<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendeeEventDay extends Model
{
    protected $fillable = ['attendee_id', 'event_date'];

    public function attendee()
    {
        return $this->belongsTo(Attendee::class);
    }
}


