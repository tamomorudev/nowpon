<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_services', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id')->default(0);
            $table->bigInteger('store_id')->default(0);
            $table->string('service_name')->nullable();
            $table->string('price')->nullable();
            $table->string('discount_price')->nullable();
            $table->string('detail')->nullable();
            $table->string('img_url')->nullable();
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
        Schema::dropIfExists('store_services');
    }
}
