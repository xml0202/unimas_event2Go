<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_name',
        'status',
        'listed',
    ];

    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class);
    }

}
