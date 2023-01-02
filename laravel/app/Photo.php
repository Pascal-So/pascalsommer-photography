<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Post;
use App\Comment;
use App\Tag;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

class Photo extends Model implements Sortable
{
    use SortableTrait;

    public $sortable = [
        'order_column_name' => 'weight',
        'sort_when_creating' => true,
    ];

    public function buildSortQuery()
    {
        // Overwrite the buildSortQuery method from the SortableTrait
        // in order to only apply the sorting within a post.
        return static::query()->where('post_id', $this->post_id);
    }

    protected $guarded = ['id'];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function scopePublished($query)
    {
        return $query->has('post');
    }

    public function scopeStaged($query)
    {
        return $query->doesntHave('post');
    }

    public function scopeBlogOrdered($query, bool $reverse = false)
    {
        $desc = $reverse ? 'asc' : 'desc';
        $asc = $reverse ? 'desc' : 'asc';

        return $query->orderByRaw('CASE WHEN post_id IS NULL THEN photos.weight + 1 ELSE 1 END DESC')
                    ->leftJoin('posts as p', 'photos.post_id', '=', 'p.id')
                    ->orderBy('p.date', $desc)
                    ->orderBy('p.id', $desc)
                    ->orderBy('weight', $asc)
                      // the explicit select statement is necessary, because otherwise, the
                      // photo id gets overwritten by the post id.
                    ->select(
                        'photos.id',
                        'photos.path',
                        'photos.description',
                        'photos.weight',
                        'photos.post_id',
                        'photos.created_at',
                        'photos.updated_at'
                    );
    }

    public function prevPhoto()
    {
        if (! $this->isPublic()) {
            return Photo::staged()
                        ->where('weight', '<', $this->weight)
                        ->orderBy('weight', 'desc')
                        ->first();
        }

        $before_in_post = $this->post->photos->where('weight', '<', $this->weight);

        if ($before_in_post->isEmpty()) {
            $prevPost = $this->post->nextPost();
            if (isset($prevPost)) {
                return $prevPost->photos->sortBy('weight')->last();
            }
            return null;
        }

        return $before_in_post->sortBy('weight')->last();
    }

    public function nextPhoto()
    {
        if (! $this->isPublic()) {
            return Photo::staged()
                        ->where('weight', '>', $this->weight)
                        ->orderBy('weight', 'asc')
                        ->first();
        }

        $after_in_post = $this->post->photos->where('weight', '>', $this->weight);

        if ($after_in_post->isEmpty()) {
            $nextPost = $this->post->prevPost();
            if (isset($nextPost)) {
                return $nextPost->photos->sortBy('weight')->first();
            }
            return null;
        }

        return $after_in_post->sortBy('weight')->first();
    }

    public function width():int
    {
        return getimagesize($this->imgPath())[0];
    }

    public function height():int
    {
        return getimagesize($this->imgPath())[1];
    }

    public function generateThumbnail()
    {
        $image = imagecreatefromjpeg($this->imgPath());
        if (!$image) {
            throw new \Exception('image ' . $this->imgPath() . ' not found.');
        }
        $scaled = imagescale($image, 300);

        $res = imagewebp($scaled, $this->thumbPath(), 40);
        if (!$res) {
            throw new \Exception('image ' . $this->thumbPath() . ' could not be saved.');
        }
    }

    public function titletext():string
    {
        return $this->description;
    }

    public function alttext():string
    {
        if ($this->description == "") {
            return "Photo by Pascal Sommer";
        }

        return $this->description;
    }

    public function getPaginationPage()
    {
        if (! $this->isPublic()) {
            return null;
        }

        return $this->post->getPaginationPage();
    }

    public function isPublic():bool
    {
        return $this->post !== null;
    }

    public function replaceLinks(string $text, string $link_options = ""):string
    {
        return preg_replace(
            "~[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]~",
            "<a {$link_options} href=\"\\0\">\\0</a>",
            $text
        );
    }

    public function replaceInternalLinks(string $text, string $link_options = ""):string
    {
        $patterns = [];
        $replacements = [];

        $photo_ids = [];
        preg_match_all("/#photo(\d+)#/", $text, $photo_ids, PREG_PATTERN_ORDER);
        foreach ($photo_ids[1] as $match_id => $id) {
            $patterns[] = '/' . $photo_ids[0][$match_id] . '/';

            $id = intval($id);
            $photo = self::find($id);

            if ($photo === null) {
                Log::error("Description of photo {$this->id} links to unknown photo ${id}.");
                $replacements[] = $photo_ids[0][$match_id];
            } elseif (! $photo->isPublic()) {
                Log::error("Description of photo {$this->id} links to unpublished photo ${id}.");
                $replacements[] = $photo_ids[0][$match_id];
            } else {
                $replacements[] = "<a ${link_options} href=\""
                                . $photo->url()
                                . "\">${id}</a>";
            }
        }

        $post_ids = [];
        preg_match_all('/#post(\d+)#/', $text, $post_ids, PREG_PATTERN_ORDER);
        foreach ($post_ids[1] as $match_id => $id) {
            $patterns[] = '/' . $post_ids[0][$match_id] . '/';

            $id = intval($id);
            $post = Post::find($id);

            if ($post === null) {
                Log::error("Description of photo {$this->id} links to unknown post ${id}.");
                $replacements[] = $post_ids[0][$match_id];
            } else {
                $replacements[] = "<a ${link_options} href=\"" . $post->permalink() . "\">\"{$post->title}\"</a>";
            }
        }

        $coords = [];
        // A coordinate is between 38 and 45 chars long after html entity substitution. Without
        // substitution it would be shorter. Also, this substitution introduces hashes so we need
        // the range here.
        preg_match_all('/#coords(.{38,45})#/u', $text, $coords, PREG_PATTERN_ORDER);
        foreach ($coords[1] as $match_id => $coord) {
            $patterns[] = '/' . $coords[0][$match_id] . '/';
            $link = 'https://www.google.com/maps/?q=' . $coord . '&=Search%20Google%20Maps';
            $replacements[] = "<a ${link_options} href=\"" . $link . "\">{$coord}</a>";
        }

        return preg_replace($patterns, $replacements, $text);
    }

    public function descriptionHTML():string
    {
        $with_br = nl2br(htmlspecialchars($this->description));
        return $this->replaceInternalLinks($this->replaceLinks($with_br, "target=blank"));
    }

    public function url():string
    {
        return route('viewPhoto', ['photo' => $this]);
    }

    public function imgPath():string
    {
        return config('constants.photos_path') . '/' . $this->path;
    }

    public function thumbPath():string
    {
        $jpg = config('constants.thumbs_path') . '/' . $this->path;
        $info = pathinfo($jpg);
        return $info['dirname'] . '/' . $info['filename']  . '.webp';
    }

    public function delete()
    {
        Storage::delete($this->imgPath());
        Storage::delete($this->thumbPath());

        $this->tags()->detach();

        $this->comments->each(function ($comment) {
            $comment->delete();
        });

        return parent::delete();
    }
}
