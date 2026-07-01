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
        Schema::create('scholarships', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('title');
            $table->string('provider');
            $table->string('location');
            $table->string('flag')->nullable();
            $table->string('level');
            $table->string('amount');
            $table->string('deadline');
            $table->string('status');
            $table->string('image');
            $table->string('external_link');
            $table->string('updated_ago');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scholarships');
    }
};
