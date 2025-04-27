<?php

namespace App\Notifications;

use App\Models\Reminder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReminderCompletedNotification extends Notification
{
    use Queueable;

    protected $reminder;

    public function __construct(Reminder $reminder)
    {
        $this->reminder = $reminder;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Reminder Completed: ' . $this->reminder->title)
            ->markdown('emails.reminder-completed', [
                'reminder' => $this->reminder,
                'user' => $notifiable
            ]);
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Reminder Completed',
            'message' => 'Your reminder "' . $this->reminder->title . '" has been marked as completed.',
            'reminder_id' => $this->reminder->id,
            'type' => 'reminder_completed'
        ];
    }
} 