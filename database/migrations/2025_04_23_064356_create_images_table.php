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
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->string('path'); // Menyimpan path atau nama file gambar
            $table->string('alt')->nullable(); // Teks alternatif untuk SEO dan aksesibilitas
            $table->string('caption')->nullable(); // Keterangan gambar (opsional)
            $table->integer('order')->nullable(); // Urutan gambar jika diperlukan
            $table->boolean('is_carousel')->default(false); // Flag untuk menandai gambar carousel
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('images');
    }
};
