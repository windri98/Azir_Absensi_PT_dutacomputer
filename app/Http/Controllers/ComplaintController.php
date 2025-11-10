<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
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
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'nullable|string|max:100',
            'priority' => 'nullable|in:low,medium,normal,high,urgent',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ], [
            'title.required' => 'Judul keluhan wajib diisi',
            'description.required' => 'Deskripsi keluhan wajib diisi',
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

        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('complaints', 'public');
            $data['attachment'] = $attachmentPath;
        }

        try {
            $complaint = Complaint::create($data);

            // Check if request came from izin page (mobile view)
            if ($request->has('start_date') || ($request->header('referer') && str_contains($request->header('referer'), 'activities/izin'))) {
                return redirect()->route('activities.izin')
                    ->with('success', 'Pengajuan izin berhasil dikirim dan menunggu persetujuan');
            }

            return redirect()->route('complaints.history')
                ->with('success', 'Keluhan berhasil dikirim');
        } catch (\Exception $e) {
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
     * Show izin/cuti page (mobile-friendly)
     */
    public function showIzinPage()
    {
        $user = Auth::user();
        $userId = $user->id;

        // Ambil semua complaints user dengan response
        $complaints = Complaint::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Hitung sisa cuti
        $leaveBalance = [
            'annual' => $user->getRemainingAnnualLeave(),
            'sick' => $user->getRemainingSickLeave(),
            'special' => $user->getRemainingSpecialLeave(),
        ];

        return view('activities.izin', compact('complaints', 'leaveBalance'));
    }
}
