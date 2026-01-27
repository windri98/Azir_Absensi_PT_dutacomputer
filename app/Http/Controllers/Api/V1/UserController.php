<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Get current user profile
     */
    public function profile()
    {
        return response()->json([
            'success' => true,
            'data' => new UserResource(Auth::user()),
        ]);
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string|max:20',
            'address' => 'sometimes|string',
            'birth_date' => 'sometimes|date',
            'gender' => 'sometimes|in:male,female,other',
        ]);

        $user = Auth::user();
        $user->update($request->only(['name', 'phone', 'address', 'birth_date', 'gender']));

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'data' => new UserResource($user),
        ]);
    }

    /**
     * Change password
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Current password is incorrect',
            ], 400);
        }

        $user->update(['password' => Hash::make($request->new_password)]);

        return response()->json([
            'success' => true,
            'message' => 'Password changed successfully',
        ]);
    }

    /**
     * Upload profile photo
     */
    public function uploadPhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();

        try {
            if ($request->hasFile('photo')) {
                $path = $request->file('photo')->store('photos', 'public');
                $user->update(['photo' => $path]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Photo uploaded successfully',
                'data' => new UserResource($user),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload photo',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store Expo push token
     */
    public function storePushToken(Request $request)
    {
        $request->validate([
            'token' => 'required|string|max:255',
        ]);

        $user = Auth::user();
        $user->update(['expo_push_token' => $request->token]);

        return response()->json([
            'success' => true,
            'message' => 'Push token updated',
        ]);
    }

    /**
     * Get all users (admin only)
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', User::class);

        $request->validate([
            'search' => 'nullable|string|max:255',
            'role' => 'nullable|string|max:100',
            'department' => 'nullable|string|max:255',
            'limit' => 'nullable|integer|min:1|max:100',
        ]);

        // Eager load relationships to avoid N+1 queries
        $query = User::with(['roles.permissions']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('employee_id', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        // Filter by department
        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }

        $limit = min($request->get('limit', 20), 100);
        $users = $query->paginate($limit);

        return response()->json([
            'success' => true,
            'data' => UserResource::collection($users->items()),
            'pagination' => [
                'total' => $users->total(),
                'per_page' => $users->perPage(),
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
            ],
        ]);
    }

    /**
     * Get single user (admin only)
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        $this->authorize('view', $user);

        return response()->json([
            'success' => true,
            'data' => new UserResource($user),
        ]);
    }

    /**
     * Create new user (admin only)
     */
    public function store(Request $request)
    {
        $this->authorize('create', User::class);

        $request->validate([
            'employee_id' => 'required|string|unique:users',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'department' => 'nullable|string',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
        ]);

        try {
            $user = DB::transaction(function () use ($request) {
                $user = User::create([
                    'employee_id' => $request->employee_id,
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'phone' => $request->phone,
                    'address' => $request->address,
                    'birth_date' => $request->birth_date,
                    'gender' => $request->gender,
                    'department' => $request->department,
                ]);

                $user->roles()->attach($request->roles);
                return $user;
            });
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create user',
                'error' => $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'User created successfully',
            'data' => new UserResource($user),
        ], 201);
    }

    /**
     * Update user (admin only)
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $this->authorize('update', $user);

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $id,
            'phone' => 'sometimes|string|max:20',
            'address' => 'sometimes|string',
            'birth_date' => 'sometimes|date',
            'gender' => 'sometimes|in:male,female,other',
            'department' => 'sometimes|string',
            'roles' => 'sometimes|array',
            'roles.*' => 'exists:roles,id',
        ]);

        $user->update($request->only(['name', 'email', 'phone', 'address', 'birth_date', 'gender', 'department']));

        if ($request->has('roles')) {
            $user->roles()->sync($request->roles);
        }

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully',
            'data' => new UserResource($user),
        ]);
    }

    /**
     * Delete user (admin only)
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $this->authorize('delete', $user);
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully',
        ]);
    }
}
