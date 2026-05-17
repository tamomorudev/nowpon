<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CopurchaseCouponsUppdateColumnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_coupons', function (Blueprint $table) {
            $table->dateTime('cancel_date')->nullable()->after('cancel_flg');
            $table->integer('cancel_reason')->nullable()->after('cancel_date');
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
            $table->dropColumn('cancel_date');
            $table->dropColumn('cancel_reason');
        });
    }
}
