<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use App\Photo;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::blogOrdered()->paginate(Post::$posts_per_page);

        $referrer = request()->server('HTTP_REFERER');
        $url = request()->url();

        $initial_view = substr($referrer, 0, strlen($url)) !== $url;

        return view('index', compact('posts', 'initial_view'));
    }

    public function permalink(Post $post)
    {
        return redirect($post->url());
    }

    public function adminIndex()
    {
        $posts = Post::blogOrdered()->get();

        return view('post.adminIndex', compact('posts'));
    }

    public function create()
    {
        $post = new Post;

        $staged = Photo::staged()->blogOrdered()->get();

        return view('post.create', compact('post', 'staged'));
    }

    private function validatePost(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'date' => 'required|regex:/^\d{4}-\d{2}-\d{2}$/',
            'photos' => 'required|array',
            'photos.*' => 'integer',
        ]);
    }

    public function store(Request $request)
    {
        $this->validatePost($request);

        $post = new Post;

        $post->title = $request->title;
        $post->date = $request->date;

        $post->save();

        $post->attachPhotos($request->photos);

        return redirect()->route('posts');
    }

    public function edit(Post $post)
    {
        $staged = Photo::staged()->blogOrdered()->get();

        return view('post.edit', compact('post', 'staged'));
    }

    public function update(Post $post, Request $request)
    {
        $this->validatePost($request);

        $post->update($request->only('title', 'date'));

        $post->detachPhotos();

        $post->attachPhotos($request->photos);

        return redirect()->route('posts');
    }

    public function delete(Post $post)
    {
        $post->delete();

        return redirect()->route('posts');
    }
}
