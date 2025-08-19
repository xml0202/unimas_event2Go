<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlacementHistory extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'event_id',
        'participant_id',
        'is_team',
        'placement_id',
        'points_awarded',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function placement()
    {
        return $this->belongsTo(PointSetup::class, 'placement_id');
    }
    
    public function participant()
    {
        return $this->belongsTo(User::class, 'participant_id');
    }
}
