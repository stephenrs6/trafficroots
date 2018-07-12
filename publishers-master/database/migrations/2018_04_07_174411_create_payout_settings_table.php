<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePayoutSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('payout_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
	    $table->integer('payment_method');
	    $table->integer('minimum_payout');
	    $table->integer('tax_status');
	    $table->string('tax_id');
	    $table->timestamps();
	    $table->unique('user_id');
        });	    //
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
