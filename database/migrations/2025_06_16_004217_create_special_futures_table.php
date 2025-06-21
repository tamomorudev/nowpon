<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpecialFuturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('special_futures', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->text('detail')->nullable();
            $table->string('image')->nullable();
            $table->dateTime('start_date');
            $table->dateTime('end_date');
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
        Schema::dropIfExists('special_futures');
    }
}
