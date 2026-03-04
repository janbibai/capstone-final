<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class StaffAuthController extends Controller
{
    /**
     * Check if the current staff user is a doctor (by role or position).
     */
    private function isDoctor(): bool
    {
        $user = Auth::user();
        if (! $user || ! $user->staff || ! $user->staff->is_active) {
            return false;
        }
        $staff = $user->staff;
        if (Schema::hasColumn('staff', 'role_id') && $staff->role_id) {
            $role = $staff->relationLoaded('role') ? $staff->role : $staff->role()->first();
            if ($role && $role->name === 'Doctor') {
                return true;
            }
        }
        return strtolower(trim($staff->position ?? '')) === 'doctor';
    }

    /**
     * Check if the current staff user is an admin.
     */
    private function isAdmin(): bool
    {
        $user = Auth::user();
        if (! $user || ! $user->staff || ! $user->staff->is_active) {
            return false;
        }
        $staff = $user->staff;
        if (Schema::hasColumn('staff', 'role_id') && $staff->role_id) {
            $role = $staff->relationLoaded('role') ? $staff->role : $staff->role()->first();
            if ($role && strtolower($role->name) === 'admin') {
                return true;
            }
        }
        return strtolower(trim($staff->position ?? '')) === 'admin';
    }

    public function showLoginForm()
    {
        if (Auth::check() && Auth::user()->staff && Auth::user()->staff->is_active) {
            if ($this->isAdmin()) {
                return redirect()->route('rhu.dashboard');
            }
            return redirect()->route($this->isDoctor() ? 'doctor.dashboard' : 'staff.dashboard');
        }

        return view('auth.staff-login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withErrors(['email' => 'These credentials do not match our records.'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        $user = Auth::user();

        if (! $user->staff || ! $user->staff->is_active) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()
                ->withErrors(['email' => 'You are not authorized as staff.'])
                ->onlyInput('email');
        }

        if ($this->isAdmin()) {
            $dashboardRoute = 'rhu.dashboard';
        } else {
            $dashboardRoute = $this->isDoctor() ? 'doctor.dashboard' : 'staff.dashboard';
        }

        return redirect()->intended(route($dashboardRoute));
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}

