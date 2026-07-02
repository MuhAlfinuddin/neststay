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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('homestay_id')->constrained('homestays')->onDelete('cascade');
            $table->string('room_number');
            $table->string('room_type'); // Standard, Deluxe, Suite, etc.
            $table->decimal('price_per_night', 15, 2);
            $table->string('status')->default('available'); // available, occupied, maintenance
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('guests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('homestay_id')->constrained('homestays')->onDelete('cascade');
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('identity_number')->nullable(); // KTP / Passport
            $table->timestamps();
        });

        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('homestay_id')->constrained('homestays')->onDelete('cascade');
            $table->foreignId('room_id')->constrained('rooms')->onDelete('cascade');
            $table->foreignId('guest_id')->constrained('guests')->onDelete('cascade');
            $table->date('check_in');
            $table->date('check_out');
            $table->decimal('total_price', 15, 2);
            $table->string('status')->default('pending'); // pending, confirmed, checked_in, checked_out, cancelled
            $table->timestamps();
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('homestay_id')->constrained('homestays')->onDelete('cascade');
            $table->foreignId('reservation_id')->constrained('reservations')->onDelete('cascade');
            $table->decimal('amount', 15, 2);
            $table->string('payment_method'); // cash, transfer, card
            $table->string('payment_status')->default('paid'); // paid, down_payment, unpaid
            $table->dateTime('payment_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
        Schema::dropIfExists('reservations');
        Schema::dropIfExists('guests');
        Schema::dropIfExists('rooms');
    }
};
