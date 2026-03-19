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
        Schema::table('tbl_tours', function (Blueprint $table) {
            $table->tinyInteger('sale_percent')->default(0)->after('priceChild')->comment('Phần trăm giảm giá');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_tours', function (Blueprint $table) {
            $table->dropColumn('sale_percent');
        });
    }
};
