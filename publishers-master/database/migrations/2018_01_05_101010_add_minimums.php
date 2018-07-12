<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMinimums extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('minimum_cpm', function (Blueprint $table) {
            $table->increments('id');
            $table->string('state_name',64);
            $table->decimal('cpc_desktop', 12, 4);
            $table->decimal('cpc_mobile', 12, 4);
            $table->decimal('cpm_desktop', 12, 4);
            $table->decimal('cpm_mobile', 12, 4);
            $table->timestamps();
        });
        $statement = "INSERT INTO minimum_cpm (state_name, cpc_desktop, cpc_mobile, cpm_desktop, cpm_mobile)
VALUES
('Alabama',1.14,1.09,2.30,3.88),
('Alaska',1.21,1.20,2.89,5.11),
('Arizona',1.21,1.20,2.89,5.12),
('Arkansas',1.15,1.11,3.89,4.84),
('California',1.52,1.48,5.19,5.33),
('Colorado',1.46,1.54,3.94,3.96),
('Connecticut',1.15,1.09,2.87,3.41),
('Delaware',1.14,1.07,2.47,4.52),
('District of Columbia',1.16,1.11,2.83,4.75),
('Florida',1.14,1.09,3.43,4.47),
('Georgia',1.13,1.10,2.61,4.21),
('Hawaii',1.15,1.10,3.48,4.51),
('Idaho',1.15,1.07,2.59,4.76),
('Illinois',1.38,1.29,3.31,4.18),
('Indiana',1.14,1.09,2.65,3.74),
('Iowa',1.14,1.07,2.40,3.33),
('Kansas',1.14,1.09,2.63,4.33),
('Kentucky',1.14,1.09,2.39,4.08),
('Louisiana',1.15,1.08,2.87,3.61),
('Maine',1.30,1.31,3.71,4.45),
('Maryland',1.16,1.10,2.38,4.19),
('Massachusetts',1.70,1.37,3.08,4.02),
('Michigan',1.24,1.34,3.08,4.10),
('Minnesota',1.15,1.09,2.38,3.98),
('Mississippi',1.14,1.07,2.52,4.43),
('Missouri',1.15,1.08,2.58,4.00),
('Montana',1.17,1.07,2.62,4.14),
('Nebraska',1.14,1.09,2.52,4.35),
('Nevada',1.32,1.19,5.83,5.42),
('New Hampshire',1.28,1.35,2.70,3.93),
('New Jersey',1.16,1.09,2.93,3.74),
('New Mexico',1.16,1.09,3.72,5.44),
('New York',1.25,1.30,3.08,4.06),
('North Carolina',1.15,1.09,2.32,4.12),
('North Dakota',1.15,1.07,3.16,3.23),
('Ohio',1.23,1.33,2.83,3.80),
('Oklahoma',1.14,1.09,2.62,4.35),
('Oregon',1.95,1.67,1.60,3.22),
('Pennsylvania',1.25,1.33,2.76,3.91),
('Rhode Island',1.15,1.09,2.77,4.59),
('South Carolina',1.15,1.08,2.16,4.17),
('South Dakota',1.15,1.06,2.59,5.06),
('Tennesse',1.14,1.09,2.54,4.14),
('Texas',1.14,1.08,3.04,4.31),
('Utah',1.14,1.08,3.27,4.34),
('Vermont',1.28,1.34,2.68,3.50),
('Virgina',1.14,1.09,3.01,3.83),
('Washington',1.28,1.31,4.63,4.65),
('West Virgina',1.16,1.07,2.40,4.05),
('Wisconsin',1.14,1.08,2.38,3.53),
('Wyoming',1.16,1.15,4.39,5.60);";
        DB::insert($statement);
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