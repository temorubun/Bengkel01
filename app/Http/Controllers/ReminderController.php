<?php

namespace App\Http\Controllers;

use App\Models\Reminder;
use App\Notifications\ReminderCompletedNotification;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReminderController extends Controller
{
    public function index()
    {
        $reminders = Reminder::where('user_id', auth()->id())
            ->where('is_active', true)
            ->orderBy('reminder_date')
            ->get();

        return view('reminders.index', compact('reminders'));
    }

    public function create()
    {
        return view('reminders.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'reminder_date' => 'required|date|after_or_equal:today',
            'reminder_type' => 'required|in:once,daily,weekly,monthly',
        ]);

        $reminder = Reminder::create([
            ...$validated,
            'user_id' => auth()->id(),
            'is_active' => true,
            'is_completed' => false,
        ]);

        return redirect()->route('reminders.index')
            ->with('success', 'Pengingat berhasil ditambahkan');
    }

    public function edit(Reminder $reminder)
    {
        if ($reminder->user_id !== auth()->id()) {
            abort(403);
        }

        return view('reminders.edit', compact('reminder'));
    }

    public function update(Request $request, Reminder $reminder)
    {
        if ($reminder->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'reminder_date' => 'required|date|after_or_equal:today',
            'reminder_type' => 'required|in:once,daily,weekly,monthly',
            'is_active' => 'boolean'
        ]);

        $reminder->update($validated);

        return redirect()->route('reminders.index')
            ->with('success', 'Pengingat berhasil diperbarui');
    }

    public function destroy(Reminder $reminder)
    {
        if ($reminder->user_id !== auth()->id()) {
            abort(403);
        }

        $reminder->delete();

        return redirect()->route('reminders.index')
            ->with('success', 'Pengingat berhasil dihapus');
    }

    public function complete(Reminder $reminder)
    {
        if ($reminder->user_id !== auth()->id()) {
            abort(403);
        }

        $user = auth()->user();

        if ($reminder->reminder_type === 'once') {
            $reminder->update([
                'is_completed' => true,
                'is_active' => false,
                'last_triggered_at' => now()
            ]);
        } else {
            // Untuk pengingat berulang, reset status dan perbarui tanggal berikutnya
            $nextDate = match($reminder->reminder_type) {
                'daily' => Carbon::now()->addDay(),
                'weekly' => Carbon::now()->addWeek(),
                'monthly' => Carbon::now()->addMonth(),
                default => null
            };

            $reminder->update([
                'is_completed' => false, // Reset status completed
                'is_active' => true,    // Pastikan tetap aktif
                'last_triggered_at' => now(),
                'reminder_date' => $nextDate
            ]);
        }

        // Kirim notifikasi email jika diaktifkan
        if ($user->email_notifications) {
            try {
                $user->notify(new ReminderCompletedNotification($reminder));
                $message = 'Pengingat berhasil ditandai selesai dan notifikasi telah dikirim ke email Anda.';
            } catch (\Exception $e) {
                \Log::error('Gagal mengirim email notifikasi pengingat: ' . $e->getMessage());
                return redirect()->route('reminders.index')
                    ->with('error', 'Pengingat berhasil ditandai selesai, tetapi gagal mengirim email notifikasi.');
            }
        } else {
            $message = 'Pengingat berhasil ditandai selesai.';
        }

        return redirect()->route('reminders.index')
            ->with('success', $message);
    }
} 