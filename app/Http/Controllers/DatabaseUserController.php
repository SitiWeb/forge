<?php

namespace App\Http\Controllers;

use App\Models\Database;
use Laravel\Forge\Forge;
use App\Models\DatabaseUser;
use Illuminate\Http\Request;

class DatabaseUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
           // Fetch data from the Forge API
           $forge = new Forge(config('forge.api_key'));
        
      
        $databaseusers = DatabaseUser::all();
        return view('databaseusers.index', compact('databaseusers'));
    }

    public function edit(DatabaseUser $databaseuser){
    
        return view('databaseusers.edit', compact('databaseuser'));
    }
 

    public function syncDatabseUsers()
    {
        // Fetch data from the Forge API
        $forge = new Forge(config('forge.api_key'));
        
        $apiServers = $forge->servers();
        
    // Initialize an array to track processed site IDs
        $processedSiteIds = [];
        foreach ($apiServers as $apiServer) {
            
            $databaseusers =$forge->databaseUsers($apiServer->id);
        
            foreach($databaseusers as $databaseuser){
              
                $databasess = DatabaseUser::where('table_user_id', $databaseuser->id)->first();
                if ($databasess){
                    $databasess->update([
                        'name' => $databaseuser->name,
                        'server_id' => $databaseuser->serverId,
                        'created_at' => $databaseuser->createdAt,
                        'status' => $databaseuser->status,
                    ]);
                }
                else{
                    DatabaseUser::create([
                        'name' => $databaseuser->name,
                        'table_user_id' => $databaseuser->id,
                        'server_id' => $databaseuser->serverId,
                        'created_at' => $databaseuser->createdAt,
                        'status' => $databaseuser->status,
                    ]);
                }
                 // Add the site ID to the processed array
                  $processedSiteIds[] = $databaseuser->id;
            }
        }
        // Delete sites that were not processed (not in the $processedSiteIds array)
        // Delete sites that were not processed (not in the $processedSiteIds array)
        DatabaseUser::whereNotIn('table_user_id', $processedSiteIds)->delete();
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $servers = Server::all();
        return view('databaseusers.create',compact('servers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

   

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validate the input data (e.g., password validation)
        $request->validate([
            'password' => 'required|min:6', // Add any other validation rules as needed
        ]);
        // Fetch data from the Forge API
        $forge = new Forge(config('forge.api_key'));
        
        // Find the database user by ID
        $user = DatabaseUser::find($id);
        
        $database = $forge->databaseUser($user->server_id,$user->id);
        



        // Redirect to a success page or show a success message
        return redirect()->route('databaseusers.index')->with('success', 'Password updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Assuming $id is the ID of the user you want to remove from a database
        $user = DatabaseUser::findOrFail($id); // Find the user by ID
        $forge = new Forge(config('forge.api_key'));
        $forge->deleteDatabaseUser($user->server_id, $user->table_user_id);

        // Detach the user from the specified database
        $user->delete();

        // Optionally, you can return a response or redirect to another page after the operation is complete
        return redirect()->route('databaseusers.index')->with('message', 'User removed from the database successfully.');
    }
}
