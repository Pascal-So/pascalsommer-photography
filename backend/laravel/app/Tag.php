<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Photo;

class Tag extends Model
{
    public function photos()
    {
        return $this->belongsToMany(Photo::class);
    }

    public function delete()
    {
        $this->photos()->detach();

        parent::delete();
    }
}
