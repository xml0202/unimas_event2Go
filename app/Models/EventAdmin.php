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
    
    protected $casts = [
        'register_start_time' => 'datetime'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function categories(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
    
    public function attendees()
    {
        return $this->hasMany(Attendee::class, 'event_id')->with('user');
    }

    public function shortBody($words = 30): string
    {
        return Str::words(strip_tags($this->body), $words);
    }

    public function getFormattedDate()
    {
        return $this->register_start_time->format('F jS Y');
    }

    public function getThumbnail()
    {
        if (str_starts_with($this->attachment, 'http')) {
            return $this->attachment;
        }

        return '/storage/' . $this->attachment;
    }

    public function humanReadTime(): Attribute
    {
        return new Attribute(
            get: function ($value, $attributes) {
                $words = Str::wordCount(strip_tags($attributes['description']));
                $minutes = ceil($words / 200);

                return $minutes . ' ' . str('min')->plural($minutes) . ', '
                    . $words . ' ' . str('word')->plural($words);
            }
        );
    }
}
