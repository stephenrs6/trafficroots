<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMinimumPayoutTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('minimum_payout', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('amount');
            $table->timestamps();
       });	    //
      $sql = "INSERT INTO minimum_payout (amount,created_at,updated_at) VALUES(250,NOW(),NOW()),(500,NOW(),NOW()),(1000,NOW(),NOW()),(5000,NOW(),NOW());";
       DB::insert($sql);       
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
