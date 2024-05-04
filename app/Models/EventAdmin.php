<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class EventAdmin extends Model
{
    use HasFactory;
    
    protected $table = "events";
    protected $primaryKey = "id";
    
    protected $fillable = [
        'user_id',
        'admin_id',
        'title',
        'attachment',
        'introduction',
        'organized_by',
        'in_collaboration',
        'program_objective',
        'program_impact',
        'invitation',
        'start_datetime',
        'end_datetime',
        'category',
        'location',
        'max_user',
        'price',
        'earn_points',
        'approved',
        'approval',
        'status',
        'comment_enabled',
    ];
    

}
