<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Users;
use Inertia\Inertia;

class usersinertia extends Controller
{
    public function index()
    {
        $users = Users::take(10)->get();

        return Inertia::render('Users/Index', [
            'users' => $users
        ]);
    }
}
