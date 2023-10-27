<?php

namespace App\Http\Controllers;
use App\Models\Database;
use App\Models\DatabaseUser;
use App\Models\Site;
use App\Models\Server;
use Illuminate\Http\Request;
use Laravel\Forge\Forge;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage; // Import the Storage class
use Symfony\Component\Process\Process;

class SiteController extends Controller
{
    // app/Http/Controllers/ProjectController.php

    public function index()
    {
        $sites = Site::all();
        return view('sites.index', compact('sites'));
    }


    public function create($server)
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
        $templates = $forge->nginxTemplates($server);
        $server = Server::where('forge_id', $server)->first();
        return view('sites.create', compact('availableProjectTypes', 'availablePHP', 'templates', 'server'));
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
        $result = $forge->createSite($forge_server, $formData);
        (new DatabaseController())->syncDatabses();
        $database = Database::where('name',  $formData['username'])->first();
        $data = [
            'name' => $request->input('database'),
            'user' => $request->input('username'),
            'password' => $randomPassword,
            'databases' => [$database->table_id],
        ];
        $db_user = ($forge->createDatabaseUser($forge_server, $data));
        $user = DatabaseUser::create(
            [
                'table_user_id' => $db_user->id,
                'status' => $db_user->status,
                'name' => $db_user->name,
                'server_id' => $db_user->serverId,
                'createdAt' => $db_user->createdAt,
                'password' => $randomPassword
            ]
        );
        $user->databases()->attach($database);

        $database->table_user_id = $db_user->id;
        $database->save();

      
        return redirect()->route('servers.show', ['id' => $forge_server])->with('message', 'Site created successfully');
    }
    public function show($server, $site)
    {
        #
        $forge = new Forge(config('forge.api_key'));

        $website = $forge->site($server, $site);


        try {
            $deploy_log = $website->siteDeploymentLog();
            // Regular expression to match ANSI escape codes
            $pattern = '/\e\[\d+(;\d+)*m/';
            // Remove ANSI escape codes from the string
            $deploy_log = preg_replace($pattern, '', $deploy_log);
            $deploy_log = str_replace("\n", '<br>', $deploy_log);
        } catch (\Exception $e) {
            // Handle the exception or set a default value
            $deploy_log = false;
        }




        $dns = $this->dnsLookup($website->name);
        $website->dns = $dns;
        $deploy_history = $website->getDeploymentHistory();
        $server = Server::where('forge_id', $server)->first();
        return view('sites.show', compact('website', 'server', 'deploy_log', 'deploy_history'));
    }

    function deleteSite(Request $request, $server, $site)
    {
        $forge = new Forge(config('forge.api_key'));
        $forge->deleteSite($server, $site);
        $servers = Server::where('forge_id', $server)->first();
        return redirect()->route('servers.show', ['id' => $servers->forge_id])->with('message', 'Site deleted successfully');
    }

    function deleteDatabase(Request $request, $server, $database)
    {
        $forge = new Forge(config('forge.api_key'));
        $forge->deleteDatabase($server, $database);
        $servers = Server::where('forge_id', $server)->first();
        return redirect()->route('servers.show', ['id' => $servers->forge_id])->with('message', 'Site deleted successfully');
    }

    function doDeploy(Request $request, $server, $site)
    {
        $forge = new Forge(config('forge.api_key'));
        $content = file_get_contents(base_path('deploy.sh'));
        $forge->updateSiteDeploymentScript($server, $site, $content);
        $forge->deploySite($server, $site, $wait = false);

        return redirect()->route('servers.show', ['id' => $server])->with('message', 'Deploy started successfully');
    }

    function command(Request $request, $server, $site)
    {
        $forge = new Forge(config('forge.api_key'));
        // dd($request->input('command'));
        $data = $forge->executeSiteCommand($server, $site, ['command' => $request->input('command')]);
        return response()->json($data);
    }

    function installSsl(Request $request, $server, $site)
    {
        $forge = new Forge(config('forge.api_key'));
        $site = $forge->site($server, $site);
        $data = [
            'domains' => [$site->name]
        ];
        $result = $forge->obtainLetsEncryptCertificate($server, $site->id, $data, $wait = false);


        return redirect()->route('projects.show', ['server' => $server, 'site' => $site->id])->with('message', 'Install started successfully');
    }

    function installNew(Request $request, $server, $site)
    {
        $forge = new Forge(config('forge.api_key'));
        $site = $forge->site($server, $site);
        $data = [
            'provider' => 'bitbucket',
            'repository' => 'nivlem-web-solutions/je-eigen-webshop',
            'branch' => 'master',
            'composer' => true,
        ];
        $result = $site->installGitRepository($data, $wait = true);


        return redirect()->route('servers.show', ['id' => $server])->with('message', 'Install started successfully');
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

    public function dnsLookup($site)
    {
        $hostname = $site;
        $ipAddress = gethostbyname($hostname);

        if ($ipAddress != $hostname) {
            return $ipAddress;
        } else {
            return false;
        }
    }


    // Shit function doesnt work
    public function captureScreenshot($site = '')
    {
        // $url = 'https://nu.nl';
        // //$url = $site;
        // $outputPath = 'storage/app/public/screenshots/screenshot.png'; // Path relative to the public directory

        // // Use Puppeteer to capture a screenshot
        // $scriptPath = base_path('resources/node_scripts/screenshot.js');

        // // $command = 'C:\Program Files\nodejs\node.js '.$scriptPath.' '.$url.' '.$outputPath;
        // $command = 'C:\Program%20Files\nodejs\node.exe C:\xampp\htdocs\forge\resources/node_scripts/screenshot.js https://nu.nl storage/app/public/screenshots/screenshot.png';
        // $process = new Process(explode(' ', $command));
        // $process->run();
        // dd($process);
        // if (!$process->isSuccessful()) {
        //     return response('Error capturing screenshot', 500);
        // }
        // shell_exec('C:\"Program Files"\nodejs\node.exe C:\xampp\htdocs\forge\resources/node_scripts/screenshot.js https://nu.nl storage/app/public/screenshots/screenshot.png');
        // // Store the screenshot in the public disk
        // $publicDisk = Storage::disk('public');
        // $publicDisk->put($outputPath, file_get_contents(base_path($outputPath)));

        // // Return the public URL of the screenshot
        // $publicUrl = $publicDisk->url($outputPath);
        // return response()->json(['url' => $publicUrl]);
    }

    public function syncSites()
    {
        // Fetch data from the Forge API
        $forge = new Forge(config('forge.api_key'));

        $apiServers = $forge->servers();

        // Initialize an array to track processed site IDs
        $processedSiteIds = [];
        foreach ($apiServers as $apiServer) {

            $sites = $forge->sites($apiServer->id);
            foreach ($sites as $website) {
                $site = Site::where('site_id', $website->id)->first();
                if ($site) {
                    $site->update([
                        'name' => $website->name,
                        'server_id' => $apiServer->id,
                    ]);
                } else {
                    Site::create([
                        'name' => $website->name,
                        'server_id' => $apiServer->id,
                        'user_id' => 1,
                        'site_id' => $website->id
                    ]);
                }
                // Add the site ID to the processed array
                $processedSiteIds[] = $website->id;
            }
        }
        // Delete sites that were not processed (not in the $processedSiteIds array)
        // Delete sites that were not processed (not in the $processedSiteIds array)
        Site::whereNotIn('site_id', $processedSiteIds)->delete();
    }




    // You can also specify a custom length if needed, e.g., generateRandomPassword(16);

}
