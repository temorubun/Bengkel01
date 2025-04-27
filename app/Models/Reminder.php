<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Reminder extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'reminder_date',
        'reminder_type',
        'is_active',
        'is_completed',
        'last_triggered_at',
        'user_id'
    ];

    protected $casts = [
        'reminder_date' => 'date',
        'is_active' => 'boolean',
        'is_completed' => 'boolean',
        'last_triggered_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shouldTrigger()
    {
        if (!$this->is_active || $this->is_completed) {
            return false;
        }

        $now = Carbon::now();
        $reminderDate = Carbon::parse($this->reminder_date);
        
        // Jika pengingat sudah pernah dipicu, gunakan last_triggered_at
        if ($this->last_triggered_at) {
            $lastTriggered = Carbon::parse($this->last_triggered_at);
            
            switch ($this->reminder_type) {
                case 'once':
                    return false; // Sudah dipicu sekali, tidak perlu lagi
                case 'daily':
                    return $now->diffInDays($lastTriggered) >= 1;
                case 'weekly':
                    return $now->diffInWeeks($lastTriggered) >= 1;
                case 'monthly':
                    return $now->diffInMonths($lastTriggered) >= 1;
            }
        }
        
        // Untuk pengingat yang belum pernah dipicu
        return $now->startOfDay()->gte($reminderDate->startOfDay());
    }

    public function getNextReminderDate()
    {
        if (!$this->last_triggered_at) {
            return $this->reminder_date;
        }

        $lastTriggered = Carbon::parse($this->last_triggered_at);
        
        switch ($this->reminder_type) {
            case 'once':
                return null;
            case 'daily':
                return $lastTriggered->addDay();
            case 'weekly':
                return $lastTriggered->addWeek();
            case 'monthly':
                return $lastTriggered->addMonth();
            default:
                return null;
        }
    }
} 