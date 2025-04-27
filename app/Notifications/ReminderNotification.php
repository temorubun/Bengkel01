<?php

namespace App\Notifications;

use App\Models\Reminder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $reminder;

    public function __construct(Reminder $reminder)
    {
        $this->reminder = $reminder;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('MyMoney - Pengingat: ' . $this->reminder->title)
            ->markdown('emails.reminder-notification', [
                'reminder' => $this->reminder,
                'user' => $notifiable
            ]);
    }

    public function toArray($notifiable)
    {
        return [
            'reminder_id' => $this->reminder->id,
            'title' => $this->reminder->title,
            'description' => $this->reminder->description,
        ];
    }
} 