<?php
namespace App\Library;
use Illuminate\Support\Facades\Cache;
use App\Models\Cron;
use App\Models\Site;
use App\Http\Controllers\CronController;
use Symfony\Component\Yaml\Yaml;
class BackupClass{

    public function checkBackupCronCallback($server)
    {
        $crons = Cron::where('server_id', $server->forge_id)->where('user', 'root')->where('command', '~/.local/bin/borgmatic')->get();
        if ($crons->isEmpty()) {
            $sitecontroller = new CronController();
            $sitecontroller->createJob($server->forge_id, '~/.local/bin/borgmatic', 'custom', 'root', true, '0', '3');

            $crons = Cron::where('server_id', $server->forge_id)->where('user', 'root')->where('command', '~/.local/bin/borgmatic')->get();
        }

        return $crons;
    }
    public function checkBackupsCallback($server)
    {

        $remoteUploader = new RemoteSsh('root', storage_path('app') . '\ssh.key', $server);

        if ($remoteUploader->connect()) {
            $result = $remoteUploader->exec('~/.local/bin/borgmatic list --json');

            if (str_contains($result, 'command not found')) {
                $response = ['status' => 'failed', 'result' => $result, 'type' => 'command_not_found'];
            } elseif (str_contains($result, 'No valid configuration files found')) {
                $response = ['status' => 'failed', 'result' => $result, 'type' => 'no_valid_config'];
            } elseif (json_decode($result)) {
                $backup = $this->getBackup(json_decode($result));
                $response = ['status' => 'success', 'result' => $backup, 'type' => 'success'];
            } else {
                $response = ['status' => 'failed', 'result' => $result, 'type' => 'no_idea'];
            }

            return $response;
        }

        return ['status' => 'failed', 'message' => 'no connection', 'type' => 'no_connection'];
    }
    private function getBackup($data){
        $newData = [];
        foreach($data as $backup){
            foreach($backup->archives as $archive){
                $name = $archive->name;
                $deco = explode('-', $name);
                if ($deco[0] == 'adminbackup'){
                    $archive->type = 'adminbackup';
                    continue;
                }
                elseif($deco[0] == 'backup'){
                    $archive->type = 'backup';
                    $archive->site = Site::where('site_id',$deco[2])->first();
                    $archive->time = $deco[3].'-'.$deco[4].'-'.$deco[5];
                }
                
                $newData[] = $archive;
                
            }

        }
        return $newData;
    }
    public function checkBackupBorgCallback($server)
    {
        $cacheKey = 'backup_status_' . $server;

        // Attempt to retrieve the result from the cache
        $cachedResult = Cache::get($cacheKey);

        if ($cachedResult !== null) {
            // If a cached result exists, return it
            return $cachedResult;
        }
        

        $remoteUploader = new RemoteSsh('root', storage_path('app') . '\ssh.key', $server);

        if ($remoteUploader->connect()) {
            $result = $remoteUploader->exec('~/.local/bin/borgmatic --version');

            if (str_contains($result, 'command not found')) {
                $response = ['status' => 'failed', 'result' => $result, 'type' => 'command_not_found'];
            } elseif (str_contains($result, 'No valid configuration files found')) {
                $response = ['status' => 'failed', 'result' => $result, 'type' => 'no_valid_config'];
            } elseif ($result) {
                $response = ['status' => 'success', 'result' => $result, 'type' => 'success'];
            } else {
                $response = ['status' => 'failed', 'result' => $result, 'type' => 'no_idea'];
            }

            // Store the result in the cache with a 24-hour expiration time
            Cache::put($cacheKey, $response, now()->addHours(24));

            return $response;
        }

        return ['status' => 'failed', 'message' => 'no connection', 'type' => 'no_connection'];
    }

    public function checkBackupConfigCallback($server)
    {
        $remoteUploader = new RemoteUpload('root', storage_path('app') . '\ssh.key', $server->ip_address);

        if ($remoteUploader->connect()) {
            $result = [];
            $filePath = '/etc/borgmatic/config.yaml';
            $result['status'] = 'success';
            $directoryContents = $remoteUploader->listDirectory(dirname($filePath));
            $result['main'] =   ['status' => 'success', 'result' => $directoryContents, 'type' => 'success'];
            if ($directoryContents === false || $directoryContents === []) {
                $yamlData = Yaml::parseFile(storage_path('app') . '\configadmin.yaml');
                $yamlData['archive_name_format'] = 'adminbackup-'.$server->forge_id.'-{now}';
                $yamlData['encryption_passphrase'] = '123qwqw321';
                $file = Yaml::dump($yamlData);
                if (!$file) {
                    return redirect()->back()->withErrors([])->withInput();
                }
                
 
                
                $tempFilePath = tempnam(sys_get_temp_dir(), 'yaml');
                file_put_contents($tempFilePath, $file);
                $upload = $remoteUploader->uploadFile($tempFilePath, '/etc/borgmatic/config.yaml' );
                
                if($upload){
                    $result['main'] =   ['status' => 'success', 'result' => 'config.yaml', 'type' => 'success'];
                    unlink($tempFilePath);
                }
                
            
                // Check if the upload was successful
                // Clean up the temporary file
               
                $result['main'] = ['status' => 'failed', 'result' => $directoryContents, 'type' => 'not_exist'];
            }

            $filePath = '/etc/borgmatic.d/config.yaml';
            $directoryContents = $remoteUploader->listDirectory(dirname($filePath));
         
            $result['d'] =   ['status' => 'success', 'result' => $directoryContents , 'type' => 'success'];
            if ($directoryContents === false) {
                $result['d'] = ['status' => 'failed', 'result' => [], 'type' => 'not_exist'];
            }
            return $result;
        }
        return ['status' => 'failed', 'message' => 'no connection', 'type' => 'no_connection'];
    }


    public function checkBackupscriptsCallback($server)
    {

        $remoteUploader = new RemoteUpload('root', storage_path('app') . '\ssh.key', $server);

        $remoteUploader->uploadScript('test');
        $cacheKey = 'backup_scripts_status_' . $server;

        // Attempt to retrieve the result from the cache
        $cachedResult = Cache::get($cacheKey);

        if ($cachedResult !== null) {
            // If a cached result exists, return it
            return $cachedResult;
        }

        $remoteUploader = new RemoteUpload('root', storage_path('app') . '\ssh.key', $server);

        if ($remoteUploader->connect()) {
            $filePaths = config('file_paths.backupfiles');
            $missingFiles = [];
            $result = [];

            foreach ($filePaths as $filePath) {
                $row = [];
                $row['filepath'] = $filePath;
                $directoryContents = $remoteUploader->listDirectory(dirname($filePath));

                if ($directoryContents === false) {
                    $missingFiles[] = $filePath;
                    $row['status'] = 'missing_directory';
                } elseif (!in_array(basename($filePath), $directoryContents)) {
                    $missingFiles[] = $filePath;
                    $row['status'] = 'missing_file';
                } else {
                    $row['status'] = 'success';
                }

                $result[] = $row;
            }

            $remoteUploader->closeConnection();

            // Check if all files were found
            $allFilesFound = empty($missingFiles);

            // Cache the result if all files were found for 4 hours
            if ($allFilesFound) {
                Cache::put($cacheKey, $result, now()->addHours(4));
            }

            return $result;
        } else {
            return ['status' => 'failed', 'message' => 'no connection', 'type' => 'no_connection'];
        }
    }
}