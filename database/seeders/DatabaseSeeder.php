<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\Reminder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // Buat user default
        $user = User::create([
            'name' => 'Kerja Bengkel 1',
            'email' => 'bengkel1@gmail.com',
            'password' => Hash::make('bengkel1'),
            'email_verified_at' => now(),
            'reminder_enabled' => true,
            'reminder_days' => 3,
            'email_notifications' => true,
        ]);

        // Buat kategori default untuk pemasukan
        $incomeCategories = [
            ['name' => 'Gaji', 'type' => 'Pemasukan', 'icon' => 'money-bill'],
            ['name' => 'Bonus', 'type' => 'Pemasukan', 'icon' => 'gift'],
            ['name' => 'Investasi', 'type' => 'Pemasukan', 'icon' => 'chart-line'],
            ['name' => 'Penjualan', 'type' => 'Pemasukan', 'icon' => 'store'],
            ['name' => 'Lainnya', 'type' => 'Pemasukan', 'icon' => 'plus-circle'],
        ];

        // Buat kategori default untuk pengeluaran
        $expenseCategories = [
            ['name' => 'Makanan & Minuman', 'type' => 'Pengeluaran', 'icon' => 'utensils'],
            ['name' => 'Transportasi', 'type' => 'Pengeluaran', 'icon' => 'car'],
            ['name' => 'Belanja', 'type' => 'Pengeluaran', 'icon' => 'shopping-cart'],
            ['name' => 'Hiburan', 'type' => 'Pengeluaran', 'icon' => 'film'],
            ['name' => 'Kesehatan', 'type' => 'Pengeluaran', 'icon' => 'hospital'],
            ['name' => 'Pendidikan', 'type' => 'Pengeluaran', 'icon' => 'graduation-cap'],
            ['name' => 'Tagihan', 'type' => 'Pengeluaran', 'icon' => 'file-invoice'],
            ['name' => 'Lainnya', 'type' => 'Pengeluaran', 'icon' => 'plus-circle'],
        ];

        // Simpan kategori dan dapatkan ID-nya
        $incomeCategoryIds = [];
        $expenseCategoryIds = [];

        foreach ($incomeCategories as $category) {
            $cat = Category::create([
                'name' => $category['name'],
                'type' => $category['type'],
                'icon' => $category['icon'],
                'user_id' => $user->id,
            ]);
            $incomeCategoryIds[] = $cat->id;
        }

        foreach ($expenseCategories as $category) {
            $cat = Category::create([
                'name' => $category['name'],
                'type' => $category['type'],
                'icon' => $category['icon'],
                'user_id' => $user->id,
            ]);
            $expenseCategoryIds[] = $cat->id;
        }

        // Buat 30 transaksi pemasukan dalam 3 bulan terakhir
        for ($i = 0; $i < 30; $i++) {
            $date = Carbon::now()->subDays(rand(1, 90));
            Transaction::create([
                'date' => $date,
                'description' => 'Pemasukan ' . ($i + 1),
                'category_id' => $incomeCategoryIds[array_rand($incomeCategoryIds)],
                'type' => 'Pemasukan',
                'amount' => rand(1000000, 5000000),
                'notes' => 'Catatan pemasukan ' . ($i + 1),
                'user_id' => $user->id,
            ]);
        }

        // Buat 27 transaksi pengeluaran dalam 3 bulan terakhir
        for ($i = 0; $i < 27; $i++) {
            $date = Carbon::now()->subDays(rand(1, 90));
            Transaction::create([
                'date' => $date,
                'description' => 'Pengeluaran ' . ($i + 1),
                'category_id' => $expenseCategoryIds[array_rand($expenseCategoryIds)],
                'type' => 'Pengeluaran',
                'amount' => rand(50000, 1000000),
                'notes' => 'Catatan pengeluaran ' . ($i + 1),
                'user_id' => $user->id,
            ]);
        }

        // Buat 25 pengingat dalam 3 bulan terakhir
        $reminderTypes = ['once', 'daily', 'weekly', 'monthly'];
        $reminderTitles = [
            'Bayar Listrik', 'Bayar Air', 'Bayar Internet', 'Bayar Cicilan',
            'Meeting Project', 'Deadline Tugas', 'Service Kendaraan', 'Kontrol Kesehatan',
            'Belanja Bulanan', 'Pembayaran Gaji', 'Tagihan Kartu Kredit'
        ];

        for ($i = 0; $i < 25; $i++) {
            $date = Carbon::now()->subDays(rand(1, 90));
            Reminder::create([
                'title' => $reminderTitles[array_rand($reminderTitles)] . ' ' . ($i + 1),
                'description' => 'Deskripsi pengingat ' . ($i + 1),
                'reminder_date' => $date,
                'reminder_type' => $reminderTypes[array_rand($reminderTypes)],
                'is_active' => true,
                'is_completed' => rand(0, 1),
                'user_id' => $user->id,
            ]);
        }

        // Buat notifikasi transaksi
        for ($i = 0; $i < 3; $i++) {
            $date = Carbon::now()->subDays(rand(1, 10));
            
            $user->notifications()->create([
                'id' => Str::uuid(),
                'type' => 'App\Notifications\TransactionReminder',
                'data' => [
                    'title' => 'Pengingat Transaksi Berulang',
                    'message' => 'Anda memiliki transaksi berulang yang akan jatuh tempo',
                    'transaction_id' => rand(1, 10),
                    'amount' => rand(100000, 1000000),
                    'due_date' => Carbon::now()->addDays(3)->format('Y-m-d')
                ],
                'created_at' => $date,
                'updated_at' => $date,
                'read_at' => rand(0, 1) ? $date->addHours(rand(1, 24)) : null
            ]);
        }

        // Buat notifikasi pengingat
        for ($i = 0; $i < 3; $i++) {
            $date = Carbon::now()->subDays(rand(1, 10));
            
            $user->notifications()->create([
                'id' => Str::uuid(),
                'type' => 'App\Notifications\ReminderNotification',
                'data' => [
                    'title' => 'Pengingat: ' . ['Bayar Tagihan', 'Meeting', 'Deadline Proyek'][rand(0, 2)],
                    'description' => 'Jangan lupa untuk menyelesaikan tugas ini',
                    'reminder_id' => rand(1, 10),
                    'reminder_date' => Carbon::now()->addDays(rand(1, 5))->format('Y-m-d')
                ],
                'created_at' => $date,
                'updated_at' => $date,
                'read_at' => rand(0, 1) ? $date->addHours(rand(1, 24)) : null
            ]);
        }

        // Buat contoh notifikasi umum
        for ($i = 0; $i < 15; $i++) {
            DB::table('notifications')->insert([
                'id' => Str::uuid(),
                'type' => ['App\Notifications\TransactionReminder', 'App\Notifications\ReminderNotification'][array_rand([0,1])],
                'notifiable_type' => User::class,
                'notifiable_id' => $user->id,
                'data' => json_encode([
                    'title' => 'Notifikasi ' . ($i + 1),
                    'message' => 'Ini adalah contoh pesan notifikasi ' . ($i + 1),
                    'created_at' => Carbon::now()->subDays(rand(1, 30))->format('Y-m-d H:i:s')
                ]),
                'read_at' => rand(0, 1) ? Carbon::now()->subDays(rand(1, 30)) : null,
                'created_at' => Carbon::now()->subDays(rand(1, 30)),
                'updated_at' => Carbon::now()->subDays(rand(1, 30))
            ]);
        }
    }
}
