<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Attendee extends Pivot
{
    use HasFactory;

    protected $table = "attendees";
    protected $primaryKey = 'id';
    public $incrementing = true;   
    public $timestamps = true;

    protected $fillable = [
        'user_id', 
        'event_id', 
        'required_transport', 
        'approved', 
        'qrcode', 
        'payment', 
        'attended', 
        'mobile_no', 
        'dob', 
        'email',
        'status', 
        'gender', 
        'addr_line_1', 
        'addr_line_2', 
        'postcode', 
        'city', 
        'state_id', 
        'state', 
        'country',
        'type'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function eventDays()
    {
        return $this->hasMany(AttendeeEventDay::class, 'attendee_id');
    }
}
