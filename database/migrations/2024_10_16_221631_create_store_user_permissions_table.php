<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreUserPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_user_permissions', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id')->default(0);
            $table->bigInteger('sotre_user_id')->default(0);
            $table->bigInteger('store_id')->default(0);
            $table->tinyinteger('permission_level')->default(0);
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
        Schema::dropIfExists('store_user_permissions');
    }
}
