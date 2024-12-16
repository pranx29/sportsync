<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SessionUpdatedNotification extends Notification
{
    use Queueable;

    public $session;
    public $action;

    public function __construct($session, $action)
    {
        $this->session = $session;
        $this->action = $action;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $actionText = $this->action === 'canceled' ? 'canceled' : 'updated';
        return (new MailMessage)
            ->subject("Session {$this->action}: {$this->session->session_name}")
            ->line("The session '{$this->session->session_name}' scheduled on {$this->session->date_time} has been {$actionText}.")
            ->line("Details: {$this->session->description}")
            ->line("Participation_limit: {$this->session->participation_limit}")
            ->line("Equipment_provided: {$this->session->equipment_provided}")
            ->line("Location: {$this->session->location}")
            ->line("Duration: {$this->session->duration} minutes");
    }
}
