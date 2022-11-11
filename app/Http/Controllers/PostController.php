<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        //TODO: Retreive the information of the authenticated user

        $validated = $request->validate([
            "message" => "required|max:250"
        ]);

        //TODO: upload the photo to the server and get the route to the photo

        $user_id = 1;
        $photo_route = null;
        $is_public = false;
        if ($request->is_public != null)
            $is_public = true;

        $data = [
            'user_id' => $user_id,
            'photo' => $photo_route,
            'message' => $request->message,
            'is_public' => $is_public
        ];

        Post::create($data);

        return redirect()->route("user.show", $user_id);
    }

    public function show($id)
    {
        $post = Post::find($id);
        return view("posts.show", [
            "post" => $post
        ]);
    }
}
