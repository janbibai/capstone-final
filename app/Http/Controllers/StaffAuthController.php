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
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

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
            if ($this->isDoctor()) {
                return redirect()->route('doctor.dashboard');
            }
            if ($this->isAdmin()) {
                return redirect()->route('rhu.dashboard');
            }
            if ($this->isPharmacy()) {
                return redirect()->route('pharmacy.dashboard');
            }
            return redirect()->route('staff.dashboard');
        }

        return view('auth.staff-login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $throttleKey = Str::lower($request->email) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()
                ->withErrors(['throttle' => $seconds])
                ->onlyInput('email');
        }

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            RateLimiter::hit($throttleKey, 60);
            return back()
                ->withErrors(['email' => 'These credentials do not match our records.'])
                ->onlyInput('email');
        }

        RateLimiter::clear($throttleKey);

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

        // Reject doctors from the staff login — direct them to doctor login
        if ($this->isDoctor()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()
                ->withErrors(['email' => 'Doctor accounts must use the Doctor Login page.'])
                ->onlyInput('email');
        }
        
        // Reject admins from the staff login — direct them to admin login
        if ($this->isAdmin()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()
                ->withErrors(['email' => 'Admin accounts must use the Admin Login page.'])
                ->onlyInput('email');
        }

        // Now we know it's a standard staff or pharmacy
        if ($this->isPharmacy()) {
            $dashboardRoute = 'pharmacy.dashboard';
        } else {
            $dashboardRoute = 'staff.dashboard';
        }

        return redirect()->route($dashboardRoute);
    }

    /**
     * Show the doctor login form.
     */
    public function showDoctorLoginForm()
    {
        if (Auth::check() && Auth::user()->staff && Auth::user()->staff->is_active) {
            if ($this->isDoctor()) {
                return redirect()->route('doctor.dashboard');
            }
            if ($this->isAdmin()) {
                return redirect()->route('rhu.dashboard');
            }
            if ($this->isPharmacy()) {
                return redirect()->route('pharmacy.dashboard');
            }
            return redirect()->route('staff.dashboard');
        }

        return view('auth.doctor-login');
    }

    /**
     * Handle doctor login submission.
     */
    public function doctorLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $throttleKey = Str::lower($request->email) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()
                ->withErrors(['throttle' => $seconds])
                ->onlyInput('email');
        }

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            RateLimiter::hit($throttleKey, 60);
            return back()
                ->withErrors(['email' => 'These credentials do not match our records.'])
                ->onlyInput('email');
        }

        RateLimiter::clear($throttleKey);

        $request->session()->regenerate();

        $user = Auth::user();

        if (! $user->staff || ! $user->staff->is_active) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()
                ->withErrors(['email' => 'You are not authorized.'])
                ->onlyInput('email');
        }

        // Only allow doctors through this login
        if (! $this->isDoctor()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()
                ->withErrors(['email' => 'This login is for doctors only. Please use the Staff Login page.'])
                ->onlyInput('email');
        }

        return redirect()->route('doctor.dashboard');
    }

    /**
     * Show the admin login form.
     */
    public function showAdminLoginForm()
    {
        if (Auth::check() && Auth::user()->staff && Auth::user()->staff->is_active) {
            if ($this->isAdmin()) {
                return redirect()->route('rhu.dashboard');
            }
            if ($this->isDoctor()) {
                return redirect()->route('doctor.dashboard');
            }
            if ($this->isPharmacy()) {
                return redirect()->route('pharmacy.dashboard');
            }
            return redirect()->route('staff.dashboard');
        }

        return view('auth.admin-login');
    }

    /**
     * Handle admin login submission.
     */
    public function adminLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $throttleKey = Str::lower($request->email) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()
                ->withErrors(['throttle' => $seconds])
                ->onlyInput('email');
        }

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            RateLimiter::hit($throttleKey, 60);
            return back()
                ->withErrors(['email' => 'These credentials do not match our records.'])
                ->onlyInput('email');
        }

        RateLimiter::clear($throttleKey);

        $request->session()->regenerate();

        $user = Auth::user();

        if (! $user->staff || ! $user->staff->is_active) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()
                ->withErrors(['email' => 'You are not authorized.'])
                ->onlyInput('email');
        }

        // Only allow admins through this login
        if (! $this->isAdmin()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()
                ->withErrors(['email' => 'This login is for administrators only. Please use the Staff Login page.'])
                ->onlyInput('email');
        }

        return redirect()->route('rhu.dashboard');
    }

    public function logout(Request $request)
    {
        // Determine where to redirect before logging out
        $isDoctor = $this->isDoctor();
        $isAdmin = $this->isAdmin();

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($isDoctor) {
            return redirect()->route('doctor.login');
        } elseif ($isAdmin) {
            return redirect()->route('admin.login');
        }
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
