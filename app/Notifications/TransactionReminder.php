<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Transaction;

class TransactionReminder extends Notification implements ShouldQueue
{
    use Queueable;

    protected $transaction;

    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $formattedAmount = number_format($this->transaction->amount, 0, ',', '.');
        
        return (new MailMessage)
            ->subject('Pengingat Transaksi - MyMoney')
            ->greeting('Hai ' . $notifiable->name . ',')
            ->line('Anda memiliki transaksi yang akan datang:')
            ->line("Deskripsi: {$this->transaction->description}")
            ->line("Kategori: {$this->transaction->category->name}")
            ->line("Tipe: {$this->transaction->type}")
            ->line("Jumlah: Rp {$formattedAmount}")
            ->line("Tanggal: {$this->transaction->next_transaction_date->format('d/m/Y')}")
            ->action('Lihat Detail', url('/transactions'))
            ->line('Terima kasih telah menggunakan MyMoney!');
    }
} 