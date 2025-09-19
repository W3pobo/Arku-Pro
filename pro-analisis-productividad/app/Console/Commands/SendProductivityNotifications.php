<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Notifications\ProductivityNotification;
use Carbon\Carbon;

class SendProductivityNotifications extends Command
{
    protected $signature = 'notifications:productivity';
    protected $description = 'Send automated productivity notifications';

    public function handle()
    {
        $users = User::where('notifications_enabled', true)->get();

        foreach ($users as $user) {
            $this->sendWeeklyReport($user);
            $this->sendProductivityTips($user);
            $this->checkGoals($user);
        }

        $this->info('Productivity notifications sent successfully.');
    }

    private function sendWeeklyReport(User $user)
    {
        if (Carbon::now()->isFriday()) { // Enviar solo los viernes
            $lastWeek = Carbon::now()->subWeek();
            
            $stats = [
                'total_hours' => $user->timeTrackings()
                    ->whereBetween('start_time', [$lastWeek->startOfWeek(), $lastWeek->endOfWeek()])
                    ->sum('duration_minutes') / 60,
                'avg_productivity' => $user->timeTrackings()
                    ->whereBetween('start_time', [$lastWeek->startOfWeek(), $lastWeek->endOfWeek()])
                    ->avg('productivity_score')
            ];

            $user->notify(new ProductivityNotification(
                'weekly_report',
                "Resumen semanal: {$stats['total_hours']} horas trabajadas con " .
                number_format($stats['avg_productivity'], 1) . "% de productividad promedio.",
                $stats
            ));
        }
    }

    private function sendProductivityTips(User $user)
    {
        $tips = [
            "¿Sabías que trabajar en bloques de 25-30 minutos con descansos cortos puede mejorar tu productividad?",
            "Intenta planificar tu día la noche anterior para maximizar tu productividad matutina.",
            "La técnica Pomodoro (25min trabajo + 5min descanso) es excelente para mantener el enfoque."
        ];

        if (rand(1, 10) <= 3) { // 30% de probabilidad de enviar tip
            $user->notify(new ProductivityNotification(
                'productivity_tip',
                $tips[array_rand($tips)]
            ));
        }
    }

    private function checkGoals(User $user)
    {
        // Verificar si el usuario alcanzó alguna meta
        $weeklyGoal = 40; // 40 horas semanales
        $weeklyHours = $user->timeTrackings()
            ->whereBetween('start_time', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->sum('duration_minutes') / 60;

        if ($weeklyHours >= $weeklyGoal) {
            $user->notify(new ProductivityNotification(
                'goal_achieved',
                "¡Felicidades! Has alcanzado tu meta semanal de {$weeklyGoal} horas.",
                ['hours_worked' => $weeklyHours, 'goal' => $weeklyGoal]
            ));
        }
    }
}