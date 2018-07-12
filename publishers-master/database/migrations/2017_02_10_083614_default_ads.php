<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DefaultAds extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('default_ads', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('location_type');
            $table->integer('affiliate_id');
            $table->integer('status')->default(0);
            $table->string('country_id')->default('0');
            $table->string('state_id')->default('0');
            $table->string('city_id')->default('0');
            $table->string('category_id')->default('0');
            $table->string('device_id')->default('0');
            $table->string('os_id')->default('0');
            $table->string('browser_id')->default('0');
            $table->integer('html_id');
            $table->integer('link_id');
            $table->integer('media_id');
            $table->timestamps();
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
