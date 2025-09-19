<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TimeTracking;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'name', 'description', 'status',
        'total_hours', 'completed_tasks', 'total_tasks', 'productivity_score'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function timeTrackings()
    {
        return $this->hasMany(TimeTracking::class);
    }
}