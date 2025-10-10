<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tag;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::get();

        foreach ($tags as $tag) {
            $tag->nr_published = $tag->photos()->published()->count();
            $tag->nr_staged = $tag->photos()->staged()->count();
            $tag->nr_total = $tag->photos()->count();
        }

        return view('tag.index', compact('tags'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:32|regex:/[a-z]+/',
        ]);

        $tag = new Tag;
        $tag->name = $request->name;
        $tag->save();

        return redirect()->route('tags');
    }

    public function delete(Tag $tag)
    {
        $tag->delete();

        return redirect()->route('tags');
    }

    public function updateAll(Request $request)
    {
        $request->validate([
            'tags' => 'required|array',
            'tags.*.name' => 'required|max:32|regex:/[a-z]+/',
        ]);

        foreach ($request->tags as $id => $tag) {
            Tag::where('id', $id)->update(['name' => $tag['name']]);
        }

        return redirect()->route('tags');
    }
}
