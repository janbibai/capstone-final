<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response;

class EnsureDoctor
{
    /**
     * Handle an incoming request. Allow only staff who are doctors (by role or position).
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            return redirect()->route('doctor.login');
        }

        $user = Auth::user();

        if (! $user->staff || ! $user->staff->is_active) {
            abort(403, 'Unauthorized: staff access only.');
        }

        $staff = $user->staff;

        // Option A: check role name if role_id exists on staff
        if (Schema::hasColumn('staff', 'role_id') && $staff->role_id) {
            $role = $staff->relationLoaded('role') ? $staff->role : $staff->role()->first();
            if ($role && $role->name === 'Doctor') {
                return $next($request);
            }
        }

        // Option C: fallback to position (e.g. "Doctor")
        if (strtolower(trim($staff->position ?? '')) === 'doctor') {
            return $next($request);
        }

        abort(403, 'Unauthorized: doctor access only.');
    }
}
