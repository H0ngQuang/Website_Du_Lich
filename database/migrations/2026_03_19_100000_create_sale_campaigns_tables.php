<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tbl_sale_campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['flash_sale', 'seasonal', 'single_tour'])->default('flash_sale');
            $table->decimal('discount_percent', 5, 2)->default(0);
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->boolean('is_active')->default(true);
            $table->enum('apply_to', ['all', 'selected'])->default('all');
            $table->timestamps();
        });

        Schema::create('tbl_sale_campaign_tours', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('campaign_id');
            $table->unsignedBigInteger('tour_id');

            $table->foreign('campaign_id')->references('id')->on('tbl_sale_campaigns')->onDelete('cascade');
            $table->unique(['campaign_id', 'tour_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('tbl_sale_campaign_tours');
        Schema::dropIfExists('tbl_sale_campaigns');
    }
};
