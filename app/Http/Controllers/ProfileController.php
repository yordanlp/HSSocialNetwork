<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ProfileController extends Controller
{
    public function Index($user_id)
    {
        $user = User::with('posts', 'posts.comments', 'posts.user', 'posts.likes', 'posts.dislikes', 'followers', 'following', 'posts.media', 'posts.parent')->findOrFail($user_id);
        Session::put('profile-posts', $user->posts);
        return view("profile", [
            "user" => $user
        ]);
    }
}
