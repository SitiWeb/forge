<?php
namespace App\Library;
use phpseclib3\Net\SFTP;
use phpseclib3\Crypt\PublicKeyLoader;
class RemoteUpload{
    
    private $username;
    private $password;
    private $privateKey;
    private $server;
    private $sftp;
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
        $this->sftp = new SFTP($this->server, $this->port);

        if ($this->privateKey) {
            if (!$this->sftp->login($this->username, $this->privateKey)) {
                return false;
            }
        } else {
            if (!$this->sftp->login($this->username, $this->password)) {
                return false;
            }
        }

        return true;
    }

    public function closeConnection()
    {
        if ($this->sftp) {
            $this->sftp->disconnect();
        }
    }

    // public function uploadFile($localFilePath, $remoteFilePath)
    // {
    //     if (!$this->sftp) {
    //         if (!$this->connect()){
    //             return false; // SFTP connection not established
    //         }
    //     }

    //     // Use SFTP put method to upload the file
    //     if ($this->sftp->put($remoteFilePath, $localFilePath, SFTP::SOURCE_LOCAL_FILE)) {
    //         return true; // File uploaded successfully
    //     } else {
    //         $errorMessage = $this->sftp->getLastSFTPError(); // Get the error message
    //         $this->closeConnection();
    //         return $errorMessage; // Return the error message
    //     }
    // }

    public function uploadFiles(array $files)
    {
        if (!$this->sftp) {
            if (!$this->connect()) {
                return false; // SFTP connection not established
            }
        }

        foreach ($files as $localFilePath => $remoteFilePath) {
            // Use SFTP put method to upload each file
            if (!$this->sftp->put($remoteFilePath, $localFilePath, SFTP::SOURCE_LOCAL_FILE)) {
                $errorMessage = $this->sftp->getLastSFTPError(); // Get the error message
                $this->closeConnection();
                return $errorMessage; // Return the error message
            }
        }

        $this->closeConnection();
        return true; // All files uploaded successfully
    }
    public function listDirectory($remoteDirectory)
    {
        if (!$this->sftp) {
            if (!$this->connect()) {
                return false; // SFTP connection not established
            }
        }

        // Use SFTP ls method to list the contents of the remote directory
        $directoryContents = $this->sftp->nlist($remoteDirectory);
        

        if ($directoryContents === false) {
            return false; // Failed to list directory contents
        }
        // Custom callback function to filter out dots
        $filteredArray = array_filter($directoryContents, function ($element) {
            return $element !== '.' && $element !== '..';
        });

        // Convert the filtered array back to indexed keys (optional)
        $filteredArray = array_values($filteredArray);

        return $filteredArray; // Return an array of directory contents
    }

    public function uploadRootFile($path){
        $localFilePath = storage_path('app'). str_replace('/root','',$path);
        $array = [
            $localFilePath => $path,
        ];
        return $this->uploadFiles($array);

    }

    private function createPathIfNotExists( $path)
{
    // Split the path into its components
    $pathComponents = explode('/', $path);

    // Initialize the current path
    $currentPath = '/';

    foreach ($pathComponents as $component) {
        if (!empty($component)) {
            $currentPath .= $component . '/';
            if (!$this->sftp->is_dir($currentPath)) {
                // If the directory doesn't exist, create it
                if (!$this->sftp->mkdir($currentPath)) {
                    // Handle mkdir failure if necessary
                    return false;
                }
            }
        }
    }

    return true;
}

public function uploadFile($localFilePath, $remoteFilePath)
{
    // Ensure the directory structure exists on the remote server
    $directoryPath = dirname($remoteFilePath);
    
    if (!$this->createPathIfNotExists( $directoryPath)) {
        // Handle directory creation failure if necessary
        return false;
    }

    // Use SFTP put method to upload the file
    if ($this->sftp->put($remoteFilePath, $localFilePath, SFTP::SOURCE_LOCAL_FILE)) {
        return true; // File uploaded successfully
    } else {
        // Handle file upload failure if necessary
        return false;
    }
}

}