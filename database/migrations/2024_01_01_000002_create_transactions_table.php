<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('description');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['Pemasukan', 'Pengeluaran']);
            $table->decimal('amount', 15, 2);
            $table->text('notes')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->boolean('is_recurring')->default(false);
            $table->date('next_transaction_date')->nullable();
            $table->enum('recurring_type', ['weekly', 'monthly'])->nullable();
            $table->boolean('reminder_sent')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}; 