<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Service::create([
        'department_id' => 1,
        'name' => 'birthing services',
        'code' => 'serv1001',
        'description' => 'pedi ka diri maka panganak',
        'estimated_time' => 15 ,
        'is_active' => true,
        ]);

        Service::create([
        'department_id' => 1,
        'name' => 'immunization',
        'code' => 'serv1002',
        'description' => 'ma immune ka sa mga sakit type shit',
        'estimated_time' => 15 ,
        'is_active' => true,
        ]);

        Service::create([
        'department_id' => 1,
        'name' => 'family planning',
        'code' => 'serv1003',
        'description' => 'pedi ka maka pangutana if maka anak pa ka xd',
        'estimated_time' => 15 ,
        'is_active' => true,
        ]);

        // Purok 9 services
        Service::create([
        'department_id' => 2,
        'name' => 'general consultation',
        'code' => 'serv2001',
        'description' => 'general consultation for purok 9',
        'estimated_time' => 15 ,
        'is_active' => true,
        ]);

    }
}
