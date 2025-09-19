<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;

class ProductivityNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $type;
    public $message;
    public $data;

    public function __construct($type, $message, $data = [])
    {
        $this->type = $type;
        $this->message = $message;
        $this->data = $data;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject($this->getSubject())
            ->line($this->message)
            ->action('Ver Detalles', url('/dashboard'))
            ->line('¡Gracias por usar nuestro sistema!');
    }

    public function toDatabase($notifiable)
    {
        return new DatabaseMessage([
            'type' => $this->type,
            'message' => $this->message,
            'data' => $this->data,
            'url' => $this->getUrl()
        ]);
    }

    private function getSubject()
    {
        $subjects = [
            'productivity_tip' => '💡 Consejo de Productividad',
            'weekly_report' => '📊 Resumen Semanal de Productividad',
            'goal_achieved' => '🎉 ¡Meta Alcanzada!',
            'low_productivity' => '⚠️ Alerta: Baja Productividad'
        ];

        return $subjects[$this->type] ?? 'Notificación del Sistema';
    }

    private function getUrl()
    {
        $urls = [
            'productivity_tip' => '/tips',
            'weekly_report' => '/reports',
            'goal_achieved' => '/goals',
            'low_productivity' => '/dashboard'
        ];

        return url($urls[$this->type] ?? '/dashboard');
    }
}