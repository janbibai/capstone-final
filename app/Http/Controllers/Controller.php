<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\StoreUserRequest;

abstract class Controller
{
    // public function store(StoreUserRequest $request)
    // {

    //     User::create([
    //         'name'=> $request->name,
    //         'email'=> $request->email,
    //         'password'=> bcrypt($request->password),
    //     ]); 

    //     return back()->with('success','naka register ka type shit');

    // }
}
