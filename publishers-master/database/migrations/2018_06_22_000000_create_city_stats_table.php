<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCityStatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('city_stats', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('site_id');
            $table->integer('zone_id');
            $table->integer('city_code');
            $table->integer('state_code');
            $table->date('stat_date');
            $table->integer('impressions');
            $table->integer('clicks');
            $table->timestamps();
            $table->index('user_id');
            $table->index('site_id');
            $table->index('zone_id');
            $table->unique(array('zone_id','stat_date','city_code','state_code'));
        });        //
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
