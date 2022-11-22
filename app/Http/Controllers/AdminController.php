<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.index');
    }

    public function users()
    {
        $users = User::with('posts', 'media')->get();
        return view('admin.users', compact('users'));
    }

    public function posts()
    {
        $posts = Post::all();
        return view('admin.posts', compact('posts'));
    }

    public function deleteUser($id)
    {
        User::findOrFail($id)->delete();
        return redirect()->back();
    }
}
