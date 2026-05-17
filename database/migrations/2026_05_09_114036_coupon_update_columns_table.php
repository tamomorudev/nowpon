<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CouponUpdateColumnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->bigInteger('purchase_user_id')->default(0)->after('status');
            $table->dateTime('purchase_date')->nullable()->after('purchase_user_id');
            $table->dateTime('cancel_date')->nullable()->after('purchase_date');
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
            $table->dropColumn('purchase_user_id');
            $table->dropColumn('purchase_date');
            $table->dropColumn('cancel_date');
        });
    }
}
