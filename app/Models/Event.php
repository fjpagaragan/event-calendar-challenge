<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        "event",
        "start",
        "end"
    ];

    protected $dates = [
        "start",
        "end"
    ];

    public function days()
    {
        return $this->hasMany("App\Models\Day");
    }
}
