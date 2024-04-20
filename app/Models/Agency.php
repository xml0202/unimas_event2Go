<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agency extends Model
{
    use HasFactory;
    
    protected $table = "agencies";
    protected $primaryKey = "id";
    protected $fillable = ['admin_id', 'name', 'description', 'url', 'status'];
    
    public function AgencyUsers()
    {
        return $this->hasMany(AgencyUser::class);
    }
}
