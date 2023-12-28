<?php

namespace App\Http\Controllers;
use Laravel\Forge\Forge;
use App\Models\WordpressAdmin;
use Illuminate\Http\Request;
use App\Library\RemoteSsh;
use App\Models\Server;
use App\Models\Site;
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

    function listAdmins($server, $site)
    {
        $forge = new Forge(config('forge.api_key'));
        // dd($request->input('command'));
        $sitedata = $forge->site($server, $site);

        $command = "cd /home/" . $sitedata->username . "/" . $sitedata->name . $sitedata->directory .  " && wp user list --role=administrator --json";
        $data = $forge->executeSiteCommand($server, $site, ['command' => $command]);
        $commandId = ($data->id);
        $result = $forge->getSiteCommand($server, $site, $commandId);

        while ($result[0]->status == 'running' || $result[0]->status == 'waiting'){
            $result = $forge->getSiteCommand($server, $site, $commandId);
            sleep(1);
        }   
        $data = json_decode($result[0]->output);
        foreach ($data as $adminData) {
            $username = $adminData->user_login;
            $wordpressUserId = $adminData->ID;
    
            // Use updateOrCreate to find or create a WordPress admin record based on the username
            WordpressAdmin::updateOrCreate(
                ['username' => $username, 'site_id' => $site],
                ['wordpress_user_id' => $wordpressUserId]
            );
        }
        return response()->json($data);
    }

    function loginUrl(Request $request, $server, $site){
        
        $site = Site::where('site_id', $site)->first();
        $ip = ($site->server->ip_address);
        $ssh = new RemoteSsh('root', storage_path('app') . '\ssh.key', $ip);
        if ($ssh->connect()) {
            $command = "cd /home/" . $site->username . "/" . $site->name . $site->directory .  " && sudo -u ". $site->username ." wp login as ". $request->input('user_select') ." --url-only";
        }
        $result = ($ssh->exec($command));
        $url = trim($result);
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            // $url is a valid URL
            $data = ['status' => 'success', 'redirect_url' => $url];
            return redirect( $url );
        } else {
            // $url is not a valid URL
            $data = ['status' => 'error', 'output' =>  $result];
            return response()->json($data);
        }
    }


}
