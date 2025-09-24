<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventTicket extends Model
{
    //
    protected $guarded = [];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function activityTickets()
    {
        return $this->hasMany(ActivityTicket::class);
    }

    public function eventCheckin()
    {
        return $this->hasOne(EventCheckin::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
