<?php

namespace App\Http\Controllers;
use App\Library\Form;
use App\Library\ForgeSite;
use App\Models\Database;
use App\Models\DatabaseUser;
use App\Models\Site;
use App\Library\RemoteUpload;
use App\Models\Server;
use Illuminate\Http\Request;
use Laravel\Forge\Forge;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\Yaml\Yaml;

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
            ['value' =>'php', 'label' => 'General PHP/Laravel Application'],
            ['value' =>'html', 'label' => 'Static HTML site'],
            ['value' =>'symfony', 'label' => 'Symfony Application'],
            ['value' =>'symfony_dev', 'label' => 'Symfony (Dev) Application'],
            ['value' =>'symfony_four', 'label' => 'Symfony >4.0 Application'],
        ];

        $availablePHP = [
            ['value' => 'php72', 'label'  => '7.2'],
            ['value' => 'php73', 'label'  =>'7.3'],
            ['value' => 'php74', 'label' => '7.4'],
            ['value' => 'php80', 'label' => '8.0'],
            ['value' => 'php81', 'label' => '8.1'],
            ['value' => 'php82', 'label' => '8.2'],
            ['value' => 'php83', 'label' => '8.3'],
        ];

        $forge = new Forge(config('forge.api_key'));
        $templates = $forge->nginxTemplates($server);
        $form = new Form('Create sites', route('projects.store'), 'POST');
        $form->setSubmitText('Create site');
        $form->addField('domain', 'text', [
            'placeholder' => 'domain.com',
            'label' => 'Enter your domain',
            'width' => 4,
            'old' => false,
        ]);
        $form->addField('username', 'text', [
            'placeholder' => 'username',
            'label' => 'Enter your username',
            'width' => 4,
            'old' => false,
        ]);
        $form->addField('database', 'text', [
            'placeholder' => 'database',
            'label' => 'Enter your database',
            'width' => 4,
            'old' => false,
        ]);
        $form->addField('aliases', 'text', [
            'placeholder' => 'aliases',
            'label' => 'Enter your aliases',
            'width' => 6,
            'old' => false,
        ]);
        $form->addField('directory', 'text', [
            'placeholder' => 'directory',
            'label' => 'Enter your directory',
            'width' => 6,
            'old' => false,
            'value' => '/public',
        ]);
        $form->addField('php_version', 'select', [
            
            'label' => 'PHP Version',
            'width' => 4,
            'old' => false,
            'selected' => 'php82',
            'options' => $availablePHP,
        ]);
        $form->addField('project_type', 'select', [
            
            'label' => 'project_type',
            'width' => 4,
            'old' => false,
            'options' =>  $availableProjectTypes,
        ]);
        $form->addField('nginx_template', 'select', [
            
            'label' => 'nginx_template',
            'width' => 4,
            'old' => false,
            'default_option' => ['value' => 'default', 'label'  => 'Default'],
            'options' =>  $templates,
        ]);
        $form->addField('server', 'hidden', [
            'value' => $server,
        ]);
        
        $server = Server::where('forge_id', $server)->first();
  
        return view('sites.create', compact('availableProjectTypes', 'availablePHP', 'templates', 'server','form'));
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

        unset($formData['nginx_template']);
        $formData['aliases'] = [];
        $formData['isolated'] = true;
        $forge_server = $formData['server'];
        unset($formData['server']);
        $forge = new Forge(config('forge.api_key'));
        
        $result = $forge->createSite($forge_server, $formData,false);
        (new DatabaseController())->syncDatabses();
        $database = Database::where('name',  $formData['username'])->first();
        $data = [
            'name' => $request->input('database'),
            'user' => $request->input('username'),
            'password' => $randomPassword,
            'databases' => [$database->table_id],
        ];
        $db_user = ($forge->createDatabaseUser($forge_server, $data, false));
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
        $website = Site::where('site_id', $site)->first();
      
        $forge = new ForgeSite($server, $site);
        //$ = $forge->getSite();
        

        // $deploy_log = '';//$forge->getDeploymentLog();
        $dns = $this->dnsLookup($website->name);
        $website->dns = $dns;
        // $env = '';//($forge->siteEnvironmentFile($server, $site));
       
        // $deploy_history = [];// $website->getDeploymentHistory();
        $form = $this->loginAsForm($server,$site);
        // $server = Server::where('forge_id', $server)->first();
        return view('sites.show', compact('website', 'form'));
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

    function updateEnvFile(Request $request, $server, $site)
    {
        $forge = new Forge(config('forge.api_key'));
        $envContents = $forge->siteEnvironmentFile($server, $site);
        $site = Site::where('site_id',$site)->first();
        
        // Define the variables you want to update
        $variablesToUpdate = [
            'DB_HOST' => 'test',
            'DB_DATABASE' => 'test',
            'DB_USERNAME' => 'test',
            'DB_PASSWORD' => 'test',
        ];

        // Replace the values in the .env file
        foreach ($variablesToUpdate as $key => $value) {
            $envContents = preg_replace(
                "/^{$key}=.*/m",
                "{$key}={$value}",
                $envContents
            );
        }
        dd($envContents);
        // Save the updated contents back to the .env file
        file_put_contents($envFile, $envContents);

        // Clear the config cache to reflect the changes
        \Artisan::call('config:clear');
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
            
                   
                    $result = $site->update([
                        'name' => $website->name,
                        'server_id' => $apiServer->id,
                        'type' => $website->app,
                        'php_version' => $website->phpVersion,
                        'aliases' => json_encode($website->aliases),
                        'is_secured' => $website->isSecured,
                        'directory' => $website->directory,
                    ]);
                   
                } else {
                    Site::create([
                        'name' => $website->name,
                        'server_id' => $apiServer->id,
                        'user_id' => 1,
                        'site_id' => $website->id,
                        'type' => $website->app,
                        'php_version' => $website->phpVersion,
                        'aliases' => json_encode($website->aliases),
                        'is_secured' => $website->isSecured,
                        'directory' => $website->directory,
                        'username' => $website->username
                    ]);
                }
                // Add the site ID to the processed array
                $processedSiteIds[] = $website->id;
            }
        }

        Site::whereNotIn('site_id', $processedSiteIds)->delete();
    }

    function installSSO($server, $site){
        $forge = new Forge(config('forge.api_key'));
        // dd($request->input('command'));
        $sitedata = $forge->site($server, $site);
        $command = "cd /home/" . $sitedata->username . "/" . $sitedata->name . $sitedata->directory .  " && wp package uninstall aaemnnosttv/wp-cli-login-command && wp package install aaemnnosttv/wp-cli-login-command && wp login install --activate";
        $data = $forge->executeSiteCommand($server, $site, ['command' => $command]);
        $commandId = ($data->id);
        $result = $forge->getSiteCommand($server, $site, $commandId);
        while ($result[0]->status == 'running' || $result[0]->status == 'waiting'){
            sleep(1);
            $result = $forge->getSiteCommand($server, $site, $commandId);
        }   
    }

    public function loginAsForm($server, $site){
        $form = new Form('Login as', route('data.login', ['server'=> $server, 'site' => $site]), 'POST');
        $form->setSubmitText('Login as');
        $sitee = Site::where('site_id', $site)->first();
        $options = [];
        if($sitee){
            foreach($sitee->wordpressAdmins as $admin){
                $options[] =  ['value' => $admin->wordpress_user_id, 'label' => $admin->username];
            }
        }
        

        $form->addField('user_select', 'select', [
            'label' => 'Login as',
            'width' => 12,
            'old' => false,
            'selected' => 'php82',
            'options' => $options,
        ]);
        $form->addField('site', 'hidden', [
            'value' => $site,
        ]);
        
        $form->addField('server', 'hidden', [
            'value' => $server,
        ]);
        
        return $form;
    }

    public function syncWordpressAdmins($server, $site){
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
       
       
        return response()->json($data);
    }

    public function backupconfig(Request $request, $site){
           // Define the validation rules
            $rules = [
                'source_directories' => ['required', 'json'],
                'repositories' => ['required', 'json'],
                'site' => ['required', 'numeric'],
                'keep_daily' => ['required', 'numeric'],
                'keep_weekly' => ['required', 'numeric'],
                'keep_monthly' => ['required', 'numeric'],
                'site_config' => ['required', 'string'],
            ];
           
            // Create a validator instance
            $validator = Validator::make($request->all(), $rules);

            // Check if validation fails
            if ($validator->fails()) {
                // If validation fails, return back with the errors
                return redirect()->back()->withErrors($validator)->withInput();
            }
            $yamlData = Yaml::parseFile(storage_path('app') . '\config.yaml');
            $site = Site::where('site_id',$site)->first();
            // If validation passes, you can access the validated data like this:
            $validatedData = $validator->validated();
            $siteconfig = $validatedData['site_config']; 
            $validatedData['keep_monthly'] = intval($validatedData['keep_monthly']);
            $validatedData['keep_weekly'] = intval($validatedData['keep_weekly']);
            $validatedData['keep_daily'] = intval($validatedData['keep_daily']);
            unset($validatedData['site']);
            unset($validatedData['site_config']);
            $validatedData['encryption_passphrase'] = '123qwqw321';
            $archive_format = $yamlData['archive_name_format'];
            $archive_format = str_replace('{serverId}',$site->server_id,$archive_format);
            $validatedData['archive_name_format'] = str_replace('{siteId}',$site->site_id,$archive_format);
            $new_source=[];
            foreach($yamlData['source_directories'] as $source){
            
                $result = str_replace('{username}',$site->username,$source);
                $result = str_replace('{site}',$site->name,$result);
                $new_source[] = $result;
            }
            $validatedData['source_directories'] = $new_source;
            $validatedData['repositories'] = $yamlData['repositories'];
            $new_data = [];
            foreach($yamlData['mysql_databases'] as $database){
                    $result = str_replace('username',$site->username,$database['name']);
                    $new_data[] = ['name' => $result];   
            }
            $validatedData['mysql_databases'] = $new_data;
            $file = Yaml::dump($validatedData);
            if (!$file){
                return redirect()->back()->withErrors([])->withInput();
            }
            
            $tempFilePath = tempnam(sys_get_temp_dir(), 'yaml');
            file_put_contents($tempFilePath, $file);
            
            $remoteUpload = new RemoteUpload('root', storage_path('app') . '\ssh.key', $site->server->ip_address);
          
            $result = $remoteUpload->uploadFile($tempFilePath, $siteconfig );
 
            // Check if the upload was successful
            // Clean up the temporary file
            unlink($tempFilePath);

            // Close the SFTP connection
            $remoteUpload->closeConnection();
   
            if ($result) {
                // File uploaded successfully
                return redirect()->back()->with('success', 'File uploaded successfully');
            } else {
                // File upload failed
                return redirect()->back()->with('error', 'File upload failed');
            }
            
            
    }



    // You can also specify a custom length if needed, e.g., generateRandomPassword(16);

}
