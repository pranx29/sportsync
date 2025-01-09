<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EventUpdatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $event;
    public $action;

    public function __construct($event, $action)
    {
        $this->event = $event;
        $this->action = $action;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $actionText = match ($this->action) {
            'created' => 'created',
            'updated' => 'updated',
            default => 'cancelled',
        };

        $mailMessage = (new MailMessage)
            ->subject("Event {$this->action}: {$this->event->eventName}")
            ->line("The event '{$this->event->eventName}' scheduled on {$this->event->eventDate} has been {$actionText}.")
            ->line("Sport type: {$this->event->sportType}")
            ->line("Details: {$this->event->eventDescription}")
            ->line("Registration type: {$this->event->registrationType}")
            ->line("Max participants: {$this->event->maxParticipants}")
            ->line("Location: {$this->event->location}")
            ->line("Duration: {$this->event->startTime} - {$this->event->endTime}")
            ->line("Registration deadline: {$this->event->registrationDeadline}");

        // Check if numberOfTeams is not empty or zero before adding to the email
        if (!empty($this->event->numberOfTeams) && $this->event->numberOfTeams != 0) {
            $mailMessage->line("Number of teams: {$this->event->numberOfTeams}");
        }

        // Check if teamAssignment is not empty before adding to the email
        if (!empty($this->event->teamAssignment)) {
            $mailMessage->line("Team assignment: {$this->event->teamAssignment}");
        }

        // Adding rules and venue regardless
        $mailMessage
            ->line("Rules: {$this->event->rulesDescription}")
            ->line("Venue: {$this->event->venue}");

        return $mailMessage;
    }
}
