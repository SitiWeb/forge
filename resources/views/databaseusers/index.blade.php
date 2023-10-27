<!-- resources/views/servers/index.blade.php -->

@extends('layouts.app')  {{-- Assuming you have a layout --}}

@section('content')
    <div class="container">
        <div class="p-3 bg-white rounded shadow"> 
            
        <h1>Database List</h1>
        
        <table class="table">
            <thead>
                <tr>
                    <th>ID123</th>
                    <th>Name</th>
                    <th>table_user_id</th>
                    <th>Status</th>
                    <th>Password</th>
     
                    <th>Created at</th>
             
                    {{-- Add other table headings as needed --}}
                </tr>
            </thead>
            <tbody>
          
                @foreach ($databaseusers as $databaseuser) 

                    <tr>
                        <td>{{ $databaseuser->id }}</td>
                        <td>{{ $databaseuser->name }}</td>
                        <td>{{ $databaseuser->table_user_id }}</td>
                        <td>{{ $databaseuser->status }}</td>
                        <td>{{ $databaseuser->password }}</td>
                        <td>{{ $databaseuser->created_at }}</td>
                        <td>@foreach($databaseuser->databases as $data)
                            {{$data->name}}
                            @endforeach
                        </td>
                    
                        <td><a href="{{route('databaseusers.edit',['databaseuser'=>$databaseuser])}}" class="btn btn-secondary">edit</a></td>
                        <td>
                            
    <form action="{{ route('databaseusers.destroy', ['databaseuser' => $databaseuser]) }}" method="POST">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger">Delete</button>
    </form></td>
                        {{-- Add other table cells as needed --}}
                    </tr>
                @endforeach
            </tbody>
        </table>
        <a href="{{route('databaseusers.create')}}" class="btn btn-primary">Create new import</a>
    </div>
    </div>
@endsection
