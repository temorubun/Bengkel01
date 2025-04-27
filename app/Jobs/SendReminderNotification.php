<?php

namespace App\Jobs;

use App\Models\Reminder;
use App\Notifications\ReminderNotification;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendReminderNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $reminder;

    public function __construct(Reminder $reminder)
    {
        $this->reminder = $reminder;
    }

    public function handle()
    {
        $user = $this->reminder->user;
        
        // Send the notification
        $user->notify(new ReminderNotification($this->reminder));
        
        // Update the last triggered time
        $this->reminder->update([
            'last_triggered_at' => Carbon::now(),
            'is_completed' => $this->reminder->reminder_type === 'once'
        ]);
    }
} 