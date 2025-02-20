<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post:: 
        orderBy('published_at', 'desc');
        return response()->json($posts);
    }

    public function show($id)
    {
        $post=Post::where('slug',$id)->first();
        return response()->json($post);
    }
}
