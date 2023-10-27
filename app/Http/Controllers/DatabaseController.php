<?php

namespace App\Http\Controllers;
use App\Models\Database;
use App\Models\DatabaseUser;
use App\Models\Server;
use Laravel\Forge\Forge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class DatabaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $databases = Database::all();
        return view('databases.index', compact('databases'));
    }

    public function syncDatabses()
    {
        // Fetch data from the Forge API
        $forge = new Forge(config('forge.api_key'));
        
        $apiServers = $forge->servers();
        
    // Initialize an array to track processed site IDs
        $processedSiteIds = [];
        foreach ($apiServers as $apiServer) {
            
            $databases =$forge->databases($apiServer->id);
            
            foreach($databases as $database){
        
                $databasess = Database::where('table_id', $database->id)->first();
                if ($databasess){
                    $databasess->update([
                        'name' => $database->name,
                        'server_id' => $database->id,
                    ]);
                }
                else{
                    Database::create([
                        'name' => $database->name,
                        'table_user_id' => $apiServer->id,
                        'user_id' => 1,
                        'table_id' => $database->id
                    ]);
                }
                 // Add the site ID to the processed array
                  $processedSiteIds[] = $database->id;
            }
        }
        // Delete sites that were not processed (not in the $processedSiteIds array)
        // Delete sites that were not processed (not in the $processedSiteIds array)
        Database::whereNotIn('table_id', $processedSiteIds)->delete();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $servers = Server::all();
        return view('databases.create',compact('servers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Fetch data from the Forge API
        $forge = new Forge(config('forge.api_key'));
        
        $apiServers = $forge->servers();
        $data = [
            'name' => $request->input('database'),
    
        ];

        


        $result = $forge->createDatabase($request->input('server'),$data);
   
        $database = Database::create(
            [
                'table_id' => $result->id,
                'name' => $result->name,
                'user_id' => Auth::user()->id
             ]
            );
            $data = [
                'name' => $request->input('database'),
                'user' => $request->input('username'),
                'password' => $request->input('password'),
                'databases' => [$result->id],
            ];
            $db_user = ($forge->createDatabaseUser($request->input('server'),$data));
            $user = DatabaseUser::create(
            [
                'table_user_id' => $db_user->id,
                'status' => $db_user->status,
                'name' => $db_user->name,
                'server_id' => $db_user->serverId,
                'createdAt' => $db_user->createdAt,
                'password' => $request->input('password')
                ]
            );
            $user->databases()->attach($database);

            $database->table_user_id = $db_user->id;
            $database->save();
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
