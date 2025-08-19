<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Rappasoft\LaravelAuthenticationLog\Traits\AuthenticationLoggable;
use Illuminate\Support\Facades\Log;
use DateTimeInterface;

class User extends Authenticatable implements MustVerifyEmail, FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, AuthenticationLoggable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'email_verified_at',
        'otp',
        'otp_expiry',
        'total_points',
        'token',
        'fcm_token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        // 'password' => 'hashed',
    ];
    
    public function profile()
    {
        return $this->hasOne(Profile::class, 'user_id');
    }
    
    public function userInfo()
    {
        return $this->hasOne(UserInfo::class);
    }
    
    public function events()
    {
        return $this->belongsToMany(Event::class, 'attendees', 'user_id', 'event_id');
    }
    
    public function invitations()
    {
        return $this->hasMany(Invitation::class);
    }
    
    public function officer_events()
    {
        return $this->belongsToMany(Event::class, 'officers', 'user_id', 'event_id')
                ->using(Officer::class)
                ->withPivot(['accepted_at', 'status'])
                ->withTimestamps();
    }
    
    public function vip_events()
    {
        return $this->belongsToMany(Event::class, 'vips', 'user_id', 'event_id')
                ->using(VIP::class)
                ->withPivot(['accepted_at', 'status'])
                ->withTimestamps();
    }
    
    public function attendees()
    {
        return $this->hasMany(Attendee::class, 'attendees', 'user_id', 'event_id');
    }
    
    public function attendee()
    {
        return $this->hasOne(Attendee::class, 'user_id', 'id');
    }

    public function canAccessFilament(): bool
    {
        return $this->hasRole(['Super Admin', 'Admin', 'Agency']);
        // return true;
    }
    
    public function points()
    {
        return $this->hasMany(Point::class);
    }
    
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
    
    public function updateTotalPoints()
    {
        $this->total_points = $this->points()->sum('points');
        $this->save();
    }
    
    public function agencies()
    {
        return $this->belongsToMany(Agency::class, 'agency_users', 'user_id', 'agency_id');
    }
    
    public function agencyEvents()
    {
        return $this->belongsToMany(Event::class, 'agency_users', 'user_id', 'agency_id')
                    ->withTimestamps();
    }
    
    public function adminEvents()
    {
        return $this->hasMany(Event::class, 'admin_id');
    }
    
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    // protected static function booted()
    // {
    //     static::saved(function ($user) {
    //         Log::info('User saved: ' . $user->id);
    //         $user->updateTotalPoints();
    //     });
    // }

    // public function updateTotalPoints()
    // {
    //     $this->total_points = $this->points()->sum('points');
    //     $this->save();
    // }
}
