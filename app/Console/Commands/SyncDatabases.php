<?php

namespace App\Console\Commands;

use App\Http\Controllers\DatabaseController;
use Illuminate\Console\Command;

class SyncDatabases extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:db';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        (new DatabaseController())->syncDatabses();
    }
}
