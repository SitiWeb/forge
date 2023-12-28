<?php

namespace App\Http\Controllers;
use App\Models\Database;
use App\Models\Site;
use App\Library\Form;
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
                        'server_id' => $apiServer->id,
                        'status' => $database->status,
                    ]);
                }
                else{
                    Database::create([
                        'name' => $database->name,
                        'server_id' => $apiServer->id,
                        'table_user_id' => '',
                        'user_id' => 1,
                        'status' => $database->status,
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
                'user_id' => Auth::user()->id,
                'server_id' => $request->input('server'),
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
        $database = Database::find($id);
        $databaseuser = DatabaseUser::where('name', $database->name)->first();
        $old_site = false;
        if ($databaseuser->site_id){
            $old_site = $databaseuser->site_id;
        }
        
        $form = new Form('Edit database', route('databases.update', ['database'=> $database]), 'PUT');
        $form->setSubmitText('Edit');
 
        $options = [];
        $sites = Site::where('server_id',$databaseuser->server_id)->get();
     
        foreach($sites as $site){
            $options[] = ['value' => $site->site_id, 'label'=>$site->name]; 
        }
        $form->addField('site', 'select', [
            'label' => 'Select site',
            'width' => 12,
            'old' => $old_site,
            'selected' => 'php82',
            'options' => $options,
        ]);
     
        
      
        return view('databases.edit', compact('database','databaseuser','form'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
      
        // Validate the form data as needed
        $request->validate([
            'site' => 'required|integer', // Adjust validation rules as needed
        ]);


        // Find the database record to update
        $database = Database::findOrFail($id);
        $databaseuser = DatabaseUser::where('name', $database->name)->first();
        // Update the record with the new data
        $databaseuser->site_id = $request->input('site'); // Assuming 'site' is the field you want to update
        $databaseuser->save();

        // Redirect to a success page or return a response as needed
        return redirect()->route('databases.index')->with('success', 'Record updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
