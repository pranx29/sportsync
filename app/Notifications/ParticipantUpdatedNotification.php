<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ParticipantUpdatedNotification extends Notification
{
    use Queueable;

    public $session;
    public $participant;
    public $action;

    public function __construct($session, $participant, $action)
    {
        $this->session = $session;
        $this->participant = $participant;
        $this->action = $action;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $actionText = $this->action === 'joined' ? 'joined' : 'left';
        return (new MailMessage)
            ->subject("Participant {$actionText} the Session: {$this->session->session_name}")
            ->line("{$this->participant->first_name} {$this->participant->last_name} has {$actionText} the session '{$this->session->session_name}' scheduled on {$this->session->date_time}.");
            // ->line("Location: {$this->session->location}")
            // ->line("Duration: {$this->session->duration} minutes")
    }
}

