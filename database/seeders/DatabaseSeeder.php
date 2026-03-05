<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call([
            DepartmentSeeder::class,
            ServiceSeeder::class,
            PatientAppointmentSeeder::class,
        ]);

        $department = Department::first();

        $user = User::firstOrCreate(
            ['email' => 'staff@example.com'],
            [
                'name' => 'Poblacion Staff',
                'password' => Hash::make('password123'),
            ]
        );

        // Check if Admin Staff already exists
        if (! Staff::where('employee_id', 'EMP-0001')->exists()) {
            Staff::create([
                'user_id' => $user->id,
                'department_id' => $department->id,
                'employee_id' => 'EMP-0001',
                'position' => 'Front Desk',
                'phone' => '09123456789',
                'is_active' => true,
            ]);
        }

        // Doctor user (for doctor dashboard access)
        $doctorUser = User::firstOrCreate(
            ['email' => 'doctor@example.com'],
            [
                'name' => 'Doctor janbai',
                'password' => Hash::make('password123'),
            ]
        );

        if (! $doctorUser->staff) {
            Staff::create([
                'user_id' => $doctorUser->id,
                'department_id' => $department?->id,
                'employee_id' => 'EMP-0002',
                'position' => 'Doctor',
                'phone' => '09187654321',
                'is_active' => true,
            ]);
        }

        // RHU Admin account
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'admin',
                'password' => Hash::make('password123'),
            ]
        );

        if (! $adminUser->staff) {
            Staff::create([
                'user_id' => $adminUser->id,
                'department_id' => $department->id,
                'employee_id' => 'EMP-0003',
                'position' => 'Admin',
                'phone' => '09987654321',
                'is_active' => true,
            ]);
        }

        // Purok 9 Department Staff & Doctor
        $purok9 = Department::where('name', 'purok 9')->first();

        // Purok 9 Staff
        $purok9StaffUser = User::firstOrCreate(
            ['email' => 'purok9staff@example.com'],
            [
                'name' => 'Purok 9 Staff',
                'password' => Hash::make('password123'),
            ]
        );

        if (! Staff::where('employee_id', 'EMP-0004')->exists()) {
            Staff::create([
                'user_id' => $purok9StaffUser->id,
                'department_id' => $purok9->id,
                'employee_id' => 'EMP-0004',
                'position' => 'Front Desk',
                'phone' => '09111111111',
                'is_active' => true,
            ]);
        }

        // Purok 9 Doctor
        $purok9DoctorUser = User::firstOrCreate(
            ['email' => 'purok9doctor@example.com'],
            [
                'name' => 'Purok 9 Doctor',
                'password' => Hash::make('password123'),
            ]
        );

        if (! Staff::where('employee_id', 'EMP-0005')->exists()) {
            Staff::create([
                'user_id' => $purok9DoctorUser->id,
                'department_id' => $purok9->id,
                'employee_id' => 'EMP-0005',
                'position' => 'Doctor',
                'phone' => '09222222222',
                'is_active' => true,
            ]);
        }
    }
}
