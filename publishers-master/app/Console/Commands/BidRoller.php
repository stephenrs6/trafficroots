<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\CronController;
class BidRoller extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'adserver:bid_roller';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cron job to match up zones to bid campaigns.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $thread = new CronController();
        $thread->bidRoller();
    }    
}
