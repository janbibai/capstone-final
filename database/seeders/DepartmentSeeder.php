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
                'name'=> 'Calango',
                'description'=> 'Calango Health Center',
                'is_active' => true,
            ],
            [
                'name'=> 'Lotuban',
                'description'=> 'Lotuban Health Center',
                'is_active' => true,
            ],
            [
                'name'=> 'Malongcay Diot',
                'description'=> 'Malongcay Diot Health Center',
                'is_active' => true,
            ],
            [
                'name'=> 'Maluay',
                'description'=> 'Maluay Health Center',
                'is_active' => true,
            ],
            [
                'name'=> 'Mayabon',
                'description'=> 'Mayabon Health Center',
                'is_active' => true,
            ],
            [
                'name'=> 'Nabago',
                'description'=> 'Nabago Health Center',
                'is_active' => true,
            ],
            [
                'name'=> 'Nasig-id',
                'description'=> 'Nasig-id Health Center',
                'is_active' => true,
            ],
            [
                'name'=> 'Najandig',
                'description'=> 'Najandig Health Center',
                'is_active' => true,
            ],    
        ];

        foreach ($departments as $dept) {
            Department::updateOrCreate(
                ['name' => $dept['name']], // unique identifier
                $dept
            );
        }

        
    }
}
