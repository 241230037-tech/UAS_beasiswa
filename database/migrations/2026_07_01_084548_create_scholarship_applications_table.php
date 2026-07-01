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
        Schema::create('scholarship_applications', function (Blueprint $table) {
            $table->id();
            $table->string('scholarship_id');
            $table->string('scholarship_title');
            $table->string('full_name');
            $table->string('nik');
            $table->string('email');
            $table->string('phone');
            $table->string('birth_date');
            $table->string('gender');
            $table->text('address');
            $table->string('applied_level');
            $table->string('university');
            $table->string('major');
            $table->string('gpa');
            $table->string('english_score')->nullable();
            $table->string('target_university')->nullable();
            $table->string('ktp_path');
            $table->string('ijazah_path');
            $table->string('transcript_path');
            $table->string('cv_path')->nullable();
            $table->text('motivation');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scholarship_applications');
    }
};
