<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function Index()
    {
        $users = User::all();
        return view("people", [
            "users" => $users
        ]);
    }
}
