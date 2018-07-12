<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCountryStatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('country_stats', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('site_id');
            $table->integer('zone_id');
            $table->integer('country_code');
            $table->date('stat_date');
            $table->integer('impressions');
            $table->integer('clicks');
            $table->timestamps();
            $table->index('user_id');
            $table->index('site_id');
            $table->index('zone_id');
            $table->unique(array('zone_id','stat_date','country_code'));
            $table->index('user_id','stat_date');
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
