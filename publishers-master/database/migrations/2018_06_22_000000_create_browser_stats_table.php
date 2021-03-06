<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBrowserStatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('browser_stats', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('site_id');
            $table->integer('zone_id');
            $table->integer('browser');
            $table->date('stat_date');
            $table->integer('impressions');
            $table->integer('clicks');
            $table->timestamps();
            $table->index('user_id');
            $table->index('site_id');
            $table->index('zone_id');
            $table->unique(array('zone_id','stat_date','browser'));
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
