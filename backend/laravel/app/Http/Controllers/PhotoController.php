<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Photo;
use App\Tag;
use App\Rules\NoHTML;
use Illuminate\Support\Str;

class PhotoController extends Controller
{
    public function index()
    {
        $photos = Photo::latest()->get();

        return view('photo.index', compact('photos'));
    }

    public function search(String $terms = '')
    {
        $query = Photo::published()->blogOrdered();
        foreach (explode(' ', $terms) as $term) {
            $matcher = "%{$term}%";
            $query->where(function($query) use ($matcher) {
                $query->where('description', 'like', $matcher)
                    ->orWhere('p.title', 'like', $matcher);
            });
        }
        $count = $query->count();
        $photos = $query->paginate(2 * 3 * 4 * 5);
        return view('photo.search', compact('photos', 'count', 'terms'));
    }

    public function view(Photo $photo)
    {
        // not logged in users aren't allowed to see unpublished photos
        if (! $photo->isPublic() && ! \Auth::check()) {
            abort(404);
        }

        return view('photo.view', compact('photo'));
    }

    public function random()
    {
        $photo = Photo::published()->inRandomOrder()->first();

        // Use the following line to show the random photo while leaving
        // the url at /random. This has the advantage that the user can
        // simply refresh the page to see a new random photo. However the
        // disadvantage is that you can't use the browser's back button
        // to get to the last random photo.
        // return view('photo.view', compact('photo'));

        // Instead, we now redirect the user to the photo once we have
        // determined a random photo.
        return redirect()->route('viewPhoto', compact('photo'));
    }

    public function filtered(string $tags = '')
    {
        $tags_arr = collect(explode(',', $tags))
                        ->filter(function ($str) {
                            return $str != '';
                        });

        $tags = Tag::get();

        // This might get inefficient as the amount of tags filtered for grows, but that's ok,
        // because usually count($tags_arr) is just 1.
        $query = Photo::published();
        foreach ($tags_arr as $tag) {
            if ($tag[0] === '!') {
                $pure_tag = substr($tag, 1);
                $query->whereDoesntHave('tags', function ($q) use ($pure_tag) {
                    $q->where('name', $pure_tag);
                });
            } else {
                $query->whereHas('tags', function ($q) use ($tag) {
                    $q->where('name', $tag);
                });
            }
        }

        $photos = $query->blogOrdered()->paginate(15);

        return view('photo.filtered', compact('tags_arr', 'photos', 'tags'));
    }

    public function adminIndex()
    {
        $published_photos = request()->query('published-photos', false);

        $staged_photos = request()->query('staged-photos', true);

        $no_desc = request()->query('no-desc', false);

        $photos_query = Photo::query();

        if ($published_photos && !$staged_photos) {
            $photos_query->published();
        } elseif (!$published_photos && $staged_photos) {
            $photos_query->staged();
        } elseif (!$published_photos && !$staged_photos) {
            $photos_query->limit(0);
        }

        if ($no_desc) {
            $photos_query->where('description', '');
        }

        $photos = $photos_query->blogOrdered()->get();

        return view('photo.adminIndex', compact('photos', 'published_photos', 'staged_photos', 'no_desc'));
    }

    public function edit(Photo $photo)
    {
        $other_tags = Tag::whereNotIn('id', $photo->tags->pluck('id'))->get();

        $tags = Tag::get();

        return view('photo.edit', compact('photo', 'other_tags', 'tags'));
    }

    public function update(Photo $photo, Request $request)
    {
        $request->validate([
            'description' => ['max:10000', new NoHTML],
        ]);

        $photo->description = $request->description ?: '';

        $photo->save();

        return redirect()->route('photos');
    }

    public function delete(Photo $photo)
    {
        $public = $photo->isPublic();

        $post = $photo->post;

        $photo->delete();

        if ($public && $post->photos->isEmpty()) {
            $post->delete();
        }

        return redirect()->back();
    }

    public function upload(Request $request)
    {
        $request->validate([
            'photos' => 'required|array',
            'photos.*' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        foreach ($request->photos as $photo) {
            $generated_name = Str::random(10) . '-' . $photo->getClientOriginalName();

            $filename = $photo->storeAs(config('constants.photos_path'), $generated_name);

            $photo = Photo::create([
                'path' => pathinfo($filename)['basename'],
                'description' => '',
            ]);
            $photo->generateThumbnail();
        }

        return redirect()->route('photos');
    }

    public function addTag(Photo $photo, Tag $tag)
    {
        $photo->tags()->attach($tag);

        return redirect(route('editPhoto', compact('photo')) . '#photo');
    }

    public function removeTag(Photo $photo, Tag $tag)
    {
        $photo->tags()->detach($tag);

        return redirect(route('editPhoto', compact('photo')) . '#photo');
    }

    public function gallery()
    {
        $photos = Photo::published()->blogOrdered()->paginate(2*3*4*5);

        return view('photo.gallery', compact('photos'));
    }

    public function generateThumbnails()
    {
        Photo::chunk(200, function($photos) {
            foreach ($photos as $photo) {
                if (!file_exists($photo->thumbPath())) {
                    $photo->generateThumbnail();
                }
            }
        });
        return redirect()->route('home');
    }
}
