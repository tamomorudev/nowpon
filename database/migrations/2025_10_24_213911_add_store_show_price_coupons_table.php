<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStoreShowPriceCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->integer('store_pay_price')->default(0)->after('original_service_price');
            $table->integer('discount_rate')->default(0)->after('store_pay_price');
            $table->string('img_url_2', 255)->nullable()->after('img_url');
            $table->string('img_url_3', 255)->nullable()->after('img_url_2');
            $table->string('img_url_4', 255)->nullable()->after('img_url_3');
            $table->string('img_url_5', 255)->nullable()->after('img_url_4');
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
            $table->dropColumn('store_pay_price');
            $table->dropColumn('discount_rate');
            $table->dropColumn('img_url_2');
            $table->dropColumn('img_url_3');
            $table->dropColumn('img_url_4');
            $table->dropColumn('img_url_5');
        });
    }
}
