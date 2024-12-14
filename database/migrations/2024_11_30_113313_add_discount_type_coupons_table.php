<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDiscountTypeCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->tinyinteger('discount_type')->default(0)->after('discount_price');
            $table->integer('cource_time')->default(0)->after('detail');
            $table->dateTime('cource_start')->nullable()->after('cource_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->dropColumn('discount_type');
            $table->dropColumn('cource_time');
            $table->dropColumn('cource_start');
        });
    }
}
