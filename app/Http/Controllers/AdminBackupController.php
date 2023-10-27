<?php

namespace App\Http\Controllers;

use App\Models\Adminbackup;
class AdminBackupController extends Controller


{
    public function index(){
  
    }

    public function receiveBackupList(Request $request)
    {
        $backups = $request->input('backups');

        // Process the backups list (e.g., store it in your database)

        // Respond with a success message or any relevant response
        return response()->json(['message' => 'Backups received and processed.']);
    }
}
