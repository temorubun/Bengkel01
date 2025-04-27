<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'description',
        'category_id',
        'type',
        'amount',
        'notes',
        'user_id',
        'is_recurring',
        'next_transaction_date',
        'recurring_type',
        'reminder_sent'
    ];

    protected $casts = [
        'date' => 'date',
        'next_transaction_date' => 'date',
        'amount' => 'decimal:2',
        'is_recurring' => 'boolean',
        'reminder_sent' => 'boolean'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shouldSendReminder()
    {
        if (!$this->is_recurring || $this->reminder_sent || !$this->next_transaction_date) {
            return false;
        }

        $reminderDays = $this->user->reminder_days ?? 3;
        return $this->next_transaction_date->lte(Carbon::now()->addDays($reminderDays));
    }
} 