<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityTicket extends Model
{
    //
    protected $guarded = [];

    public function eventTicket()
    {
        return $this->belongsTo(EventTicket::class);
    }
    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }
}
