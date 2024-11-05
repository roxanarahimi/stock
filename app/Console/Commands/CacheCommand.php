<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CacheCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $datetime = new \DateTime( "now", new \DateTimeZone( "Asia/Tehran" ));

        $nowHour  = $datetime->format( 'G');
        if (((int)$nowHour > 8) && ((int)$nowHour < 19)){
            (new \App\Http\Controllers\InfoController)->cache();
        }
    }
}
