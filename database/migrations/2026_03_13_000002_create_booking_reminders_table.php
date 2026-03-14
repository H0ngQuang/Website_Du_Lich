<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tbl_booking_reminders', function (Blueprint $table) {
            $table->id('reminderId');
            $table->unsignedBigInteger('bookingId');
            $table->string('reminder_type', 50)->default('email'); // email, zalo
            $table->timestamp('sent_at')->useCurrent();
            $table->integer('reminder_count')->default(1);
        });
    }

    public function down()
    {
        Schema::dropIfExists('tbl_booking_reminders');
    }
};
