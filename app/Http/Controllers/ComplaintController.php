<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ComplaintController extends Controller
{
    /**
     * Show complaint form
     */
    public function showForm()
    {
        return view('complaints.complaint-form');
    }

    /**
     * Store new complaint
     */
    public function store(Request $request)
    {
        $isIzinOrSakit = in_array($request->input('category'), ['izin', 'sakit'], true);

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'nullable|string|max:100',
            'priority' => 'nullable|in:low,medium,normal,high,urgent',
            'attachment' => ($isIzinOrSakit ? 'required' : 'nullable').'|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
            'admin_notes' => 'nullable|string|max:1000',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ], [
            'title.required' => 'Judul keluhan wajib diisi',
            'description.required' => 'Deskripsi keluhan wajib diisi',
            'attachment.required' => 'Lampiran wajib untuk pengajuan izin atau sakit',
            'attachment.mimes' => 'File harus berupa pdf, doc, docx, jpg, jpeg, atau png',
            'attachment.max' => 'Ukuran file maksimal 5MB',
            'end_date.after_or_equal' => 'Tanggal selesai harus setelah atau sama dengan tanggal mulai',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', $validator->errors()->first());
        }

        $data = [
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category ?? 'general',
            'priority' => $request->priority ?? 'medium',
            'status' => 'pending',
        ];

        // Add admin notes if provided (especially for sick leave)
        if ($request->filled('admin_notes')) {
            $data['notes'] = $request->admin_notes;
        }

        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('complaints', 'public');
            $data['attachment'] = $attachmentPath;
        }

        try {
            $complaint = Complaint::create($data);

            // Send notification to all admins
            $this->notifyAdmins($complaint);

            // Check if request came from izin page (mobile view)
            if ($request->has('start_date') || ($request->header('referer') && str_contains($request->header('referer'), 'activities/izin'))) {
                return redirect()->route('leave.index')
                    ->with('success', 'Pengajuan izin berhasil dikirim dan menunggu persetujuan');
            }

            return redirect()->route('complaints.history')
                ->with('success', 'Keluhan berhasil dikirim');
        } catch (\Exception $e) {
            \Log::error('Error storing complaint: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'category' => $request->category,
            ]);
            return back()
                ->withInput()
                ->with('error', 'Gagal menyimpan pengajuan: '.$e->getMessage());
        }
    }

    /**
     * Show user's complaint history
     */
    public function history(Request $request)
    {
        $query = Complaint::with('user', 'responder')
            ->where('user_id', Auth::id());

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $complaints = $query->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('complaints.complaint-history', compact('complaints'));
    }

    /**
     * Show technician complaints (for admin/manager)
     */
    public function technicianComplaints(Request $request)
    {
        if (! Auth::user()->hasAnyRole(['admin', 'manager'])) {
            abort(403, 'Unauthorized');
        }

        $query = Complaint::with('user', 'responder');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $complaints = $query->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('complaints.technician-complaints', compact('complaints'));
    }

    /**
     * Respond to complaint (admin/manager only)
     */
    public function respond(Request $request, $id)
    {
        if (! Auth::user()->hasAnyRole(['admin', 'manager'])) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'response' => 'required|string',
            'status' => 'required|in:pending,in_progress,resolved,closed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 400);
        }

        $complaint = Complaint::findOrFail($id);
        $complaint->update([
            'response' => $request->response,
            'status' => $request->status,
            'responded_by' => Auth::id(),
            'responded_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Respon berhasil disimpan',
            'data' => $complaint,
        ]);
    }

    /**
     * Delete complaint
     */
    public function destroy($id)
    {
        $complaint = Complaint::findOrFail($id);

        if ($complaint->user_id !== Auth::id() && ! Auth::user()->hasRole('admin')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        if ($complaint->attachment && Storage::exists('public/'.$complaint->attachment)) {
            Storage::delete('public/'.$complaint->attachment);
        }

        $complaint->delete();

        return response()->json([
            'success' => true,
            'message' => 'Keluhan berhasil dihapus',
        ]);
    }

    /**
     * Show izin/cuti page - OPTIMIZED (mobile-friendly)
     */
    public function showIzinPage()
    {
        try {
            \Log::debug('showIzinPage entry');
            
            $user = Auth::user();

            if (!$user) {
                \Log::warning('showIzinPage user not authenticated');
                return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
            }

            $userId = $user->id;

            // Get recent leave complaints
            $complaints = Complaint::where('user_id', $userId)
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get();

            // Get leave balance using service
            $leaveService = app(\App\Services\LeaveService::class);
            $leaveBalance = $leaveService->getLeaveBalance($user);

            \Log::debug('showIzinPage view rendered', ['complaints_count' => $complaints->count()]);

            return view('activities.izin', compact('complaints', 'leaveBalance', 'user'));
        } catch (\Exception $e) {
            \Log::error('Error in showIzinPage: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return redirect()->route('dashboard')->with('error', 'Terjadi kesalahan saat memuat halaman izin');
        }
    }

    /**
     * Notify all admins about new complaint/leave request
     */
    private function notifyAdmins(Complaint $complaint): void
    {
        try {
            $user = $complaint->user;
            $notificationService = app(NotificationService::class);

            // Get all admin users
            $admins = User::role(['admin', 'manager'])
                ->select('id', 'name', 'email')
                ->get();

            if ($admins->isEmpty()) {
                return;
            }

            // Determine notification type based on category
            $categoryLabel = match($complaint->category) {
                'cuti' => 'Cuti',
                'sakit' => 'Sakit',
                'izin' => 'Izin',
                default => 'Keluhan',
            };

            $title = "Pengajuan {$categoryLabel} Baru dari {$user->name}";
            $message = "{$user->name} mengajukan {$categoryLabel} dengan judul: {$complaint->title}";

            // Send notification to each admin
            foreach ($admins as $admin) {
                $notificationService->sendToUser($admin, 'complaint', $title, $message, [
                    'complaint_id' => $complaint->id,
                    'category' => $complaint->category,
                    'user_id' => $user->id,
                    'status' => $complaint->status,
                ]);
            }

            \Log::info('Admin notifications sent for complaint', [
                'complaint_id' => $complaint->id,
                'admin_count' => $admins->count(),
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to send admin notifications: ' . $e->getMessage(), [
                'complaint_id' => $complaint->id,
            ]);
        }
    }
}
