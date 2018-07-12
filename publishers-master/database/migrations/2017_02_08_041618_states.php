<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class States extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('states', function (Blueprint $table) {
            $table->increments('id');
            $table->string('state_name', 64);
            $table->integer('country_id');
            $table->timestamps();
        });

        //let's do countries too
        Schema::create('countries', function (Blueprint $table) {
            $table->increments('id');
            $table->string('country_short', 2);
            $table->string('country_name', 64);
            $table->unique('country_short');
            $table->unique('country_name');
            $table->timestamps();
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
