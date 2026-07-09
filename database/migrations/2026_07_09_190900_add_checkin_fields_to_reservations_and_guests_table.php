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
        Schema::table('reservations', function (Blueprint $table) {
            $table->string('checkin_token')->nullable()->after('status');
        });

        Schema::table('guests', function (Blueprint $table) {
            $table->string('ktp_photo_path')->nullable()->after('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn('checkin_token');
        });

        Schema::table('guests', function (Blueprint $table) {
            $table->dropColumn('ktp_photo_path');
        });
    }
};
