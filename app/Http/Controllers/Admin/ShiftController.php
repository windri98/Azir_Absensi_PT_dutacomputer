<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shift;
use App\Models\User;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    public function index()
    {
        $shifts = Shift::withCount('users')->orderBy('name')->paginate(15);

        return view('admin.shifts.index', compact('shifts'));
    }

    public function create()
    {
        return view('admin.shifts.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
        ]);
        Shift::create($data);

        return redirect()->route('admin.shifts.index')->with('success', 'Shift berhasil dibuat');
    }

    public function edit(Shift $shift)
    {
        $shift->load('users');

        return view('admin.shifts.edit', compact('shift'));
    }

    public function update(Request $request, Shift $shift)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
        ]);
        $shift->update($data);

        return redirect()->route('admin.shifts.index')->with('success', 'Shift berhasil diupdate');
    }

    /**
     * Show assign form
     */
    public function assignForm(Shift $shift)
    {
        $users = User::with('roles')->orderBy('name')->get();
        $assignedUsers = $shift->users->pluck('id')->toArray();

        return view('admin.shifts.assign', compact('shift', 'users', 'assignedUsers'));
    }

    /**
     * Bulk assign users to shift
     */
    public function assignUsers(Request $request, Shift $shift)
    {
        $data = $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        // Prepare pivot data
        $pivotData = [];
        foreach ($data['user_ids'] as $userId) {
            $pivotData[$userId] = [
                'start_date' => $data['start_date'] ?? null,
                'end_date' => $data['end_date'] ?? null,
            ];
        }

        // Sync users with pivot data
        $shift->users()->syncWithoutDetaching($pivotData);

        return redirect()->route('admin.shifts.edit', $shift)
            ->with('success', count($data['user_ids']).' user berhasil di-assign ke shift ini');
    }

    /**
     * Remove user from shift
     */
    public function removeUser(Shift $shift, User $user)
    {
        $shift->users()->detach($user->id);

        return back()->with('success', 'User berhasil dihapus dari shift ini');
    }

    public function destroy(Shift $shift)
    {
        // Detach all users first
        $shift->users()->detach();
        $shift->delete();

        return redirect()->route('admin.shifts.index')->with('success', 'Shift berhasil dihapus');
    }
}
