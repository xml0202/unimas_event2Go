<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Event extends Model
{
    use HasFactory;
    
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
    
    protected $casts = [
        'attachment' => 'array',
    ];
    
    
    // protected $casts = [
    //     'start_time' => 'datetime',
    // ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    public function attendee_users()
    {
        return $this->hasMany(Attendee::class);
    }
    
    public function officer_users()
    {
        return $this->belongsToMany(User::class, 'officers', 'event_id', 'user_id')
                    ->using(Officer::class)
                    ->withPivot(['accepted_at', 'status'])
                    ->withTimestamps();
    }
    
    public function vip_users()
    {
        return $this->belongsToMany(User::class, 'vips', 'event_id', 'user_id')
                    ->using(VIP::class)
                    ->withPivot(['accepted_at', 'status'])
                    ->withTimestamps();
    }

    public function categories(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
    
    public function attendees(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'attendees')
                    ->using(Attendee::class)
                    ->withPivot('required_transport', 'qrcode', 'attended', 
                        'approved', 'mobile_no', 'status', 'gender', 'addr_line_1', 
                        'addr_line_2', 'postcode', 'city', 'state', 'country') 
                    ->withTimestamps(); 
    }

    public function shortBody($words = 30): string
    {
        return Str::words(strip_tags($this->introduction), $words);
    }

    public function getFormattedDate()
    {
        return $this->start_date->format('F jS Y');
    }

    // public function getThumbnail()
    // {
    //     if (str_starts_with($this->attachment, 'http')) {
    //         return $this->attachment;
    //     }

    //     return '/storage/' . $this->attachment;
    // }
    
    public function users()
    {
        return $this->belongsToMany(User::class)
                    ->using(Officer::class)
                    ->withPivot(['accepted_at', 'status'])
                    ->withTimestamps();
    }
    
    public function getThumbnail()
    {
        $fileName = null;
        
        // Check if the attachment is an array and not empty
        if (is_array($this->attachment) && !empty($this->attachment)) {
            $fileName = $this->attachment[0]; // Choose the first image
        } elseif (is_string($this->attachment)) {
            $fileName = $this->attachment; // Use the attachment as is
        }
        
        if (!$fileName) {
            return null; // No valid file name found
        }
        
        if (str_starts_with($fileName, 'http')) {
            return $fileName; // Return the full URL
        }
    
        return '/storage/' . $fileName; // Return the URL with '/storage/' prepended
    }
    
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    
    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class);
    }
    
    public function likes()
    {
        return $this->hasMany(UpvoteDownvote::class);
    }

    public function humanReadTime(): Attribute
    {
        return new Attribute(
            get: function ($value, $attributes) {
                $words = Str::wordCount(strip_tags($attributes['introduction']));
                $minutes = ceil($words / 200);

                return $minutes . ' ' . str('min')->plural($minutes) . ', '
                    . $words . ' ' . str('word')->plural($words);
            }
        );
    }
}
