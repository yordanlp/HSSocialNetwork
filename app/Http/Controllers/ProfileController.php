<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function Index($user_id)
    {
        $user = User::with('posts', 'posts.comments', 'posts.user', 'posts.likes', 'posts.dislikes', 'followers', 'following', 'posts.media')->find($user_id);
        return view("profile", [
            "user" => $user
        ]);
    }
}
