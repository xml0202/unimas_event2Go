<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;
    
    protected $fillable = ['event_id', 'title', 'description', 'link'];
    
    public function event(){
        return $this->belongsTo(events::class);
    }
}
