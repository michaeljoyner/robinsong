<?php

namespace App\Http\Controllers;

use App\Blog\Post;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class BlogController extends Controller
{
    public function index()
    {
        $posts = Post::where('published', 1)->latest()->paginate(10);

        return view('front.pages.blogindex')->with(compact('posts'));
    }

    public function show($slug)
    {
        $post = Post::findBySlug($slug);

        return view('front.pages.blogshow')->with(compact('post'));
    }
}
