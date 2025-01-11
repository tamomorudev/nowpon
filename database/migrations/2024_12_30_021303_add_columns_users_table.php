<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nickname')->nullable()->after('name');
            $table->string('postal_code', 50)->nullable()->after('remember_token');
            $table->string('prefecture', 50)->nullable()->after('postal_code');
            $table->string('city', 50)->nullable()->after('prefecture');
            $table->tinyinteger('sex')->default(0)->after('phone_number');
            $table->integer('age')->default(0)->after('sex');
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
            $table->dropColumn('nickname');
            $table->dropColumn('postal_code');
            $table->dropColumn('prefecture');
            $table->dropColumn('city');
            $table->dropColumn('sex');
            $table->dropColumn('age');
        });
    }
}
