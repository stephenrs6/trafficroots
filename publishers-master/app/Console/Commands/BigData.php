<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\StatsController;
class BigData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stats:bigdata {date?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Compile daily aggregate tables';

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
        $date = strlen($this->argument('date')) ? $this->argument('date') : '';
        $thread = new StatsController();
        $thread->bigData($date);
        //$thread->reloadBigData();
    }
}
