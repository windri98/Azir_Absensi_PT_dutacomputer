<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    /**
     * Show profile page
     */
    public function show()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $stats = $this->buildAttendanceStats($user->id);

        return view('profile.profile', compact('user', 'stats'));
    }

    /**
     * Show profil page (alternative)
     */
    public function showProfil()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $stats = $this->buildAttendanceStats($user->id);

        return view('profile.profil', compact('user', 'stats'));
    }

    /**
     * Show profile detail page
     */
    public function showDetail()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->load('roles', 'attendances');
        $stats = $this->buildAttendanceStats($user->id);

        return view('profile.profile-detail', compact('user', 'stats'));
    }

    /**
     * Show edit profile form
     */
    public function edit()
    {
        $user = Auth::user();

        return view('profile.edit-profile', compact('user'));
    }

    /**
     * Update profile
     */
    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'birth_date' => 'nullable|date',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'name.required' => 'Nama wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan',
            'photo.image' => 'File harus berupa gambar',
            'photo.mimes' => 'Format gambar harus jpeg, png, atau jpg',
            'photo.max' => 'Ukuran gambar maksimal 2MB',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        // Update basic info
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->birth_date = $request->birth_date;

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($user->photo && Storage::exists('public/'.$user->photo)) {
                Storage::delete('public/'.$user->photo);
            }

            $photoPath = $request->file('photo')->store('photos', 'public');
            $user->photo = $photoPath;
        }

        $user->save();

        return redirect()->route('profile.show')->with('success', 'Profile berhasil diperbarui');
    }

    /**
     * Get profile data as JSON (for API)
     */
    public function getProfile()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->load('roles');

        return response()->json([
            'success' => true,
            'data' => $user,
        ]);
    }

    /**
     * Build attendance statistics for current month
     */
    private function buildAttendanceStats(int $userId): array
    {
        $start = now()->startOfMonth();
        $end = now()->endOfMonth();

        $query = \App\Models\Attendance::where('user_id', $userId)
            ->whereBetween('date', [$start->toDateString(), $end->toDateString()]);

        $present = (clone $query)->where('status', 'present')->count();
        $late = (clone $query)->where('status', 'late')->count();
        $hadir = $present + $late;
        $totalDays = (clone $query)->count();
        $totalHours = (clone $query)->sum('work_hours');

        return [
            'total_days' => $totalDays,
            'hadir' => $hadir,
            'terlambat' => $late,
            'total_hours' => round($totalHours ?? 0, 2),
        ];
    }

    /**
     * Update profile photo
     */
    public function updatePhoto(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 400);
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Delete old photo
        if ($user->photo && Storage::exists('public/'.$user->photo)) {
            Storage::delete('public/'.$user->photo);
        }

        // Upload new photo
        $photoPath = $request->file('photo')->store('photos', 'public');
        $user->photo = $photoPath;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Foto profil berhasil diperbarui',
            'data' => [
                'photo_url' => Storage::url($photoPath),
            ],
        ]);
    }

    /**
     * Delete profile photo
     */
    public function deletePhoto()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->photo && Storage::exists('public/'.$user->photo)) {
            Storage::delete('public/'.$user->photo);
            $user->photo = null;
            $user->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Foto profil berhasil dihapus',
        ]);
    }
}
