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
                    <th>action</th>
                    <th>status</th>
                    <th>data</th>
                    <th>users</th>
                    <th>Domain</th>
                    <th>Created At</th>
                    {{-- Add other table headings as needed --}}
                </tr>
            </thead>
            <tbody>
          
                @foreach ($imports as $import)
    
                    <tr>
                        <td>{{ $import->id }}</td>
                        <td>{{ $import->name }}</td>
                        <td>{{ $import->action }}</td>
                        <td>{{ $import->status }}</td>
                        <td>{{ $import->data }}</td>
                        <td>{{ $import->user->name }}</td>
                        <td>{{ $import->site->name }}</td>
                        <td>{{ $import->created_at }}</td>
                        {{-- Add other table cells as needed --}}
                    </tr>
                @endforeach
            </tbody>
        </table>
        <a href="{{route('imports.create')}}" class="btn btn-primary">Create new import</a>
    </div>
    </div>
@endsection
