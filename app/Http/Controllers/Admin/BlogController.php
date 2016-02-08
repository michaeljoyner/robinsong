<?php

namespace App\Http\Controllers\Admin;

use App\Blog\Post;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class BlogController extends Controller
{
    public function index()
    {
        $posts = Post::all();

        return view('admin.blog.index')->with(compact('posts'));
    }

    public function store(Request $request)
    {
        $this->validate($request, ['title' => 'required|max:255']);

        $post = Post::create(['title' => $request->title, 'user_id' => $request->user()->id]);

        return redirect('admin/blog/posts/'.$post->id.'/edit');
    }

    public function edit($postId)
    {
        $post = Post::findOrFail($postId);

        return view('admin.blog.edit')->with(compact('post'));
    }

    public function update(Request $request, $postId)
    {
        $this->validate($request, [
            'title' => 'required|max:255',
            'description' => 'required',
            'content' => 'required'
        ]);
        $post = Post::findOrFail($postId);
        $post->update($request->only(['title', 'description', 'content']));

        return redirect('admin/blog/posts');
    }

    public function storeImageUpload(Request $request, $postId)
    {
        $this->validate($request, ['file' => 'required|image']);

        $image = Post::findOrFail($postId)->addImage($request->file('file'));

        return response()->json(['location' => $image->getUrl('web')]);
    }

    public function setPublishedState(Request $request, $postId)
    {
        $this->validate($request, ['publish' => 'required|boolean']);

        $newState = Post::findOrFail($postId)->setPublished($request->publish);

        return response()->json(['new_state' => $newState]);
    }
}
