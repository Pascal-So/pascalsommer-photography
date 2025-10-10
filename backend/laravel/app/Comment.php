<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Photo;

class Comment extends Model
{
    protected $guarded = ['id'];

    public function photo()
    {
        return $this->belongsTo(Photo::class);
    }

    public function commentHTML()
    {
        return nl2br(htmlspecialchars($this->comment));
    }
}
