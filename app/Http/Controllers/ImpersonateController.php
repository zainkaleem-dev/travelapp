<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ImpersonateController extends Controller
{
    public function take($userId)
    {
        $user = User::withoutGlobalScopes()->findOrFail($userId);

        // Security check: Only Super Admins can initiate impersonation
        if (!auth()->user()->hasRole('super_admin')) {
            abort(403, 'Unauthorized action.');
        }

        // Security check: Don't allow impersonating other super admins (optional but safer)
        if ($user->hasRole('super_admin')) {
            abort(403, 'Cannot impersonate a super admin.');
        }

        // Store original admin ID
        session()->put('impersonated_by', auth()->id());

        // Login as the user
        Auth::login($user);

        return redirect()->route('travel.hub')->with('status', "Now impersonating {$user->first_name}");
    }

    public function leave()
    {
        if (!session()->has('impersonated_by')) {
            return redirect()->route('travel.hub');
        }

        $adminId = session()->pull('impersonated_by');
        $admin = User::withoutGlobalScopes()->find($adminId);

        if ($admin) {
            Auth::login($admin);
            return redirect()->route('superadmin.users')->with('status', 'Returned to admin session.');
        }

        return redirect()->route('login');
    }
}
