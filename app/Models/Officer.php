<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Officer extends Pivot
{
    use HasFactory;
    
    protected $table = 'officers';

    protected $fillable = ['user_id', 'event_id', 'accepted_at', 'status'];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Define the relationship with Event model
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
