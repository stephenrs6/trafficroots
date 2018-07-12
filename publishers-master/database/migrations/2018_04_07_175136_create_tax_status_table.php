<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaxStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('tax_status', function (Blueprint $table) {
            $table->increments('id');
            $table->string('description');
            $table->timestamps();
       });

       $sql = "INSERT INTO tax_status (description,created_at,updated_at) VALUES('Individual',NOW(),NOW()),('Company',NOW(),NOW());";
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
