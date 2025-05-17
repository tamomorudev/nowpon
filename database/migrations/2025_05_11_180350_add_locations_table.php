<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->string('area')->nullable()->after('address3');
            $table->string('station', 50)->nullable()->after('area');
            $table->string('transportation', 50)->nullable()->after('station');
            $table->integer('time')->default(0)->after('transportation');
            $table->string('station_2', 50)->nullable()->after('time');
            $table->string('transportation_2', 50)->nullable()->after('station_2');
            $table->integer('time_2')->default(0)->after('transportation_2');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn('area');
            $table->dropColumn('station');
            $table->dropColumn('transportation');
            $table->dropColumn('time');
            $table->dropColumn('station_2');
            $table->dropColumn('transportation_2');
            $table->dropColumn('time_2');
        });
    }
}
