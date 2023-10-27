<?php

namespace App\Console\Commands;
use \App\Http\Controllers\DatabaseUserController;
use Illuminate\Console\Command;

class SyncDatabaseUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:dbusers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync Databaseusers';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        (new DatabaseUserController())->syncDatabseUsers();
    }
}
