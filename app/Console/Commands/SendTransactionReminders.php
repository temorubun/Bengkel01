<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Transaction;
use App\Notifications\TransactionReminder;
use Carbon\Carbon;

class SendTransactionReminders extends Command
{
    protected $signature = 'app:send-transaction-reminders';
    protected $description = 'Send reminders for upcoming recurring transactions';

    public function handle()
    {
        $users = User::where('reminder_enabled', true)->get();
        
        foreach ($users as $user) {
            $transactions = Transaction::where('user_id', $user->id)
                ->where('is_recurring', true)
                ->where('reminder_sent', false)
                ->whereNotNull('next_transaction_date')
                ->get();

            foreach ($transactions as $transaction) {
                if ($transaction->shouldSendReminder()) {
                    // Kirim notifikasi
                    if ($user->email_notifications) {
                        $user->notify(new TransactionReminder($transaction));
                    }

                    // Update status pengingat
                    $transaction->reminder_sent = true;
                    
                    // Hitung tanggal transaksi berikutnya
                    $nextDate = $transaction->recurring_type === 'monthly' 
                        ? $transaction->next_transaction_date->addMonth() 
                        : $transaction->next_transaction_date->addWeek();
                    
                    // Reset reminder untuk transaksi berikutnya
                    $transaction->next_transaction_date = $nextDate;
                    $transaction->reminder_sent = false;
                    $transaction->save();

                    $this->info("Reminder sent for transaction: {$transaction->description}");
                }
            }
        }

        $this->info('Transaction reminders sent successfully!');
    }
} 