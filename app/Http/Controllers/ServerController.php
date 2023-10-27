<?php

namespace App\Http\Controllers;
use App\Models\Server;
use Laravel\Forge\Forge;
use Illuminate\Http\Request;

class ServerController extends Controller
{

    public function index()
    {
        $servers = Server::all(); // Fetch all server records
        
        return view('servers.index', compact('servers'));
    }

    public function show($id)
    {
        $server = Server::where('forge_id',$id)->first(); // Fetch a single server record by ID
        // Fetch the list of websites associated with the server from the Forge API
        $forge = new Forge(config('forge.api_key'));
        
        $websites = $forge->sites($server->forge_id);
        $databases = $forge->databases($server->forge_id);
  
        return view('servers.show', compact('server', 'websites','databases'));
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
        }

        // Delete servers that exist in the database but not in the API
        $existingServerIds = Server::pluck('forge_id');
        $apiServerIds = collect($apiServers)->pluck('id');
        $serverIdsToDelete = $existingServerIds->diff($apiServerIds);

        Server::whereIn('forge_id', $serverIdsToDelete)->delete();

        return redirect()->route('servers.index')->with('success', 'Servers synchronized successfully.');
    }
}
