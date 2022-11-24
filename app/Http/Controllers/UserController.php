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
        $users = User::with('posts')->get();
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

    public function update(Request $request)
    {
        $user = auth()->user();

        //dd($request->all());
        $validated = $request->validate([
            "profile_picture" => ["image", "mimes:jpeg,png,jpg,gif,svg", "nullable", "file"],
            "cover_picture" => ["image", "mimes:jpeg,png,jpg,gif,svg", "nullable", "file"]
        ], [
            "photo.image" => "The file uploaaded should be an image file",
            "photo.mimes" => "The file uploaaded should be an image file",
            "photo.max" => "The file should not be over 10000kb",
            "photo.file" => "The file should be an image"
        ]);


        if ($request->has("profile_picture")) {
            $user->getMedia('profile_picture')->each(function ($item, $key) {
                $item->delete();
            });
            $user->addMediaFromRequest('profile_picture')->toMediaCollection('profile_picture');
        }

        if ($request->has("cover_picture")) {
            $user->getMedia('cover_picture')->each(function ($item, $key) {
                $item->delete();
            });
            $user->addMediaFromRequest('cover_picture')->toMediaCollection('cover_picture');
        }

        return redirect()->route("user.show", $user->id);
    }
}
