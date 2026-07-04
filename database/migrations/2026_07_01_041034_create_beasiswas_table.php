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
        Schema::create('beasiswas', function (Blueprint $table) {
            $table->id();

            $table->string('nama');
            $table->string('penyelenggara');
            $table->text('deskripsi');
            $table->text('persyaratan')->nullable();

            $table->decimal('minimal_ipk', 3, 2)->nullable();
            $table->string('jurusan')->nullable();
            $table->integer('semester_min')->nullable();
            $table->string('domisili')->nullable();

            $table->date('deadline');

            $table->string('link_pendaftaran')->nullable();

            $table->enum('status', [
                'dibuka',
                'ditutup'
            ])->default('dibuka');

            $table->foreignId('admin_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('beasiswas');
    }
};