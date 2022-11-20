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
            "message" => "required|max:250",
            "photo" => "image|mimes:jpeg,png,jpg,gif,svg|max:10000"
        ]);

        //TODO: upload the photo to the server and get the route to the photo

        $user_id = $user->id;
        $is_public = false;
        if ($request->is_public != null)
            $is_public = true;

        $data = [
            'user_id' => $user_id,
            'photo' => $photo_route,
            'message' => $request->message,
            'is_public' => $is_public,
            'post_id' => $request->post_id
        ];

        Post::create($data);

        if ($request->post_id != null)
            return redirect()->route("post.show", $request->post_id);
        return redirect()->route("user.show", $user_id);
    }

    public function edit($id)
    {
        $post = Post::find($id);
        return view("posts.edit", ['post' => $post]);
    }

    public function update($id)
    {
        dd();
    }

    public function destroy($id)
    {
        dd();
    }

    public function show($id)
    {
        $post = Post::find($id);
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
