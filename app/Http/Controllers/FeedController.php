<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedController extends Controller
{
    public function Index()
    {
        //TODO: Retrieve only friends posts and public posts
        $user = auth()->user();
        $is_logged_in = Auth::check();
        $following_users = $is_logged_in ? $user->following->map(fn ($f) => $f['id'])->toArray() : [];

        $posts = Post::orderBy("created_at", "desc")
            ->whereNull('post_id')
            ->where(function ($query) use ($following_users, $user) {
                $query->whereIn('user_id', $following_users)
                    ->orWhere('is_public', '=', true)
                    ->orWhere('user_id', '=', $user->id);
            })
            ->with('user', 'comments', 'likes', 'dislikes', 'media')
            ->get();

        return view("feed", [
            'posts' => $posts
        ]);
    }
}
