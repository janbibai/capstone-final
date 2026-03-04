<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
    /**
     * Handle an incoming request.
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
        $isAdmin = false;

        if (Schema::hasColumn('staff', 'role_id') && $staff->role_id) {
            $role = $staff->relationLoaded('role') ? $staff->role : $staff->role()->first();
            if ($role && strtolower($role->name) === 'admin') {
                $isAdmin = true;
            }
        }

        if (! $isAdmin && strtolower(trim($staff->position ?? '')) === 'admin') {
            $isAdmin = true;
        }

        if (! $isAdmin) {
             abort(403, 'Unauthorized: Admin access only.');
        }

        return $next($request);
    }
}
