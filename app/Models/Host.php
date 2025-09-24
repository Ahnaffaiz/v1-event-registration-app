<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Host extends Model
{
    //
    protected $guarded = [];
    public function events()
    {
        return $this->hasMany(Event::class);
    }
}