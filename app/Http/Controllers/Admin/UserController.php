<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\AuditLog;
use App\Models\Role;
use App\Models\Shift;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('q');
        $query = User::with(['roles', 'shifts'])
            ->withCount([
                'attendances as total_hadir' => function ($q) {
                    $q->whereMonth('date', date('m'))
                        ->whereYear('date', date('Y'))
                        ->whereNotNull('check_in');
                },
                'attendances as total_terlambat' => function ($q) {
                    $q->whereMonth('date', date('m'))
                        ->whereYear('date', date('Y'))
                        ->where('status', 'late');
                },
            ])
            ->withSum([
                'attendances as total_jam_kerja' => function ($q) {
                    $q->whereMonth('date', date('m'))
                        ->whereYear('date', date('Y'))
                        ->whereNotNull('check_out');
                },
            ], DB::raw('CAST(work_hours AS DECIMAL(10,2))'));

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%')
                    ->orWhere('employee_id', 'like', '%'.$search.'%')
                    ->orWhere('phone', 'like', '%'.$search.'%');
            });
        }
        $users = $query->orderBy('id', 'desc')->paginate(15);
        $roles = Role::all();

        return view('admin.users.index', compact('users', 'roles', 'search'));
    }

    public function create()
    {
        $roles = Role::all();
        $shifts = Shift::all();

        return view('admin.users.create', compact('roles', 'shifts'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'employee_id' => 'required|string|max:50|unique:users,employee_id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'birth_date' => 'nullable|date',
            'roles' => 'array',
            'shifts' => 'array',
        ]);
        $user = User::create([
            'employee_id' => $data['employee_id'],
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone' => $data['phone'] ?? null,
            'address' => $data['address'] ?? null,
            'birth_date' => $data['birth_date'] ?? null,
        ]);
        if (! empty($data['roles'])) {
            $user->roles()->attach($data['roles']);
        }
        if (! empty($data['shifts'])) {
            $user->shifts()->attach($data['shifts']);
        }

        // Audit log
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'create',
            'model_type' => 'User',
            'model_id' => $user->id,
            'changes' => ['employee_id' => $user->employee_id, 'name' => $user->name, 'email' => $user->email],
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User created');
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        $shifts = Shift::all();
        $assigned = $user->roles->pluck('id')->toArray();
        $assignedShifts = $user->shifts->pluck('id')->toArray();

        // Get attendance stats for this month
        $attendanceStats = [
            'total_hadir' => $user->attendances()
                ->whereMonth('date', date('m'))
                ->whereYear('date', date('Y'))
                ->whereNotNull('check_in')
                ->count(),
            'total_terlambat' => $user->attendances()
                ->whereMonth('date', date('m'))
                ->whereYear('date', date('Y'))
                ->where('status', 'late')
                ->count(),
            'total_jam_kerja' => $user->attendances()
                ->whereMonth('date', date('m'))
                ->whereYear('date', date('Y'))
                ->whereNotNull('check_out')
                ->sum(DB::raw('CAST(work_hours AS DECIMAL(10,2))')),
        ];

        return view('admin.users.edit', compact('user', 'roles', 'shifts', 'assigned', 'assignedShifts', 'attendanceStats'));
    }

    public function update(Request $request, User $user)
    {
        $oldData = ['employee_id' => $user->employee_id, 'name' => $user->name, 'email' => $user->email];

        $data = $request->validate([
            'employee_id' => 'required|string|max:50|unique:users,employee_id,'.$user->id,
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'password' => 'nullable|min:6',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'birth_date' => 'nullable|date',
            'annual_leave_quota' => 'required|integer|min:0|max:30',
            'sick_leave_quota' => 'required|integer|min:0|max:30',
            'special_leave_quota' => 'required|integer|min:0|max:30',
            'roles' => 'array',
            'shifts' => 'array',
        ]);

        $user->employee_id = $data['employee_id'];
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->phone = $data['phone'] ?? null;
        $user->address = $data['address'] ?? null;
        $user->birth_date = $data['birth_date'] ?? null;
        $user->annual_leave_quota = $data['annual_leave_quota'];
        $user->sick_leave_quota = $data['sick_leave_quota'];
        $user->special_leave_quota = $data['special_leave_quota'];

        if (! empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }
        $user->save();
        $user->roles()->sync($data['roles'] ?? []);
        $user->shifts()->sync($data['shifts'] ?? []);

        // Audit log
        $changes = array_merge(['old' => $oldData], ['new' => ['employee_id' => $user->employee_id, 'name' => $user->name, 'email' => $user->email]]);
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'update',
            'model_type' => 'User',
            'model_id' => $user->id,
            'changes' => $changes,
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User updated');
    }

    public function destroy(User $user)
    {
        $userData = ['id' => $user->id, 'name' => $user->name, 'email' => $user->email];
        $user->delete();

        // Audit log
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'delete',
            'model_type' => 'User',
            'model_id' => $userData['id'],
            'changes' => $userData,
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User deleted');
    }

    public function assignRole(Request $request, User $user)
    {
        $request->validate(['role_id' => 'required|exists:roles,id']);
        if (! $user->roles->contains($request->role_id)) {
            $user->roles()->attach($request->role_id);
        }

        return back()->with('success', 'Role assigned');
    }

    public function removeRole(User $user, Role $role)
    {
        $user->roles()->detach($role->id);

        return back()->with('success', 'Role removed');
    }

    /**
     * Export attendance to CSV
     */
    public function exportAttendance(Request $request)
    {
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        $attendances = \App\Models\Attendance::with('user')
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->orderBy('date', 'desc')
            ->get();

        $filename = "attendance_{$year}_{$month}.csv";
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($attendances) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Tanggal', 'Nama', 'Email', 'Check In', 'Check Out', 'Status', 'Jam Kerja']);

            foreach ($attendances as $att) {
                fputcsv($file, [
                    $att->date,
                    $att->user->name,
                    $att->user->email,
                    $att->check_in ?? '-',
                    $att->check_out ?? '-',
                    $att->status,
                    $att->work_hours ?? 0,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Show user attendance details
     */
    public function showAttendance(Request $request, User $user)
    {
        $month = $request->get('month', date('m'));
        $year = $request->get('year', date('Y'));

        $attendances = $user->attendances()
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->orderBy('date', 'desc')
            ->paginate(20);

        // Calculate summary
        $summary = [
            'total_hadir' => $user->attendances()
                ->whereMonth('date', $month)
                ->whereYear('date', $year)
                ->whereNotNull('check_in')
                ->count(),
            'total_terlambat' => $user->attendances()
                ->whereMonth('date', $month)
                ->whereYear('date', $year)
                ->where('status', 'late')
                ->count(),
            'total_jam' => $user->attendances()
                ->whereMonth('date', $month)
                ->whereYear('date', $year)
                ->whereNotNull('check_out')
                ->sum(DB::raw('CAST(work_hours AS DECIMAL(10,2))')),
        ];

        $summary['avg_jam'] = $summary['total_hadir'] > 0
            ? number_format($summary['total_jam'] / $summary['total_hadir'], 1)
            : '0';

        return view('admin.users.attendance', compact('user', 'attendances', 'month', 'year', 'summary'));
    }
}
