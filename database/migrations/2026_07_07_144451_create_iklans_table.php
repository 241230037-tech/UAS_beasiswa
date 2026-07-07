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
    Schema::create('iklans', function (Blueprint $table) {

        $table->id();

        $table->string('judul');

        $table->string('gambar');

        $table->text('deskripsi')->nullable();

        $table->string('link')->nullable();

        $table->boolean('status')->default(true);

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
        Schema::dropIfExists('iklans');
    }
};
