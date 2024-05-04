<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Attendee extends Pivot
{
    use HasFactory;
    
    protected $table = "attendees";
    
    protected $fillable = [
        'id', 
        'user_id', 
        'event_id', 
        'required_transport', 
        'approved', 
        'qrcode', 
        'payment', 
        'attended', 
        'mobile_no', 
        'dob', 
        'status', 
        'gender', 
        'addr_line_1', 
        'addr_line_2', 
        'postcode', 
        'city', 
        'state_id', 
        'state', 
        'country'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

}
