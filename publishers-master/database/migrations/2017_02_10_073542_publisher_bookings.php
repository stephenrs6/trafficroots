<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PublisherBookings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('publisher_bookings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('site_id');
            $table->integer('zone_id');
            $table->integer('pub_id');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('commission_tier');
            $table->integer('impressions_delivered');
            $table->decimal('cost',12,2);
            $table->decimal('revenue',12,2);
            $table->string('comments');
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
