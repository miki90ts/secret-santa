<?php

namespace App\Notifications;

use App\Models\Event;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SecretSantaAssignmentNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public Event $event;
    public User $receiver;

    /**
     * Create a new notification instance.
     */
    public function __construct(Event $event, User $receiver)
    {
        $this->event = $event;
        $this->receiver = $receiver;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $assignmentUrl = route('assignments.my', $this->event->id);

        return (new MailMessage)
            ->subject('🎅 Vaša Secret Santa dodela za ' . $this->event->name)
            ->view('emails.secret-santa-assignment', [
                'giver' => $notifiable,
                'receiver' => $this->receiver,
                'event' => $this->event,
                'assignmentUrl' => $assignmentUrl,
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'event_id' => $this->event->id,
            'event_name' => $this->event->name,
            'receiver_id' => $this->receiver->id,
            'receiver_name' => $this->receiver->name,
            'message' => 'Vi darivate ' . $this->receiver->name . ' za ' . $this->event->name
        ];
    }
}
