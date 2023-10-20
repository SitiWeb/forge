<?php

namespace App\Http\Controllers;
use App\Models\Server;
use Illuminate\Http\Request;
use Laravel\Forge\Forge;
use Illuminate\Support\Facades\Http;
class SiteController extends Controller
{
    // app/Http/Controllers/ProjectController.php

public function create(Server $server)
{

    $availableProjectTypes = [
        'php' => 'General PHP/Laravel Application',
        'html' => 'Static HTML site',
        'symfony' => 'Symfony Application',
        'symfony_dev' => 'Symfony (Dev) Application',
        'symfony_four' => 'Symfony >4.0 Application',
    ];

    $availablePHP = [
        'php72' => '7.2',
        'php73' => '7.3',
        'php74' => '7.4',
        'php80' => '8.0',
        'php81' => '8.1',
        'php82' => '8.2',
        'php83' => '8.3',
    ];
    $forge = new Forge(config('forge.api_key'));
    $templates = $forge->nginxTemplates($server->forge_id);

    return view('sites.create', compact('availableProjectTypes', 'availablePHP','templates', 'server'));
}


    public function store(Request $request)
    {
        $formData = $request->except('_token');
        $forge = new Forge(config('forge.api_key'));
    
        // Usage: Generate a random password with a default length of 12 characters
        $randomPassword = $this->generateRandomPassword();
        $data = [
            'name' => $request->input('database'),
            'username' => $request->input('username'),
            'password' => $randomPassword,
        ];


        // $result = $forge->createDatabase($formData['server'],$data);
       
        // // $database_id = $result->id;
        // $formData['database'] = $result->name;
        unset($formData['nginx_template']);
        $formData['aliases'] = [];
        $formData['isolated'] = true;
        $forge_server = $formData['server'];
        unset($formData['server']);
        $forge = new Forge(config('forge.api_key'));
        $result = $forge->createSite($forge_server,$formData);
       
        $server = Server::where('forge_id', $forge_server)->first();
        return redirect()->route('servers.show', ['id' => $server->id])->with('message', 'Site created successfully');
    }
    public function show(Server $server, $site){
        $forge = new Forge(config('forge.api_key'));
        
        $website = $forge->site($server->forge_id, $site);

        return view('sites.show', compact('website', 'server'));
    }

    function deleteSite(Request $request, $server, $site){
        $forge = new Forge(config('forge.api_key'));
        $forge->deleteSite($server, $site);
        $servers = Server::where('forge_id', $server)->first();
        return redirect()->route('servers.show', ['id' => $servers->id])->with('message', 'Site deleted successfully');

    }

    function deleteDatabase(Request $request, $server, $database){
        $forge = new Forge(config('forge.api_key'));
        $forge->deleteDatabase($server, $database);
        $servers = Server::where('forge_id', $server)->first();
        return redirect()->route('servers.show', ['id' => $servers->id])->with('message', 'Site deleted successfully');

    }

    function doDeploy(Request $request, $server, $site){
        $forge = new Forge(config('forge.api_key'));
        $content = $forge->siteDeploymentScript($server, '2137716');
        $forge->updateSiteDeploymentScript($server, $site, $content);
        $forge->deploySite($server, $site, $wait = false);
        $servers = Server::where('forge_id', $server)->first();
        return redirect()->route('servers.show', ['id' => $servers->id])->with('message', 'Deploy started successfully');
    }

    function installSsl(Request $request, $server, $site){
        $forge = new Forge(config('forge.api_key'));
        $site = $forge->site($server, $site);
        $data = [
            'domains' => [$site->name]
        ];
        $result = $forge->obtainLetsEncryptCertificate( $server, $site->id, $data, $wait = false);
        
        $servers = Server::where('forge_id', $server)->first();
        return redirect()->route('projects.show', ['server' => $servers->id, 'site'=> $site->id])->with('message', 'Install started successfully');
    }

    function installNew(Request $request, $server, $site){
        $forge = new Forge(config('forge.api_key'));
        $site = $forge->site($server, $site);
        $data = [
            'provider' => 'bitbucket',
            'repository' => 'nivlem-web-solutions/je-eigen-webshop',
            'branch' => 'master',
            'composer' => true,
        ];
        $result = $site->installGitRepository($data, $wait = true);
        
        $servers = Server::where('forge_id', $server)->first();
        return redirect()->route('servers.show', ['id' => $servers->id])->with('message', 'Install started successfully');
    }

    function generateRandomPassword($length = 12)
    {
        // Define the character pool from which the password will be generated
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        // Get the total number of characters in the pool
        $characterCount = strlen($characters);

        // Initialize an empty password
        $password = '';

        // Generate random characters to build the password
        for ($i = 0; $i < $length; $i++) {
            $randomIndex = rand(0, $characterCount - 1);
            $password .= $characters[$randomIndex];
        }

        return $password;
    }



// You can also specify a custom length if needed, e.g., generateRandomPassword(16);

}
