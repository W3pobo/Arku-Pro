<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductivityTag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'color',
        'impact_score',
        'description',
        'user_id',
        'is_system'
    ];

    protected $casts = [
        'is_system' => 'boolean',
        'impact_score' => 'integer'
    ];

    /**
     * Obtener etiquetas disponibles para el usuario
     */
    public static function getAvailableTags()
    {
        return self::where(function($query) {
            $query->where('user_id', auth()->id())
                  ->orWhere('is_system', true);
        })->get();
    }

    /**
     * Relación con el usuario
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con time trackings
     */
    public function timeTrackings()
    {
        return $this->belongsToMany(TimeTracking::class, 'time_tracking_productivity_tags')
                    ->withTimestamps();
    }
}