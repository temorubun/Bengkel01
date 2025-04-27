<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class SettingController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        return view('settings.index', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = auth()->user();
        $data = ['name' => $request->name];

        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                Storage::disk('public')->delete($user->photo);
            }

            // Store new photo
            $photo = $request->file('photo');
            $filename = 'profile-photos/' . time() . '.' . $photo->getClientOriginalExtension();
            $photo->storeAs('public', $filename);
            $data['photo'] = $filename;
        }

        $user->update($data);

        return redirect()->route('settings.index')
            ->with('success', 'Profil berhasil diperbarui');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai']);
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect()->route('settings.index')
            ->with('success', 'Password berhasil diperbarui');
    }

    public function updateNotifications(Request $request)
    {
        $request->validate([
            'reminder_enabled' => 'boolean',
            'reminder_days' => 'required_if:reminder_enabled,1|integer|min:1|max:30',
            'email_notifications' => 'boolean',
        ]);

        $user = auth()->user();
        $user->update([
            'reminder_enabled' => $request->boolean('reminder_enabled'),
            'reminder_days' => $request->reminder_days,
            'email_notifications' => $request->boolean('email_notifications'),
        ]);

        return redirect()->route('settings.index')
            ->with('success', 'Pengaturan notifikasi berhasil diperbarui');
    }

    public function deleteAccount(Request $request)
    {
        $request->validate([
            'password' => 'required|current_password',
        ]);

        $user = auth()->user();
        
        // Delete user's photo if exists
        if ($user->photo && Storage::disk('public')->exists($user->photo)) {
            Storage::disk('public')->delete($user->photo);
        }

        // Begin transaction to ensure all data is deleted
        DB::beginTransaction();
        try {
            // Delete all related data
            $user->transactions()->delete();
            $user->categories()->delete();
            $user->reminders()->delete();
            $user->notifications()->delete();
            
            // Delete the user
            $user->delete();

            // Commit transaction
            DB::commit();

            // Logout
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->with('success', 'Akun Anda telah berhasil dihapus');
        } catch (\Exception $e) {
            // Rollback transaction if any error occurs
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan saat menghapus akun. Silakan coba lagi.');
        }
    }
} 