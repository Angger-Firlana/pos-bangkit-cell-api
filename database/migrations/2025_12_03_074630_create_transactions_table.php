<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_operator')->constrained('users')->cascadeOnDelete();
            $table->enum('status', ['pending', 'paid', 'cancelled'])->default('pending');
            $table->enum('metode_pembayaran', ['cash', 'qris', 'gopay', 'other'])->nullable();
            $table->decimal('jumlah_bayar', 12, 2)->nullable();
            $table->decimal('kembalian', 12, 2)->nullable();
            $table->string('qris_reference')->nullable();
            $table->decimal('total', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
