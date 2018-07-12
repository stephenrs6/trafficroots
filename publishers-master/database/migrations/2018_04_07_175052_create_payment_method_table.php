<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentMethodTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('payment_method', function (Blueprint $table) {
            $table->increments('id');
            $table->string('description');
            $table->timestamps();
       });	    //
       $sql = "INSERT INTO payment_method (description,created_at,updated_at) VALUES('Paper Check',NOW(),NOW()),('Wire/Bank (fee may apply)',NOW(),NOW()),('ACH',NOW(),NOW());";
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
