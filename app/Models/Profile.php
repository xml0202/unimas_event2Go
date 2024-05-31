<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'username', 'universityId', 'fullname', 'email', 'altEmail', 'departmentCode',
        'departmentName', 'salutation', 'phoneNo', 'officeCode', 'officeName',
        'category', 'categoryCode', 'nationalId', 'staff', 'picture', 'extra', 'authorities',
    ];

    protected $casts = [
        'extra' => 'array',
        'authorities' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
