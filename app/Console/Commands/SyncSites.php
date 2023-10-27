<?php

namespace App\Console\Commands;
use \App\Http\Controllers\SiteController;
use Illuminate\Console\Command;

class SyncSites extends Command
{
      /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:sites';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize sites';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        (new SiteController())->syncSites();
    }
}
