<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpendTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spend', function (Blueprint $table) {
		$table->increments('id');
  		$table->integer('user_id');
		$table->date('spend_date');
		$table->decimal('spent', 12, 6);						                
		$table->timestamps();
		$table->unique(array('user_id','spend_date'), 'Ind_user_spend');
		$table->index('spend_date', 'Ind_spend_date');
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
