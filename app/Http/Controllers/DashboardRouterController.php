<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardRouterController extends Controller
{
    /**
     * Route user to appropriate dashboard based on permissions
     */
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        
        // Check for specific dashboard type request
        $dashboardType = $request->query('type');
        
        if ($dashboardType === 'admin' && $user->hasPermission('dashboard.admin')) {
            return redirect()->route('admin.dashboard');
        }
        
        if ($dashboardType === 'user' && $user->hasPermission('dashboard.view')) {
            return redirect()->route('user.dashboard');
        }

        // Default behavior: prioritize user dashboard if available
        if ($user->hasPermission('dashboard.view')) {
            return redirect()->route('user.dashboard');
        }
        
        // Fallback to admin dashboard if user doesn't have user dashboard permission
        if ($user->hasPermission('dashboard.admin')) {
            return redirect()->route('admin.dashboard');
        }

        // If no dashboard permissions, redirect to profile or access denied
        return redirect()->route('profile.show')
            ->with('warning', 'Anda tidak memiliki akses ke dashboard. Silakan hubungi administrator.');
    }
}