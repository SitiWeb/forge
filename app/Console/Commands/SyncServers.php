<?php

namespace App\Console\Commands;
use \App\Http\Controllers\ServerController;
use Illuminate\Console\Command;
class SyncServers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:servers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize servers';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        (new ServerController())->syncServers();
    }
}
