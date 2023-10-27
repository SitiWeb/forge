<?php

namespace App\Http\Controllers;

use App\Library\HttpSocket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\Models\Directadmin;
use Illuminate\Support\Facades\Auth;

class DirectadminController extends Controller
{
    //
    public function index()
    {
        $directadmins = Directadmin::all();
        return view('directadmin.index', compact('directadmins'));
    }

    public function create()
    {

        return view('directadmin.create');
    }


    public function store(Request $request)
    {
        // Validate the input data
        $request->validate([
            'host' => 'required|string',
            'port' => 'required|integer',
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Create a new DirectAdminCredential instance and save it to the database
        $credential = new Directadmin;
        $credential->host = $request->input('host');
        $credential->port = $request->input('port');
        $credential->username = $request->input('username');
        $credential->password = $request->input('password');
        $credential->user_id = Auth::getUser()->id;
        $credential->save();

        // Redirect to a success page or another route
        return redirect()->route('directadmin.index')->with('message', 'DirectAdmin credentials saved successfully!');
    }

    public function show(Directadmin $directadmin)
    {

        return view('directadmin.show', compact('directadmin'));
    }

    public function edit(Directadmin $directadmin)
    {

        return view('directadmin.edit', compact('directadmin'));
    }
    public function update(Request $request, DirectAdminCredential $directadminCredential)
    {
        // Validate the input data
        $request->validate([
            'host' => 'required|string',
            'port' => 'required|integer',
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Update the DirectAdmin credential
        $directadminCredential->update([
            'host' => $request->input('host'),
            'port' => $request->input('port'),
            'username' => $request->input('username'),
            'password' => $request->input('password'),
        ]);

        // Redirect to a success page or another route
        return redirect()->route('directadmin.index')->with('message', 'DirectAdmin credentials updated successfully!');
    }

    public function destroy(Directadmin $directadmin)
    {
        // Delete the DirectAdmin credential
        $directadmin->delete();

        // Redirect to a success page or another route
        return redirect()->route('directadmin.index')->with('message', 'DirectAdmin credential deleted successfully!');
    }

    public function test(Directadmin $directadmin){

        $sock = $this->auth($directadmin);
        $result = $this->getAdmins($sock);
        if ($result){
            return response()->json(['status'=>'success']);
        }
        return response()->json(['status'=>'failed']);
    }

    public function dataSwitch(Request $request, Directadmin $directadmin, $action){
    
       
        $sock = $this->auth($directadmin);
        
        switch($action){
            case 'getUser':
               
                $request->validate([
                    'user' => 'required|string|max:12',
                    // Add other validation rules as needed for your request
                ]);
                $result = $this->getUser($sock,$request->input('user'));
                break;
            case 'getUsers':
                $result = $this->getUsers($sock);
                break;
            case 'updateOption':
                $request->validate([
                    'user' => 'required|string|max:12',
                    'option' => 'required|string|max:50',
                    'value' => 'required|string|max:5000',
                    // Add other validation rules as needed for your request
                ]);
                $result = $this->updateOption($sock,$request->input('user'), $request->input('option'), $request->input('value'));
                break;
            
            default:
                return response()->json(['status'=>'failed']);
        }
        return response()->json(['status'=>'success','data' => $result]);
    }

    public function getAdmins($sock){
        $sock->query('/CMD_API_SHOW_ADMINS');
        $result = $sock->fetch_parsed_body();
        return $result;
    }

    public function updateOption($sock, $user, $option, $value){
        $data = [
            'action' => 'customize',
            'user' => $user,
            $option =>  $value,
          
        ];
        $sock->query('/CMD_API_MODIFY_USER',$data);
        $result = $sock->fetch_parsed_body();
   
        return $result;
    }

    public function getUser($sock, $user){
        $sock->query('/CMD_API_SHOW_USER_CONFIG?user='.$user);
        $result = $sock->fetch_parsed_body();
        return $result;
    }

    public function getUsers($sock){
        $sock->query('/CMD_API_SHOW_ALL_USERS');
        $result = $sock->fetch_parsed_body();
        return $result;
    }


    private function auth(Directadmin $directadmin){
        $sock = new HttpSocket();

        $sock->connect($directadmin->host, $directadmin->port);
        $sock->set_login($directadmin->username, $directadmin->password);

        return $sock;        
    }
}
