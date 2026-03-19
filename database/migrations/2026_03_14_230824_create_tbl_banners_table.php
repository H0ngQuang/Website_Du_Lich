<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_banners', function (Blueprint $table) {
            $table->increments('bannerId');
            $table->string('title', 255);
            $table->string('subtitle', 255)->nullable();
            $table->string('image', 255);
            $table->string('link_url', 255)->nullable();
            $table->integer('order_index')->default(0);
            $table->tinyInteger('is_active')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_banners');
    }
};
