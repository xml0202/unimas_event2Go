<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class FeedbackList extends Model
{
    protected $table = "feedback_list";
    protected $primaryKey = "id";
    protected $fillable = ['name'];
    
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

   
}
