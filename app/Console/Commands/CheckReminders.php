<?php

namespace App\Console\Commands;

use App\Jobs\SendReminderNotification;
use App\Models\Reminder;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckReminders extends Command
{
    protected $signature = 'reminders:check';
    protected $description = 'Check for due reminders and send notifications';

    public function handle()
    {
        $now = Carbon::now();
        
        // Get all active reminders that haven't been triggered yet or are due for next trigger
        $reminders = Reminder::where('is_active', true)
            ->where('is_completed', false)
            ->where(function($query) use ($now) {
                $query->whereNull('last_triggered_at')
                    ->orWhere(function($q) use ($now) {
                        $q->where('reminder_type', '!=', 'once')
                            ->where('reminder_date', '<=', $now);
                    });
            })
            ->get();

        foreach ($reminders as $reminder) {
            if ($reminder->shouldTrigger()) {
                SendReminderNotification::dispatch($reminder);
                $this->info("Sending notification for reminder: {$reminder->title}");
            }
        }

        $this->info('Reminder check completed.');
    }
} 