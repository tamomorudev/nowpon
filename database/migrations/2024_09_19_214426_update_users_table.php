<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone_number')->nullable()->after('remember_token');
            $table->integer('point')->default(0)->after('phone_number');
            $table->tinyinteger('user_type')->default(0)->after('point');
            $table->bigInteger('parent_user_id')->default(0)->after('user_type');
            $table->integer('sort_order')->default(0)->after('parent_user_id');
            $table->integer('status')->default(0)->after('sort_order');
            $table->tinyinteger('delete_flg')->default(0)->after('status');
            $table->bigInteger('created_by')->default(0)->after('created_at');
            $table->bigInteger('updated_by')->default(0)->after('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('phone_number');
            $table->dropColumn('user_type');
            $table->dropColumn('sort_order');
            $table->dropColumn('status');
            $table->dropColumn('delete_flg');
        });
    }
}
