<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendee extends Model
{
    use HasFactory;
    
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

    public function user(){
    	return $this->hasOne(User::class, 'id', 'user_id');
    }

	public function event(){
    	return $this->hasOne(Event::class, 'id', 'event_id');
    }

}
