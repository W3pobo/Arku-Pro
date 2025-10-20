<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'user_id',
        'title',
        'description',
        'status',
        'priority',
        'due_date',
        'estimated_minutes',
        'actual_minutes'
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    // Relaciones
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function timeTrackings()
    {
        return $this->hasMany(TimeTracking::class);
    }

    // Accesores
    public function getPriorityBadgeAttribute()
    {
        $badges = [
            1 => ['text' => 'Baja', 'color' => 'success'],
            2 => ['text' => 'Media', 'color' => 'warning'],
            3 => ['text' => 'Alta', 'color' => 'danger']
        ];
        
        return $badges[$this->priority] ?? $badges[1];
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => ['text' => 'Pendiente', 'color' => 'secondary'],
            'in_progress' => ['text' => 'En Progreso', 'color' => 'primary'],
            'completed' => ['text' => 'Completada', 'color' => 'success'],
            'cancelled' => ['text' => 'Cancelada', 'color' => 'danger']
        ];
        
        return $badges[$this->status] ?? $badges['pending'];
    }

    public function getIsOverdueAttribute()
    {
        return $this->due_date && $this->due_date->isPast() && !in_array($this->status, ['completed', 'cancelled']);
    }

    public function getProgressPercentageAttribute()
    {
        if ($this->estimated_minutes > 0) {
            return min(100, round(($this->actual_minutes / $this->estimated_minutes) * 100));
        }
        return 0;
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeDueSoon($query, $days = 3)
    {
        return $query->where('due_date', '<=', Carbon::now()->addDays($days))
                    ->whereNotIn('status', ['completed', 'cancelled']);
    }

    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', Carbon::today())
                    ->whereNotIn('status', ['completed', 'cancelled']);
    }
}