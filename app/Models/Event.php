<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $guarded = [];

    protected $casts = [
        'registration_start_date' => 'datetime',
        'registration_end_date' => 'datetime',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'require_approval' => 'boolean',
        'is_public' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function host()
    {
        return $this->belongsTo(Host::class);
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    public function tickets()
    {
        return $this->hasMany(EventTicket::class);
    }

    public function eventTickets()
    {
        return $this->hasMany(EventTicket::class);
    }

    public function eventCheckins()
    {
        return $this->hasManyThrough(EventCheckin::class, EventTicket::class);
    }

    public function eventTags()
    {
        return $this->hasMany(EventTag::class);
    }

    public function eventCategories()
    {
        return $this->hasMany(EventCategory::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'event_tags');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'event_categories');
    }
}
