<?php

use Illuminate\Database\Seeder;

class PlatformsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('platforms')->insert(
            ['platform' => 'Desktop'],
            ['platform' => 'Mobile'],
            ['platform' => 'Tablet'],
            ['platform' => 'SmartTV'],
            ['platform' => 'Other']
        );        
    }
}
