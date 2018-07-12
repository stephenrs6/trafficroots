<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSiteAnalysisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('site_analysis', function (Blueprint $table) {
            $table->string('site_handle',64);
            $table->date('stat_date');
            $table->string('geo',2);
            $table->string('state',2);
            $table->string('city',64);
            $table->integer('device');
            $table->integer('browser');
            $table->integer('os');
            $table->integer('impressions');
		   
		   
        });
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
