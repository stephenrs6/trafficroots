<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\EarningsController;

class CalculateEarnings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'revenues:calculate_earnings {date?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculates Publisher Earnings from stats Table';

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
	$date = strlen($this->argument('date')) ? $this->argument('date') : date('Y-m-d');
        $thread = new EarningsController($date);
        $thread->processEarnings(); 
    }
}
