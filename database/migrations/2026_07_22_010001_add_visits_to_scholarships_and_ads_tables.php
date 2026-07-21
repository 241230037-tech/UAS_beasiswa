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
        Schema::table('scholarships', function (Blueprint $table) {
            $table->integer('visits')->default(0)->after('updated_ago');
        });

        Schema::table('ad_banners', function (Blueprint $table) {
            $table->integer('visits')->default(0)->after('position');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('scholarships', function (Blueprint $table) {
            $table->dropColumn('visits');
        });

        Schema::table('ad_banners', function (Blueprint $table) {
            $table->dropColumn('visits');
        });
    }
};
