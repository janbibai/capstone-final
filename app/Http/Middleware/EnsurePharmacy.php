<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response;

class EnsurePharmacy
{
    /**
     * Handle an incoming request. Allow only staff who are pharmacy personnel (by role or position).
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            return redirect()->route('staff.login');
        }

        $user = Auth::user();

        if (! $user->staff || ! $user->staff->is_active) {
            abort(403, 'Unauthorized: staff access only.');
        }

        $staff = $user->staff;

        // Check role name if role_id exists on staff
        if (Schema::hasColumn('staff', 'role_id') && $staff->role_id) {
            $role = $staff->relationLoaded('role') ? $staff->role : $staff->role()->first();
            if ($role && strtolower($role->name) === 'pharmacy') {
                return $next($request);
            }
        }

        // Fallback to position
        if (strtolower(trim($staff->position ?? '')) === 'pharmacy') {
            return $next($request);
        }

        abort(403, 'Unauthorized: pharmacy access only.');
    }
}
