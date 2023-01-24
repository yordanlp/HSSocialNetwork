<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function index(Request $request, string $search = '')
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
                return $query->where("message", 'like', "%" . $search . "%")
                    ->orWhere("users.name", 'like', "%" . $search . "%");
            })
            ->where(function ($query) use ($following_users) {
                return $query->whereIn('user_id', $following_users)->orWhere('is_public', '=', true);
            })->paginate(15, ['posts.*']);

        return [
            'posts' => $posts
        ];
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            "message" => ["max:250", "nullable", "required_if:photo,null"],
            "photo" => ["image", "mimes:jpeg,png,jpg,gif,svg", "max:10000", "nullable", "required_if:message,null", "file"]
        ], [
            "photo.image" => "The file uploaaded should be an image file",
            "photo.mimes" => "The file uploaaded should be an image file",
            "photo.max" => "The file should not be over 10000kb",
            "photo.file" => "The file should be an image"
        ]);

        $user_id = $user->id;
        $is_public = false;
        if ($request->is_public != null)
            $is_public = true;

        $data = [
            'user_id' => $user_id,
            'message' => $request->message,
            'is_public' => $is_public,
            'post_id' => $request->post_id
        ];

        $post = Post::create($data);

        if ($request->has("photo"))
            $post->addMediaFromRequest('photo')->toMediaCollection();

        return $post;
    }

    public function show($id)
    {
        $post = Post::with(
            'likes',
            'dislikes',
            'media',
            'user',
            'user.media',
            'comments',
            'comments.user',
            'comments.parent',
            'comments.media',
            'comments.likes',
            'comments.dislikes',
            'comments.comments'
        )->findOrFail($id);
        return [
            "post" => $post
        ];
    }

    public function update($id, Request $request)
    {
        $post = Post::findOrFail($id);

        $validated = $request->validate([
            "message" => ["max:250", "nullable", "required_if:photo,null"],
            "photo" => ["image", "mimes:jpeg,png,jpg,gif,svg", "max:10000", "nullable", "required_if:message,null", "file"]
        ], [
            "photo.image" => "The file uploaaded should be an image file",
            "photo.mimes" => "The file uploaaded should be an image file",
            "photo.max" => "The file should not be over 10000kb",
            "photo.file" => "The file should be an image"
        ]);

        $is_public = false;
        if ($request->is_public != null)
            $is_public = true;

        $data = [
            'message' => $request->message,
            'is_public' => $is_public,
        ];

        $post->update($data);

        if ($request->has("photo")) {
            $post->media->each(function ($item, $key) {
                $item->delete();
            });
            $post->addMediaFromRequest('photo')->toMediaCollection();
        }

        return $post;
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();
        return $post;
    }
}
