<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 6 karakter',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // Always redirect to dashboard after successful login
            return redirect()->route('dashboard')
                ->with('success', 'Login berhasil! Selamat datang '.Auth::user()->name);
        }

        return back()
            ->withErrors(['email' => 'Email atau password salah'])
            ->withInput($request->only('email'));
    }

    /**
     * Show register form
     */
    public function showRegister()
    {
        // Restrict access: must be authenticated admin (middleware already ensures auth + role)
        /** @var \App\Models\User|null $authUser */
        $authUser = Auth::user();
        if (! $authUser || ! $authUser->hasRole('admin')) {
            abort(403);
        }

        return view('auth.register');
    }

    /**
     * Handle register request
     */
    public function register(Request $request)
    {
        // Extra guard server-side
        /** @var \App\Models\User|null $authUser */
        $authUser = Auth::user();
        if (! $authUser || ! $authUser->hasRole('admin')) {
            abort(403);
        }
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|string|max:50|unique:users,employee_id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'birth_date' => 'nullable|date',
            'gender' => 'required|in:Laki-laki,Perempuan',
        ], [
            'employee_id.required' => 'ID Card wajib diisi',
            'employee_id.unique' => 'ID Card sudah terdaftar',
            'name.required' => 'Nama wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 6 karakter',
            'gender.required' => 'Jenis kelamin wajib diisi',
            'gender.in' => 'Jenis kelamin tidak valid',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $user = User::create([
                'employee_id' => $request->employee_id,
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'address' => $request->address,
                'birth_date' => $request->birth_date,
                'gender' => $request->gender,
            ]);

            // Default new user role = employee (can be changed after creation)
            $defaultRole = Role::where('name', 'employee')->first();
            if ($defaultRole) {
                $user->roles()->attach($defaultRole->id);
            }

            return redirect()->route('admin.users.index')->with('success', 'User berhasil dibuat: '.$user->name.' (ID: '.$user->employee_id.')');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal membuat user: '.$e->getMessage()])->withInput();
        }
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Logout berhasil');
    }

    /**
     * Show change password form
     */
    public function showChangePassword()
    {
        return view('auth.change-password');
    }

    /**
     * Handle change password request
     */
    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ], [
            'current_password.required' => 'Password lama wajib diisi',
            'new_password.required' => 'Password baru wajib diisi',
            'new_password.min' => 'Password baru minimal 6 karakter',
            'new_password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator);
        }

        $user = Auth::user();

        // Check current password
        if (! Hash::check($request->current_password, $user->password)) {
            return back()
                ->withErrors(['current_password' => 'Password lama tidak sesuai']);
        }

        // Update password
        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()
            ->with('success', 'Password berhasil diubah');
    }
}
