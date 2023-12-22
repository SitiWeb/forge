<?php

namespace App\Http\Controllers;

use App\Models\Server;
use App\Models\Site;
use App\Models\Cron;
use Illuminate\Http\Request;

use App\Library\BackupClass;
use Laravel\Forge\Forge;
use Symfony\Component\Yaml\Yaml;

use App\Http\Controllers\CronController;


class ServerController extends Controller
{

    public function index(Request $request)
    {
        $searchQuery = $request->input('q'); // Rename $query to $searchQuery
    
        // Use a conditional query builder to filter servers based on the search query
        $servers = Server::when($searchQuery, function ($query) use ($searchQuery) { // Rename $query to $searchQuery
            return $query->where('name', 'LIKE', "%$searchQuery%");
        })->paginate(10);
    
        return view('servers.index', compact('servers', 'searchQuery'));
    }
    


    public function show($id)
    {
        $server = Server::where('forge_id', $id)->first(); // Fetch a single server record by ID
        // Fetch the list of websites associated with the server from the Forge API
        $forge = new Forge(config('forge.api_key'));


        $websites = $forge->sites($server->forge_id);
        $databases = $forge->databases($server->forge_id);
        $jobs = Cron::where('server_id', $server->forge_id)->get();

        return view('servers.show', compact('server', 'websites', 'databases', 'jobs'));
    }
    public function syncServers()
    {
        // Fetch data from the Forge API
        $forge = new Forge(config('forge.api_key'));

        $apiServers = $forge->servers();

        foreach ($apiServers as $apiServer) {

            // Check if the server already exists in the database
            $server = Server::where('forge_id', $apiServer->id)->first();

            if ($server) {
                // Server exists, update its attributes
                $server->update([
                    'name' => $apiServer->name,
                    'size' => $apiServer->size,
                    'region' => $apiServer->region,
                    'ip_address' => $apiServer->ipAddress,
                    'private_ip_address' => $apiServer->privateIpAddress,
                    'php_version' => $apiServer->phpVersion,
                    'server_created_at' => \Carbon\Carbon::parse($apiServer->createdAt)->toDateTimeString(),
                    // Add other attributes to update as needed
                ]);
            } else {
                // Server does not exist, create a new record
                Server::create([
                    'forge_id' => $apiServer->id,
                    'name' => $apiServer->name,
                    'size' => $apiServer->size,
                    'region' => $apiServer->region,
                    'ip_address' => $apiServer->ipAddress,
                    'private_ip_address' => $apiServer->privateIpAddress,
                    'php_version' => $apiServer->phpVersion,
                    'server_created_at' => \Carbon\Carbon::parse($apiServer->createdAt)->toDateTimeString(),
                    // Add other attributes to create as needed
                ]);
            }
            $cron = new CronController();
            $cron->syncCron($apiServer->id);
        }

        // Delete servers that exist in the database but not in the API
        $existingServerIds = Server::pluck('forge_id');
        $apiServerIds = collect($apiServers)->pluck('id');
        $serverIdsToDelete = $existingServerIds->diff($apiServerIds);

        Server::whereIn('forge_id', $serverIdsToDelete)->delete();

        return redirect()->route('servers.index')->with('success', 'Servers synchronized successfully.');
    }

    public function checkBackupConfig(Server $server)
    {
        $yamlData = Yaml::parseFile(storage_path('app') . '\config.yaml');

        $data = Site::where('type', 'WordPress')
            ->where('server_id', $server->forge_id)->get();
        $sites = [];
        foreach ($data as $site) {

            $new_source = [];
            foreach ($yamlData['source_directories'] as $source) {
                $result = str_replace('[username]', $site->username, $source);
                $result = str_replace('[site]', $site->name, $result);
                $new_source[] = $result;
            }
            $yamlData['source_directories'] = $new_source;
            $site->yamlconfig = $yamlData;
            $site->config_path = '/etc/borgmatic.d/' . $site->username . '.yaml';
            $sites[] = $site;
        }
        $backup = new BackupClass();
        $backups = [];
        $result = $backup->checkBackupScriptsCallback($server->ip_address);
        $borg = $backup->checkBackupBorgCallback($server->ip_address);
        $config = $backup->checkBackupConfigCallback($server);
        $crons = $backup->checkBackupCronCallback($server);
        if ($result) {
            return view('servers.checkconfig', compact('result', 'borg', 'config', 'sites', 'server', 'crons', 'backups'));
        }

        return view('servers.checkconfig');
    }

    public function AllBackups($server){
        $server = Server::where('forge_id', $server)->first();
        $backup = new BackupClass();
        $backups = $backup->checkBackupsCallback($server->ip_address);
        if ($backups) {
            return response()->json($backups);
        }
    }
    
}
