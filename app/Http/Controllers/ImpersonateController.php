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
        $currentUser = auth()->user();

        // Security check: Don't allow impersonating other super admins
        if ($user->hasRole('Super Admin')) {
            abort(403, 'Cannot impersonate a super admin.');
        }

        // Super Admins can impersonate anyone (except other super admins, handled above)
        // Organization Admins can only impersonate users within their own company
        if ($currentUser->hasRole('Super Admin')) {
            // Allowed
        } elseif ($currentUser->hasRole('Organization Admin') && $user->company_id === $currentUser->company_id) {
            // Allowed — same company
        } else {
            abort(403, 'Unauthorized action.');
        }

        // Push the current user's ID onto the impersonation stack
        $stack = session()->get('impersonated_by', []);
        $stack[] = auth()->id();
        session()->put('impersonated_by', $stack);

        // Login as the user
        Auth::login($user);

        // Set context before redirection check
        setPermissionsTeamId($user->company_id);

        // Redirect based on role (Align with Login.php)
        if ($user->hasRole(['Agent', 'User'])) {
            return redirect()->route('flights.search')->with('status', "Now impersonating {$user->first_name}");
        }

        return redirect()->route('companies.index')->with('status', "Now impersonating {$user->first_name}");
    }

    public function leave()
    {
        $stack = session()->get('impersonated_by', []);

        if (empty($stack)) {
            return redirect()->route('travel.hub');
        }

        // Pop the last admin from the stack
        $adminId = array_pop($stack);

        if (empty($stack)) {
            session()->forget('impersonated_by');
        } else {
            session()->put('impersonated_by', $stack);
        }

        $admin = User::withoutGlobalScopes()->find($adminId);

        if ($admin) {
            Auth::login($admin);
            setPermissionsTeamId($admin->company_id);

            // Redirect based on role of the restored user
            if ($admin->hasRole('Super Admin')) {
                return redirect()->route('companies.index')->with('status', 'Returned to Super Admin session.');
            }
            return redirect()->route('companies.index')->with('status', 'Returned to admin session.');
        }

        return redirect()->route('login');
    }
}
