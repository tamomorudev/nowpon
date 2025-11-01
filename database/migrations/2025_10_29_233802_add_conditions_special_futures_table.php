<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddConditionsSpecialFuturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('special_futures', function (Blueprint $table) {
            $table->string('outline', 255)->nullable()->after('name');
            $table->tinyInteger('sex')->default(0)->after('end_date');
            $table->datetime('coupon_date_start')->nullable()->after('sex');
            $table->datetime('coupon_date_end')->nullable()->after('coupon_date_start');
            $table->json('day_of_the_weeks')->nullable()->after('coupon_date_end');
            $table->integer('discount_rate')->default(0)->after('day_of_the_weeks');
            $table->json('genre')->nullable()->after('discount_rate');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('special_futures', function (Blueprint $table) {
            $table->dropColumn('outline');
            $table->dropColumn('sex');
            $table->dropColumn('coupon_date_start');
            $table->dropColumn('coupon_date_end');
            $table->dropColumn('day_of_the_weeks');
            $table->dropColumn('discount_rate');
            $table->dropColumn('genre');
        });
    }
}
