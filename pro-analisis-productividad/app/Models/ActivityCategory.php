<?php
// app/Models/ActivityCategory.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ActivityCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'color',
        'icon',
        'description',
        'is_productive',
        'productivity_weight',
        'user_id',
        'is_system'
    ];

    protected $casts = [
        'is_productive' => 'boolean',
        'is_system' => 'boolean',
    ];

    /**
     * Relación con time trackings
     */
    public function timeTrackings(): HasMany
    {
        return $this->hasMany(TimeTracking::class);
    }

    /**
     * Relación con usuario
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope para categorías del sistema
     */
    public function scopeSystem($query)
    {
        return $query->where('is_system', true);
    }

    /**
     * Scope para categorías del usuario
     */
    public function scopeUserDefined($query, $userId = null)
    {
        $userId = $userId ?: auth()->id();
        return $query->where('user_id', $userId);
    }

    /**
     * Obtener categorías productivas
     */
    public function scopeProductive($query)
    {
        return $query->where('is_productive', true);
    }

    /**
     * Obtener categorías no productivas
     */
    public function scopeNonProductive($query)
    {
        return $query->where('is_productive', false);
    }
}