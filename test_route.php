<?php

// Test staff login
$staff = App\Models\User::where('email', 'staff@example.com')->first();
Illuminate\Support\Facades\Auth::login($staff);
$request1 = Illuminate\Http\Request::create('/staff/login', 'GET');
$response1 = app()->make(Illuminate\Contracts\Http\Kernel::class)->handle($request1);
echo 'STAFF LOGIN REDIRECT: ' . $response1->headers->get('Location') . PHP_EOL;

// Test admin login
$admin = App\Models\User::where('email', 'admin@example.com')->first();
Illuminate\Support\Facades\Auth::login($admin);
$request2 = Illuminate\Http\Request::create('/staff/login', 'GET');
$response2 = app()->make(Illuminate\Contracts\Http\Kernel::class)->handle($request2);
echo 'ADMIN LOGIN REDIRECT: ' . $response2->headers->get('Location') . PHP_EOL;

// Test staff accessing admin route
Illuminate\Support\Facades\Auth::login($staff);
$request3 = Illuminate\Http\Request::create('/rhu/dashboard', 'GET');
$response3 = app()->make(Illuminate\Contracts\Http\Kernel::class)->handle($request3);
echo 'STAFF HITTING RHU DASHBOARD STATUS: ' . $response3->status() . PHP_EOL;
