<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComplaintController extends Controller
{
    /**
     * Display all complaints for admin
     */
    public function index(Request $request)
    {
        $query = Complaint::with('user');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $complaints = $query->orderBy('created_at', 'desc')
            ->paginate(20);

        $users = User::orderBy('name')->get();

        return view('admin.complaints.index', compact('complaints', 'users'));
    }

    /**
     * Show complaint detail
     */
    public function show($id)
    {
        $complaint = Complaint::with('user', 'responder')->findOrFail($id);

        return view('admin.complaints.show', compact('complaint'));
    }

    /**
     * Approve complaint
     */
    public function approve(Request $request, $id)
    {
        $complaint = Complaint::findOrFail($id);

        $complaint->update([
            'status' => 'approved',
            'response' => $request->response ?? 'Pengajuan Anda telah disetujui',
            'responded_by' => Auth::id(),
            'responded_at' => now(),
        ]);

        return redirect()->route('admin.complaints.index')
            ->with('success', 'Pengajuan berhasil disetujui');
    }

    /**
     * Reject complaint
     */
    public function reject(Request $request, $id)
    {
        $complaint = Complaint::findOrFail($id);

        $request->validate([
            'response' => 'required|string|min:10',
        ], [
            'response.required' => 'Alasan penolakan wajib diisi',
            'response.min' => 'Alasan penolakan minimal 10 karakter',
        ]);

        $complaint->update([
            'status' => 'rejected',
            'response' => $request->response,
            'responded_by' => Auth::id(),
            'responded_at' => now(),
        ]);

        return redirect()->route('admin.complaints.index')
            ->with('success', 'Pengajuan berhasil ditolak');
    }
}
