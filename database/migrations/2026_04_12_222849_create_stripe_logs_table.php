<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStripeLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stripe_logs', function (Blueprint $table) {
            $table->id();
            $table->string('coupon_code');
            $table->bigInteger('user_id')->nullable();
            $table->string('payment_intent_id')->nullable();
            $table->string('payment_method_id')->nullable();
            $table->integer('amount')->nullable(); 
            $table->string('currency')->default('jpy');
            $table->string('status');
            $table->string('error_code')->nullable();
            $table->string('error_message')->nullable();
            $table->string('decline_code')->nullable();
            $table->json('stripe_response')->nullable();
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
        Schema::dropIfExists('stripe_logs');
    }
}
