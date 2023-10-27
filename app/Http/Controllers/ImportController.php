<?php

namespace App\Http\Controllers;
use phpseclib3\Net\SFTP;
use App\Models\Directadmin;
use App\Models\Import;
use App\Models\Server;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Forge\Forge;
use PDO;

class importController extends Controller
{
    private $ssh_port = 2220;
    private $ssh_user;
    private $ssh_host;
    private $destination_path;
    private $source_path;
    private $destination_db_name;
    private $destination_db_user;
    private $destination_db_pass;

    public function index(){
        $imports = Import::all();
      
        return view('imports.index',compact('imports'));
    }
    public function create(){
        // $fields = $this->wordPressImport();
        $directadmins = Directadmin::all();
        $servers = Server::all();
        return view('imports.create', compact('directadmins','servers'));
    }

    public function show(Import $import){
        // $fields = $this->wordPressImport();
        
        return view('imports.show', compact('import'));
    }

    public function store(Request $request)
    {
        // Validate the incoming form data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'directadminserver' => 'required|exists:directadmins,id', // Assuming a 'directadminservers' table
            'directadminUser' => 'required',     // Assuming a 'directadminusers' table
            'server' => 'required|exists:servers,id',                       // Assuming a 'servers' table
            'site' => 'required|string|max:255|exists:sites,id',
        ]);

        
        $validatedData['action'] = 'wordpress';
        $validatedData['status'] = 'created';
        $validatedData['data']['server'] = $validatedData['server'];
        $validatedData['data']['directadminserver'] = $validatedData['directadminserver'];
        $validatedData['data']['directadminUser'] = $validatedData['directadminUser'];

        $validatedData['user_id'] = Auth::user()->id;
        $validatedData['site_id'] = $request->input('site');
        $validatedData['data'] = json_encode($validatedData['data']);
       // dd($validatedData);

        // Create a new Import record in the database
        $import = Import::create($validatedData);

        // Optionally, you can add more logic here, such as sending a notification or performing additional actions.

        // Redirect the user to a relevant page (e.g., show the imported record)
        return redirect()->route('imports.show', ['import' => $import->id])
            ->with('success', 'Import created successfully');
    }

    public function rsync(Import $import){
        $forge = new Forge(config('forge.api_key'));
        $server_id = $import->site->server->forge_id;
        $site_id = $import->site->site_id;
        $site = $forge->site( $server_id, $site_id);

        $import->data = json_decode($import->data);
        $import->data->server = Directadmin::find($import->data->server);
        $this->ssh_host = str_replace('https://', '', $import->data->server['host']);
        $this->ssh_user = $import->data->directadminUser;
        $this->source_path = "/home/{$this->ssh_user}/domains/{$import->site->name}/public_html";
        $this->destination_path = "/home/{$site->username}/public";
        $this->destination_db_name = '';
        $this->destination_db_pass = '';
        $this->destination_db_user = '';
        dd( $this->createMysqldump());

        
        $cmd = $this->downloadFiles();
        
        $forge->executeSiteCommand($server_id, $site->id, ['command'=>$cmd]);
        // Remove "https://" and "http://" from the URL, if present
       
        
    }

    public function downloadFiles(){
        $cmd = "rsync -avz -e \"ssh -p {$this->ssh_port}\"   {$this->ssh_host}:{$this->source_path}/ {$this->destination_path}/";
        return $cmd;
    }
    public function createMysqldump(){
        $cmd = "ssh -p {$this->ssh_port} {$this->ssh_user}@{$this->ssh_host} \"mysqldump -u 'cd {$this->source_path} && wp config get DB_USER' -p'cd {$this->source_path} && wp config get DB_PASS' 'cd domains/liefslabel.nl/public_html && wp config get DB_PASS' > {$this->source_path}/database.sql --no-tablespaces\"";
        return $cmd;
    }
    public function runImport(Import $import){
        switch($import->status){
            case 'created':
                $this->getCredentials($import);
        }
    }

    public function getCredentials($import){
        $cmd = "ssh -p {$this->ssh_port} {$this->ssh_user}@{$this->ssh_host} \"cd {$this->source_path} && wp config get DB_USER\"";

        return $cmd;
    }

    public function wordPressImport(){
        // return [
        //     'SSH_USER' => [
        //         'label' => 'SSH User',
        //         'required' => true,
        //         'type' => 'text',
        //         'place_holder' => 'user'
        //     ],
        //     'SSH_HOST' => [
        //         'label' => 'SSH Host',
        //         'required' => true,
        //         'type' => 'text',
        //         'place_holder' => '1.2.3.4'
        //     ],
        //     'SSH_PORT' => [
        //         'label' => 'SSH Port',
        //         'required' => true,
        //         'type' => 'text',
        //         'place_holder' => '1.2.3.4'
        //     ],
        //     'SOURCE_DB_USER' => [
        //         'label' => 'DB User',
        //         'required' => true,
        //         'type' => 'text',
        //         'place_holder' => 'db_user'
        //     ],
        //     'SOURCE_DB_PASS' => [
        //         'label' => 'DB Pass',
        //         'required' => true,
        //         'type' => 'text',
        //         'place_holder' => 'db_pass'
        //     ],
        //     'SOURCE_DB_PASS' => [
        //         'label' => 'DB Pass',
        //         'required' => true,
        //         'type' => 'text',
        //         'place_holder' => 'db_pass'
        //     ],

        // ];
    }
}

