<?php
namespace App\Library;
use phpseclib3\Net\SSH2;
use phpseclib3\Crypt\PublicKeyLoader;
class RemoteSsh{
    
    private $username;
    private $password;
    private $privateKey;
    private $server;
    private $ssh;
    private $port;

    public function __construct($username, $passwordOrPrivateKey, $server, $port = 22)
    {
        $this->username = $username;
        $this->server = $server;
        $this->port = $port;

        // Determine if the provided passwordOrPrivateKey is a password or a private key file path.
        if (file_exists($passwordOrPrivateKey)) {
            
            $this->privateKey = PublicKeyLoader::load(file_get_contents( $passwordOrPrivateKey));
            
        } else {
            $this->password = $passwordOrPrivateKey;
        }

        $this->connect();
    }

    public function connect()
    {
        $this->ssh = new SSH2($this->server, $this->port);

        if ($this->privateKey) {
            if (!$this->ssh->login($this->username, $this->privateKey)) {
                return false;
            }
        } else {
            if (!$this->ssh->login($this->username, $this->password)) {
                return false;
            }
        }

        return true;
    }

    public function closeConnection()
    {
        if ($this->ssh) {
            $this->ssh->disconnect();
        }
    }

    public function exec($command){
        return $this->ssh->exec($command);
    }

   

}