<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Bids extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bids', function (Blueprint $table) {
            $table->increments('id');
            $table->string('zone_handle', 64);
            $table->integer('location_type');
            $table->integer('status')->default(0);
            $table->integer('buyer_id');
            $table->string('country_id')->default('0');
            $table->string('state_id')->default('0');
            $table->string('city_id')->default('0');
            $table->string('category_id')->default('0');
            $table->string('device_id')->default('0');
            $table->string('os_id')->default('0');
            $table->string('browser_id')->default('0');
            $table->index('zone_handle','status');
            $table->index('buyer_id');
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
