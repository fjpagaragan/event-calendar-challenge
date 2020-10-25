<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Day extends Model
{
    use HasFactory;

    protected $fillable = [
        "event_id",
        "name"
    ];

    public function event()
    {
        return $this->belongsTo('App\Model\Event');
    }
}
