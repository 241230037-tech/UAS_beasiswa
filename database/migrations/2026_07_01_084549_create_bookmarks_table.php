<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menjalankan migrasi untuk membuat tabel bookmarks.
     */
    public function up(): void
    {
        // Membuat tabel 'bookmarks' untuk menyimpan daftar beasiswa favorit pengguna
        Schema::create('bookmarks', function (Blueprint $table) {
            $table->id();
            // Menghubungkan ke tabel 'users' (jika user dihapus, bookmark otomatis terhapus)
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            // Menghubungkan ke tabel 'scholarships' yang ID-nya berupa string
            $table->string('scholarship_id');
            $table->foreign('scholarship_id')->references('id')->on('scholarships')->cascadeOnDelete();
            $table->timestamps();
            
            // Mencegah duplikasi data bookmark yang sama untuk pengguna yang sama
            $table->unique(['user_id', 'scholarship_id']);
        });
    }

    /**
     * Membatalkan migrasi (menghapus tabel bookmarks).
     */
    public function down(): void
    {
        Schema::dropIfExists('bookmarks');
    }
};
