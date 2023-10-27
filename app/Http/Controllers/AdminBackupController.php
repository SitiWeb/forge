<?php

namespace App\Http\Controllers;

use App\Models\Adminbackup;
use Illuminate\Http\Request;
use phpseclib3\Net\SFTP;
use phpseclib3\Net\SSH2;
use phpseclib3\Crypt\PublicKeyLoader;
class AdminBackupController extends Controller


{

        // SSH Host

        private $ssh_host = 'roberto.robert.ooo';

        // SSH Port
    
        private $ssh_port = 22;
    
        // SSH Server Fingerprint
    
        private $ssh_server_fp = '';
    
        // SSH Username
    
        private $ssh_auth_user = 'forge';
    
        // SSH Public Key File
    
        private $ssh_auth_pub = '/home/username/.ssh/id_rsa.pub';
    
        // SSH Private Key File
    
        private $ssh_auth_priv = 'C:\Users\rober\Desktop\id_rsa';
    
        // SSH Private Key Passphrase (null == no passphrase)
    
        private $ssh_auth_pass;
    
        // SSH Connection
    
        private $connection;
    public function index(){
        $adminbackups = Adminbackup::all();
        return view('adminbackups.index', compact('adminbackups'));
    }

    public function runBorgCommand($command = 'list /home/forge/backup'){
        $ssh = $this->connect();
        $ssh->enablePTY();

        $ssh->exec('borg '.$command.' --json');
        // Wait for the prompt and send the passphrase
        $ssh->read('Enter passphrase for key /home/forge/backup: ');
        $ssh->write("123qwqw321\n");
        $output =  $ssh->read();

        $ssh->disconnect();

        return json_decode($output);

    }

    public function list(){
        return $this->runBorgCommand();
    }

    public function create(){
        return $this->runBorgCommand();
    }

    public function config(){
        return $this->runBorgCommand();
    }

    public function connect($service = 'ssh') {


        $key = PublicKeyLoader::load(file_get_contents($this->ssh_auth_priv));
        if ($service == 'ssh'){
            $ssh = new SSH2($this->ssh_host);
            if (!$ssh->login($this->ssh_auth_user, $key)) {
                throw new \Exception('Login failed');
            }
            return $ssh;
    
        }
        else{
            $sftp = new SFTP('localhost');
            if (!$sftp->login($this->ssh_auth_user, $key)) {
                throw new \Exception('Login failed');
            }
            return $sftp;

            
        }
        
        
    }
}
