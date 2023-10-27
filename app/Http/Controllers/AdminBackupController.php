<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Adminbackup;
use App\Http\Controllers\RepositoryController;
class AdminBackupController extends Controller


{
    public function index(){
  
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
        foreach($archives as $archive){
     
            $repository = Adminbackup::firstOrCreate(
                ['backup_id' => $archive['id']],
                [
                    'archive' => $archive['archive'],
                    'barchive' => $archive['barchive'],
                    'backup_id' => $archive['id'],
                    'start' => $archive['start'],
                    'time' => $archive['time'],
                    'name' => $archive['name'],
                    'repository_id' => $repo->id,
                ]
            );
           
        }

    }
}
