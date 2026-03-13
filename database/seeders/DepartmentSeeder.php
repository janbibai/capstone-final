<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $departments = [
            [
                'name'        => 'poblacion',
                'description' => 'poblacion Health Center',
                'is_active'   => true,
            ],
            [
                'name'        => 'purok 9',
                'description' => 'purok 9 Health Center',
                'is_active'   => true,
            ],
        ];

        foreach ($departments as $dept) {
            Department::firstOrCreate(
                ['name' => $dept['name']],   // search criteria
                $dept                          // values to fill if not found
            );
        }

        
    }
}
