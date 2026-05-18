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

        // Get impersonation stack to check the original session owner
        $stack = session()->get('impersonated_by', []);
        $originalUserId = !empty($stack) ? $stack[0] : null;
        
        $isOriginalSuperAdmin = false;
        if ($originalUserId) {
            $originalUser = User::withoutGlobalScopes()->find($originalUserId);
            if ($originalUser && $originalUser->hasRole('Super Admin')) {
                $isOriginalSuperAdmin = true;
            }
        }

        // Super Admins (either currently logged in or the original session owner) can impersonate anyone
        // Admins (Organization Admin, Partner Admin, Branch Admin) can impersonate users within their own company or descendant companies in the hierarchy
        $allowed = false;
        if ($currentUser->hasRole('Super Admin') || $isOriginalSuperAdmin) {
            $allowed = true;
        } elseif ($currentUser->hasRole(['Organization Admin', 'Partner Admin', 'Branch Admin'])) {
            $manageableHierarchy = app(\App\Support\TenantContext::class)->getManageableHierarchy($currentUser);
            if (in_array((int) $user->company_id, $manageableHierarchy, true)) {
                $allowed = true;
            }
        }

        if (!$allowed) {
            abort(403, 'Unauthorized action.');
        }

        // Push the current user's ID onto the impersonation stack
        $stack = session()->get('impersonated_by', []);
        $stack[] = auth()->id();
        session()->put('impersonated_by', $stack);

        // Login as the user
        Auth::login($user);

        // Sync the active company in session with the impersonated user's company
        if ($user->hasRole('Super Admin')) {
            session()->forget('active_company_id');
        } else {
            session(['active_company_id' => $user->company_id]);
        }

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

            // Sync the active company in session with the restored admin's company
            if ($admin->hasRole('Super Admin')) {
                session()->forget('active_company_id');
            } else {
                session(['active_company_id' => $admin->company_id]);
            }

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
