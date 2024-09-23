<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_users', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id')->default(0);
            $table->string('name');
            $table->string('password');
            $table->string('email')->nullable();
            $table->datetime('email_verified_at')->nullable();
            $table->rememberToken();
            $table->string('phone_number')->nullable();
            $table->tinyinteger('user_type')->default(0);
            $table->bigInteger('store_id')->default(0);
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
        Schema::dropIfExists('store_users');
    }
}
