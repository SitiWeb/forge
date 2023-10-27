<?php

namespace App\Http\Controllers;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(){
        $roles = Role::all();
        return view('roles.index',compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('roles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the input data (you can add more validation rules)
        $request->validate([
            'name' => 'required|string|max:255|unique:roles',
        ]);

        // Create a new role
        $role = new Role;
        $role->name = $request->name;
        $role->save();

        // Redirect to a success page or another route
        return redirect()->route('roles.index')->with('message', 'Role created successfully!');
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
    public function destroy(Role $role)
    {
        // Delete the role
        $role->delete();

        // Redirect to a success page or another route
        return redirect()->route('roles.index')->with('message', 'Role deleted successfully!');
    }
}
