<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Activity\ApproveActivityRequest;
use App\Models\Activity;
use App\Models\Partner;
use App\Models\User;
use App\Services\PushNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        $query = Activity::with(['user', 'partner']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('partner_id')) {
            $query->where('partner_id', $request->partner_id);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $activities = $query->orderBy('created_at', 'desc')->paginate(20);
        $partners = Partner::orderBy('name')->get();
        $users = User::orderBy('name')->get();

        return view('admin.activities.index', compact('activities', 'partners', 'users'));
    }

    public function show(Activity $activity)
    {
        $activity->load(['user', 'partner', 'approvedBy']);

        return view('admin.activities.show', compact('activity'));
    }

    public function approve(Activity $activity)
    {
        if ($activity->status !== 'signed') {
            return back()->with('error', 'Aktivitas belum ditandatangani PIC mitra');
        }

        $activity->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'rejected_reason' => null,
        ]);

        $activity->load('user');
        if ($activity->user?->expo_push_token) {
            app(PushNotificationService::class)->sendToExpo(
                $activity->user->expo_push_token,
                'Aktivitas Disetujui',
                'Aktivitas Anda telah disetujui.',
                ['activity_id' => $activity->id, 'status' => 'approved']
            );
        }

        return redirect()->route('admin.activities.index')
            ->with('success', 'Aktivitas berhasil disetujui');
    }

    public function reject(ApproveActivityRequest $request, Activity $activity)
    {
        $request->validate([
            'rejected_reason' => 'required|string|min:10',
        ], [
            'rejected_reason.required' => 'Alasan penolakan wajib diisi',
            'rejected_reason.min' => 'Alasan penolakan minimal 10 karakter',
        ]);

        $activity->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'rejected_reason' => $request->rejected_reason,
        ]);

        $activity->load('user');
        if ($activity->user?->expo_push_token) {
            app(PushNotificationService::class)->sendToExpo(
                $activity->user->expo_push_token,
                'Aktivitas Ditolak',
                'Aktivitas Anda ditolak. Periksa alasan penolakan.',
                [
                    'activity_id' => $activity->id,
                    'status' => 'rejected',
                    'reason' => $request->rejected_reason,
                ]
            );
        }

        return redirect()->route('admin.activities.index')
            ->with('success', 'Aktivitas berhasil ditolak');
    }
}
