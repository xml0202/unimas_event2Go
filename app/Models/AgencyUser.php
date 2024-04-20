<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgencyUser extends Model
{
    use HasFactory;
    
    protected $table = "agency_users";
    protected $primaryKey = "id";
    protected $fillable = ['admin_id', 'agency_id', 'user_id', 'status'];

    public function agency(){
    	return $this->hasOne(Agency::class, 'id', 'agency_id');
    }

    public function user(){
    	return $this->hasOne(User::class, 'id', 'user_id');
    }
    
    // public function user()
    // {
    //     return $this->belongsTo(User::class);
    // }
}
