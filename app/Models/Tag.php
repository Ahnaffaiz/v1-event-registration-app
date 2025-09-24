<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $guarded = [];

    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_tags');
    }

    public function eventTags()
    {
        return $this->hasMany(EventTag::class);
    }
}
