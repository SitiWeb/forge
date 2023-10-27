<?php

namespace App\Http\Controllers;
use Laravel\Forge\Forge;
use Illuminate\Http\Request;
use App\Models\Server;
class DataController extends Controller
{
    public function deployment($server, $site, $deployment){
        $forge = new Forge(config('forge.api_key'));
        $site = $forge->site($server, $site);
        $deployment = $site->getDeploymentHistoryOutput($deployment);
        // Regular expression to match ANSI escape codes
        $pattern = '/\e\[\d+(;\d+)*m/';
        // Remove ANSI escape codes from the string
        $deploy_log = preg_replace($pattern, '', $deployment);
        $deploy_log = str_replace("\n", '<br>', $deploy_log);
        return response()->json($deploy_log);
    }

    public function console($server, $site){
        $forge = new Forge(config('forge.api_key'));
        $result =$forge->listCommandHistory($server, $site);
        return response()->json($result);
    }

    public function command($server, $site, $command){
        $forge = new Forge(config('forge.api_key'));
        $result = $forge->getSiteCommand($server, $site, $command);
        $pattern = '/\e\[\d+(;\d+)*m/';
        foreach($result as $index => $line){
            
       
            $deploy_log = preg_replace($pattern, '', $line->output);
            $deploy_log = str_replace("\n", '<br>', $deploy_log);
            $result[$index]->output = $deploy_log;
        }
        
        return response()->json($result);

    }

    public function sites(Server $server){
        if ($server){
            return response()->json($server->sites);
        }
         // Return a 404 response if the server is not found
         return response()->json(['message' => 'Server not found'], 404);
        
    }
}
