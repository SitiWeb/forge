<!-- resources/views/servers/index.blade.php -->

@extends('layouts.app')  {{-- Assuming you have a layout --}}

@section('content')
    <div class="container">
        <div class="p-3 bg-white rounded shadow"> 
            
        <h1>Database List</h1>
        
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>user</th>
                    <th>table_user</th>
                    <th>Password</th>
                    <th>Created at</th>
             
                    {{-- Add other table headings as needed --}}
                </tr>
            </thead>
            <tbody>
          
                @foreach ($databases as $database)
    
                    <tr>
                        <td>{{ $database->table_id }}</td>
                        <td>{{ $database->name }}</td>
                        <td><a href="{{route('users.edit',['user'=>$database->user])}}">{{ $database->user->name }}</a></td>
                        <td>@if($database->table_user)<a href="{{route('databaseusers.show',['databaseuser'=>$database->user])}}">{{ $database->table_user->name }}</a>@endif</td>
                        <td>@if($database->table_user){{ $database->table_user->password }}@endif</td>        
                   
                        <td>{{ $database->created_at }}</td>
                        {{-- Add other table cells as needed --}}
                    </tr>
                @endforeach
            </tbody>
        </table>
        <a href="{{route('databases.create')}}" class="btn btn-primary">Create new database</a>
    </div>
    </div>
@endsection
