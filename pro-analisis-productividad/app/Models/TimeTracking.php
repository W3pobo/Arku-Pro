<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeTracking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'project_id',
        'activity_category_id',
        'start_time',
        'end_time',
        'duration_minutes',
        'description',
        'focus_level',
        'energy_level',
        'notes',
        'productivity_score',
        'activity_type',
        'is_productive'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'is_productive' => 'boolean'
    ];

    // Relaciones
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function activityCategory()
    {
        return $this->belongsTo(ActivityCategory::class);
    }

    public function productivityTags()
    {
        return $this->belongsToMany(ProductivityTag::class, 'time_tracking_productivity_tag')
                    ->withTimestamps();
    }

    // Accessors (Métodos que comienzan con get...Attribute)
    public function getProductivityScoreAttribute()
    {
        // Si no existe el campo, calcularlo basado en focus y energy
        if (isset($this->attributes['productivity_score'])) {
            return $this->attributes['productivity_score'];
        }
        
        return ($this->focus_level + $this->energy_level) / 2;
    }

    public function getIsProductiveAttribute()
    {
        // Si no existe el campo, determinar basado en la categoría
        if (isset($this->attributes['is_productive'])) {
            return $this->attributes['is_productive'];
        }

        return $this->activityCategory ? $this->activityCategory->is_productive : true;
    }

    /**
     * Accessor para obtener el color basado en el nivel de productividad
     */
    public function getProductivityColorAttribute()
    {
        $score = $this->productivity_score;

        if ($score >= 80) {
            return 'success'; // Verde
        } elseif ($score >= 60) {
            return 'warning'; // Amarillo/Naranja
        } else {
            return 'danger'; // Rojo
        }
    }

    /**
     * Accessor para obtener el color en formato HEX
     */
    public function getProductivityHexColorAttribute()
    {
        $score = $this->productivity_score;

        if ($score >= 80) {
            return '#10b981'; // Verde
        } elseif ($score >= 60) {
            return '#f59e0b'; // Amarillo/Naranja
        } else {
            return '#ef4444'; // Rojo
        }
    }

    /**
     * Accessor para obtener el texto descriptivo de productividad
     */
    public function getProductivityLevelAttribute()
    {
        $score = $this->productivity_score;

        if ($score >= 80) {
            return 'Alta';
        } elseif ($score >= 60) {
            return 'Media';
        } else {
            return 'Baja';
        }
    }

    /**
     * Accessor para formatear la duración en horas y minutos
     */
    public function getDurationFormattedAttribute()
    {
        $hours = floor($this->duration_minutes / 60);
        $minutes = $this->duration_minutes % 60;

        if ($hours > 0) {
            return "{$hours}h {$minutes}m";
        }

        return "{$minutes}m";
    }

    /**
     * Accessor para obtener el nombre de la categoría de forma segura
     */
    public function getActivityCategoryNameAttribute()
    {
        return $this->activityCategory ? $this->activityCategory->name : 'Sin categoría';
    }

    /**
     * Accessor para obtener el color de la categoría
     */
    public function getActivityCategoryColorAttribute()
    {
        return $this->activityCategory ? $this->activityCategory->color : '#6b7280';
    }
}