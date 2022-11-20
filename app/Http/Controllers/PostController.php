<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\UserPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function create()
    {
        return view('posts.create');
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

        if ($request->post_id != null)
            return redirect()->route("post.show", $request->post_id);
        return redirect()->route("user.show", $user_id);
    }

    public function edit($id)
    {
        $post = Post::findOrFail($id);
        return view("posts.edit", ['post' => $post]);
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

        return redirect()->route("user.show", Auth::user()->id);
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();
        return redirect()->back();
    }

    public function show($id)
    {
        $post = Post::findOrFail($id);
        return view("posts.show", [
            "post" => $post
        ]);
    }

    public function like($post_id, Request $request)
    {
        $like = $request->like === "on" ? true : false;
        $user = Auth::user();
        $like = UserPost::updateOrCreate(
            ['user_id' => $user->id, "post_id" => $post_id],
            ['like' => $like]
        );
        return redirect()->back();
    }
}
