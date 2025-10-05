<?php
// app/Models/ProductivityTag.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
    ];

    /**
     * Relación con time trackings
     */
    public function timeTrackings(): BelongsToMany
    {
        return $this->belongsToMany(TimeTracking::class, 'time_tracking_productivity_tags')
                    ->withTimestamps();
    }

    /**
     * Relación con usuario
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope para etiquetas del sistema
     */
    public function scopeSystem($query)
    {
        return $query->where('is_system', true);
    }

    /**
     * Scope por tipo
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope para etiquetas positivas
     */
    public function scopePositive($query)
    {
        return $query->where('impact_score', '>', 0);
    }

    /**
     * Scope para etiquetas negativas
     */
    public function scopeNegative($query)
    {
        return $query->where('impact_score', '<', 0);
    }
}