<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventApproval extends Model
{
    use HasFactory;
    
    protected $table = "events";
    protected $primaryKey = "id";
    
    protected $fillable = [
        'user_id',
        'title',
        'attachment',
        'description',
        'extra_info',
        'start_time',
        'end_time',
        'register_start_time',
        'register_end_time',
        'category',
        'location',
        'online',
        'maxUser',
        'paid',
        'price',
        'earn_points',
        'approved',
        'listed',
        'status',
    ];
}
