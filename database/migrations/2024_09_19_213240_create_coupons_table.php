<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('coupon_name')->nullable();
            $table->string('coupon_code')->nullable();
            $table->integer('company_id')->default(0);
            $table->bigInteger('store_id')->default(0);
            $table->string('service_id')->nullable();
            $table->string('service_name')->nullable();
            $table->integer('price')->default(0);
            $table->integer('discount_price')->default(0);
            $table->text('detail')->nullable();
            $table->string('img_url')->nullable();
            $table->dateTime('expire_start_date')->nullable();
            $table->dateTime('expire_end_date')->nullable();
            $table->integer('sort_order')->default(0);
            $table->integer('status')->default(0);
            $table->tinyinteger('delete_flg')->default(0);
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
        Schema::dropIfExists('coupons');
    }
}
