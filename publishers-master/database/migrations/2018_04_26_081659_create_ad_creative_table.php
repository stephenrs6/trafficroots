<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdCreativeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ad_creatives', function (Blueprint $table) {
	    $table->increments('id');
	    $table->integer('ad_id');
            $table->integer('weight');
            $table->integer('status')->default(1);
	    $table->integer('media_id');
	    $table->integer('link_id');
	    $table->integer('folder_id');
            $table->timestamps();
        });
	//
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
