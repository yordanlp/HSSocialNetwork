<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use function PHPUnit\Framework\returnSelf;

class FeedController extends Controller
{
    public function Index(Request $request)
    {
        $search = $request->query('search') ?? "";
        $user = auth()->user();
        $is_logged_in = Auth::check();
        $following_users = $is_logged_in ? $user->following->map(fn ($f) => $f['id'])->toArray() : [];

        $posts = Post::orderBy("posts.created_at", "desc")
            ->with('user', 'comments', 'likes', 'dislikes', 'media', 'parent', 'user.media')
            ->join('users', 'users.id', '=', 'posts.user_id')
            ->where(function ($query) use ($search) {
                if ($search == "")
                    return $query;
                return $query->where("message", 'like', "%" . $search . "%")->orWhere("users.name", 'like', "%" . $search . "%");
            })
            ->where(function ($query) use ($following_users) {
                return $query->whereIn('user_id', $following_users)->orWhere('is_public', '=', true);
            })->paginate(15, ['posts.*']);



        ray($posts);

        return view("feed", [
            'posts' => $posts
        ]);
    }
}
