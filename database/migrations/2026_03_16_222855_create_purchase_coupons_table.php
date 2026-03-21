<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_coupons', function (Blueprint $table) {
            $table->id();
            $table->string('purchase_code')->nullable();
            $table->bigInteger('coupon_id')->default(0);
            $table->string('coupon_code')->nullable();
            $table->integer('company_id')->default(0);
            $table->bigInteger('store_id')->default(0);
            $table->bigInteger('purchase_user_id')->default(0);
            $table->dateTime('purchase_date')->nullable();
            $table->integer('status')->default(0);
            $table->string('payment_id')->nullable();
            $table->integer('payment_amount')->default(0);
            $table->tinyinteger('cancel_flg')->default(0);
            $table->dateTime('created_at');
            $table->bigInteger('created_by')->default(0);
            $table->dateTime('updated_at');
            $table->bigInteger('updated_by')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_coupons');
    }
}
