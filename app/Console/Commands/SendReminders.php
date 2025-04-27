<?php

namespace App\Console\Commands;

use App\Models\Reminder;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendReminders extends Command
{
    protected $signature = 'reminders:send';
    protected $description = 'Send due reminders to users';

    public function handle()
    {
        $reminders = Reminder::where('is_active', true)
            ->where('is_completed', false)
            ->where(function ($query) {
                $query->whereNull('last_triggered_at')
                    ->orWhere(function ($q) {
                        $q->where('reminder_type', '!=', 'once')
                            ->where('reminder_date', '<=', now());
                    });
            })
            ->get();

        foreach ($reminders as $reminder) {
            if ($reminder->shouldTrigger()) {
                $user = User::find($reminder->user_id);
                
                if ($user && $user->email_notifications_enabled) {
                    Mail::send(
                        'emails.reminder',
                        ['user' => $user, 'reminder' => $reminder],
                        function ($message) use ($user, $reminder) {
                            $message->to($user->email)
                                ->subject('MyMoney Reminder: ' . $reminder->title);
                        }
                    );
                }

                if ($reminder->reminder_type === 'once') {
                    $reminder->update([
                        'is_completed' => true,
                        'last_triggered_at' => now()
                    ]);
                } else {
                    $reminder->update([
                        'last_triggered_at' => now(),
                        'reminder_date' => $reminder->getNextReminderDate()
                    ]);
                }
            }
        }

        $this->info('Reminders sent successfully!');
    }
} 