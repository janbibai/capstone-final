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
        Department::insert([

            ['name'=> 'poblacion',
            'description'=> 'poblacion Health Center',
            'is_active'=> true],

            ['name'=> 'purok 9',
            'description'=> 'purok 9 Health Center',
            'is_active'=> true],
            
        ]);
    }
}
