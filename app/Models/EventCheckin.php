<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventCheckin extends Model
{
    //
    protected $guarded = [];

    public function eventTicket()
    {
        return $this->belongsTo(EventTicket::class);
    }
}