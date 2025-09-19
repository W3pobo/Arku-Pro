<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class TimeTracking extends Model
{
    use HasFactory;

    protected $table = 'time_trackings';

    protected $fillable = [
        'user_id', 'project_id', 'start_time', 'end_time', 
        'duration_minutes', 'description', 'activity_type', 'productivity_score'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    // Calcular duración automáticamente si no se proporciona
    public static function calculateDuration($startTime, $endTime)
    {
        $start = new \DateTime($startTime);
        $end = new \DateTime($endTime);
        $interval = $start->diff($end);
        
        return ($interval->h * 60) + $interval->i;
    }
}