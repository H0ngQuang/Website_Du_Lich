<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tbl_customer_loyalty', function (Blueprint $table) {
            $table->id('loyaltyId');
            $table->unsignedBigInteger('userId')->unique();
            $table->decimal('total_spent', 15, 0)->default(0);
            $table->integer('total_bookings')->default(0);
            $table->string('loyalty_tier', 20)->default('bronze'); // bronze, silver, gold, platinum
            $table->integer('points')->default(0);
            $table->timestamp('last_booking_at')->nullable();
            $table->timestamp('last_promotion_sent_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tbl_customer_loyalty');
    }
};
