<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Role;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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

    /**
     * Check if the current staff user is pharmacy personnel.
     */
    private function isPharmacy(): bool
    {
        $user = Auth::user();
        if (! $user || ! $user->staff || ! $user->staff->is_active) {
            return false;
        }
        $staff = $user->staff;
        if (Schema::hasColumn('staff', 'role_id') && $staff->role_id) {
            $role = $staff->relationLoaded('role') ? $staff->role : $staff->role()->first();
            if ($role && strtolower($role->name) === 'pharmacy') {
                return true;
            }
        }
        return strtolower(trim($staff->position ?? '')) === 'pharmacy';
    }

    public function showLoginForm()
    {
        if (Auth::check() && Auth::user()->staff && Auth::user()->staff->is_active) {
            if ($this->isAdmin()) {
                return redirect()->route('rhu.dashboard');
            }
            if ($this->isPharmacy()) {
                return redirect()->route('pharmacy.dashboard');
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
        } elseif ($this->isPharmacy()) {
            $dashboardRoute = 'pharmacy.dashboard';
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

    public function showRegisterForm()
    {
        if (Auth::check() && Auth::user()->staff && Auth::user()->staff->is_active) {
            return redirect()->route('staff.dashboard');
        }

        $departments = Department::where('is_active', true)->get();

        return view('auth.staff-register', compact('departments'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'email', 'max:255', 'unique:users,email'],
            'password'      => ['required', 'string', 'min:8', 'confirmed'],
            'position'      => ['required', 'in:Staff,Doctor,Pharmacy'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'phone'         => ['nullable', 'string', 'max:20'],
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // Try to match a role by position name
            $role = Role::where('name', $request->position)->first();

            // Auto-generate employee ID
            $latestStaff = Staff::orderByDesc('id')->first();
            $nextNumber  = $latestStaff ? ($latestStaff->id + 1) : 1;
            $employeeId  = 'EMP-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

            $staffData = [
                'user_id'       => $user->id,
                'department_id' => $request->department_id,
                'employee_id'   => $employeeId,
                'position'      => $request->position,
                'phone'         => $request->phone,
                'is_active'     => false, // requires admin approval
            ];

            // Only set role_id if the column exists on the staff table
            if (Schema::hasColumn('staff', 'role_id') && $role) {
                $staffData['role_id'] = $role->id;
            }

            Staff::create($staffData);
        });

        return redirect()->route('staff.login')
            ->with('success', 'Registration successful! Your account is pending admin approval.');
    }
}
