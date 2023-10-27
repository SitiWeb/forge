<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Adminbackup;
use App\Http\Controllers\RepositoryController;
use PDO;

class AdminBackupController extends Controller


{
    public function index(){
        $adminbackups = Adminbackup::all();
        return view('adminbackups.index', compact('adminbackups'));
    }

    public function receiveBackupList(Request $request)
    {
        //$backups = $request->parameter('archives');
        $repo = new RepositoryController();
        $array = $request->input('backups'); // Get all request data
        // Decode the JSON string into an array
        $backupData = json_decode($array, true);
     
        foreach($backupData as $data){
            if (!isset($data['archives'])){
                continue;
            }
            $archives = $data['archives'];
            $repositoryData = $data['repository'];
            $repo = $repo->store($repositoryData);
            $this->storeArchives( $archives, $repo); 
        }
        
        // Process the backups list (e.g., store it in your database)

        // Respond with a success message or any relevant response
        return response()->json(['message' => 'Backups received and processed.']);
    }


    public function storeArchives($archives, $repo){
        $backups = [];
        foreach($archives as $archive){
     
            // First, try to find an existing backup record by 'backup_id'
            $existingBackup = Adminbackup::where('backup_id', $archive['id'])->first();
        
            if ($existingBackup) {
                // If an existing backup record is found, update it
                $existingBackup->update([
                    'archive' => $archive['archive'],
                    'barchive' => $archive['barchive'],
                    'start' => $archive['start'],
                    'time' => $archive['time'],
                    'name' => $archive['name'],
                    'repository_id' => $repo->id,
                ]);

                // Store the ID of the updated or existing backup
                $backups[] = $existingBackup->id;
            } else {
                // If no existing backup is found, create a new one
                $newBackup = Adminbackup::create([
                    'archive' => $archive['archive'],
                    'barchive' => $archive['barchive'],
                    'backup_id' => $archive['id'],
                    'start' => $archive['start'],
                    'time' => $archive['time'],
                    'name' => $archive['name'],
                    'repository_id' => $repo->id,
                ]);

                // Store the ID of the newly created backup
                $backups[] = $newBackup->id;
            }

        }
        
        // Now, remove backups where 'repository_id' matches $repo->id but are not present in $backups
        Adminbackup::where('repository_id', $repo->id)
            ->whereNotIn('id', $backups)
            ->delete();
           
    

    }

    public function destroy(){

    }
}
