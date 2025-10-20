<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPreference extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'preferred_categories', 'preferred_difficulty', 
        'preferred_time_slots', 'productivity_pattern'
    ];

    protected $casts = [
        'preferred_categories' => 'array',
        'preferred_time_slots' => 'array',
        'productivity_pattern' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}