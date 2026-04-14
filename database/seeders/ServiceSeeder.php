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
        'name' => 'Outpatient Consultation',
        'code' => 'serv1001',
        'description' => 'General consultation for outpatients',
        'estimated_time' => 15 - 30 ,
        'is_active' => true,
        ]);

        Service::create([
        'department_id' => 1,
        'name' => 'Maternal and Child Health',
        'code' => 'serv1002',
        'description' => 'Maternal and Child Health services',
        'estimated_time' => 15 - 30 ,
        'is_active' => true,
        ]);

        Service::create([
        'department_id' => 1,
        'name' => 'Dental Services',
        'code' => 'serv1003',
        'description' => 'Dental services',
        'estimated_time' => 15 - 30 ,
        'is_active' => true,
        ]);
        Service::create([
        'department_id' => 1,
        'name' => 'Public health services',
        'code' => 'serv1004',
        'description' => 'Public health services',
        'estimated_time' => 15 - 30 ,
        'is_active' => true,
        ]);
        Service::create([
        'department_id' => 1,
        'name' => 'Medical Consultation',
        'code' => 'serv1005',
        'description' => 'Medical Consultation services',
        'estimated_time' => 15 - 30 ,
        'is_active' => true,
        ]);
        Service::create([
        'department_id' => 1,
        'name' => 'Implant Insertion and removal',
        'code' => 'serv1006',
        'description' => 'Implant Insertion and removal services',
        'estimated_time' => 15 - 30 ,
        'is_active' => true,
        ]);
        Service::create([
        'department_id' => 1,
        'name' => 'Minor Surgery (tuli)',
        'code' => 'serv1007',
        'description' => 'Minor Surgery (tuli) services',
        'estimated_time' => 15 - 30 ,
        'is_active' => true,
        ]);
        Service::create([
        'department_id' => 1,
        'name' => 'ECG',
        'code' => 'serv1008',
        'description' => 'ECG services',
        'estimated_time' => 15 - 30 ,
        'is_active' => true,
        ]);
        Service::create([
        'department_id' => 1,
        'name' => 'IVF therapy',
        'code' => 'serv1009',
        'description' => 'IVF therapy services',
        'estimated_time' => 15 - 30 ,
        'is_active' => true,
        ]);
        Service::create([
        'department_id' => 1,
        'name' => 'Follow up Check-up',
        'code' => 'serv1010',
        'description' => 'Follow up Check-up services',
        'estimated_time' => 15 - 30 ,
        'is_active' => true,
        ]);
    }
}
