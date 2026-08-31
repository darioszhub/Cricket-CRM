<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\login;

class userview extends Controller
{
    function user()
    {
        $users = login::all();

        return view('users', ['users' => $users]);
    }
}
