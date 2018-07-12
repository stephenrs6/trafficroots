<?php

use Illuminate\Database\Seeder;

class BrowsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('browsers')->insert(
            ['browser' => 'Internet Explorer'],
            ['browser' => 'Chrome'],
            ['browser' => 'Firefox'],
            ['browser' => 'Safari'],
            ['browser' => 'Other']
        );        
    }
}
