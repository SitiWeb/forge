<!-- resources/views/servers/index.blade.php -->

@extends('layouts.app')  {{-- Assuming you have a layout --}}

@section('content')
    <div class="container">
        <div class="p-3 bg-white rounded shadow"> 
            
        <h1>Server List</h1>
        
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Size</th>
                    <th>Region</th>
                    <th>IP Address</th>
                    <th>Private IP Address</th>
                    <th>PHP Version</th>
                    <th>Created At</th>
                    {{-- Add other table headings as needed --}}
                </tr>
            </thead>
            <tbody>
          
                @foreach ($servers as $server)
    
                    <tr>
                        <td>{{ $server->id }}</td>
                        <td><a href="{{route('servers.show',['id'=>$server->forge_id])}}">{{ $server->name }}</a></td>
                        <td>{{ $server->size }}</td>
                        <td>{{ $server->region }}</td>
                        <td>{{ $server->ip_address }}</td>
                        <td>{{ $server->private_ip_address }}</td>
                        <td>{{ $server->php_version }}</td>
                        <td>{{ $server->server_created_at }}</td>
                        {{-- Add other table cells as needed --}}
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    </div>
@endsection
