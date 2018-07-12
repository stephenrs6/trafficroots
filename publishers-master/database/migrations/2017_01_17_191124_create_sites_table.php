<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sites', function (Blueprint $table) {
            $table->increments('id');
            $table->string('site_name', 64);
            $table->string('site_url', 128)->unique();
            $table->integer('site_category');
            $table->integer('user_id');
            
            //$table->timestamps();
        });
        //
		//		Schema::table('site_delete', function (Blueprint $table) {
//			$table->softDeletes();
//		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		Schema::create('sites', function (Blueprint $table) {
             $table->timestamps();

            
            //$table->timestamps();
        });
		
		
        //
    }
}
