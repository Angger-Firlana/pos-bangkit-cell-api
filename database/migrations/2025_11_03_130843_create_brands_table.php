<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('negara_asal')->nullable();
            $table->timestamps();
        });

        Schema::table('devices', function (Blueprint $table) {
            $table->unsignedBigInteger('brand_id')->after('id');
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('cascade');
            $table->dropColumn('merek'); // hapus kolom merek lama
        });
    }

    public function down(): void
    {
        Schema::table('devices', function (Blueprint $table) {
            $table->string('merek')->nullable();
            $table->dropForeign(['brand_id']);
            $table->dropColumn('brand_id');
        });

        Schema::dropIfExists('brands');
    }
};
