<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $guarded = [];

    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_categories');
    }

    public function eventCategories()
    {
        return $this->hasMany(EventCategory::class);
    }
}
