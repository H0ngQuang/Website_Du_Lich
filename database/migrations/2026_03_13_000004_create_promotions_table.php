<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tbl_promotions', function (Blueprint $table) {
            $table->id('promotionId');
            $table->string('code', 50)->unique();
            $table->string('description')->nullable();
            $table->decimal('discount_percent', 5, 2)->default(0);
            $table->decimal('discount_amount', 15, 0)->default(0);
            $table->date('valid_from');
            $table->date('valid_until');
            $table->integer('usage_limit')->default(0); // 0 = unlimited
            $table->integer('used_count')->default(0);
            $table->string('min_tier', 20)->default('bronze'); // tier tối thiểu để dùng
            $table->tinyInteger('is_active')->default(1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tbl_promotions');
    }
};
