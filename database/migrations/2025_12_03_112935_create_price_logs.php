<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('price_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_service_variant_id')->constrained('device_service_variants')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete(); // siapa yang ubah
            $table->decimal('old_harga_min', 12, 2)->nullable();
            $table->decimal('old_harga_max', 12, 2)->nullable();
            $table->decimal('new_harga_min', 12, 2)->nullable();
            $table->decimal('new_harga_max', 12, 2)->nullable();
            $table->string('tipe_part')->nullable(); // Original, OEM, KW, dll
            $table->timestamp('changed_at')->useCurrent();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('price_logs');
    }
};
