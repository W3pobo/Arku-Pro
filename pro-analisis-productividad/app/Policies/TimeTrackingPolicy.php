<?php

namespace App\Policies;

use App\Models\TimeTracking;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TimeTrackingPolicy
{
    public function view(User $user, TimeTracking $timeTracking)
    {
        return $user->id === $timeTracking->user_id;
    }

    public function update(User $user, TimeTracking $timeTracking)
    {
        return $user->id === $timeTracking->user_id;
    }

    public function delete(User $user, TimeTracking $timeTracking)
    {
        return $user->id === $timeTracking->user_id;
    }
}