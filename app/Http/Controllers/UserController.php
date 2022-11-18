<?php

namespace App\Http\Controllers;

use App\Models\Follow;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function Index()
    {
        $users = User::all();
        return view("people", [
            "users" => $users
        ]);
    }

    public function follow($user_id, Request $request)
    {
        $user = Auth::user();
        $follows = Follow::where('follower_user_id', '=', $user->id)->where('following_user_id', '=', $user_id)->get();
        if (count($follows) == 0)
            Follow::create([
                'follower_user_id' => $user->id,
                'following_user_id' => $user_id
            ]);
        else {
            $ids = $follows->map(fn ($f) => $f->id);
            Follow::where('follower_user_id', '=', $user->id)->where('following_user_id', '=', $user_id)->delete();
        }
        return redirect()->back();
    }
}
