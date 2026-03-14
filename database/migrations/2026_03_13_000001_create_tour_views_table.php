<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tbl_tour_views', function (Blueprint $table) {
            $table->id('viewId');
            $table->unsignedBigInteger('userId');
            $table->unsignedBigInteger('tourId');
            $table->timestamp('viewed_at')->useCurrent();
            $table->tinyInteger('converted')->default(0); // 0: chưa mua, 1: đã mua
            $table->tinyInteger('reminder_sent')->default(0); // 0: chưa gửi, 1: đã gửi
        });
    }

    public function down()
    {
        Schema::dropIfExists('tbl_tour_views');
    }
};
