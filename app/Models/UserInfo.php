<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInfo extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'mobile_no',
        'email',
        'addr_line_1',
        'addr_line_2',
        'postcode',
        'city',
        'state',
        'country',
        'gender',
    ];
    
    public function profile()
    {
        return $this->hasOne(Profile::class, 'user_id', 'user_id');
    }
}
