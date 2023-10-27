<!-- resources/views/create.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Create a Database</h1>
        @include('errors')
        <form method="POST" action="{{ route('databases.store') }}">
            @csrf

            <div class="form-group">
                <label for="server">Choose server:</label>
                <select class="form-control" id="server" name="server" required>
                    <option value="">Select server</option>
                    @foreach ($servers as $key => $server)
                        <option value="{{$server->forge_id}}">{{ $server->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="database">Database name:</label>
                <input type="text" class="form-control" id="database" name="database" required>
            </div>


            <div class="form-group">
                <label for="user">username:</label>
                <input type="text" class="form-control" id="user" name="user" required>
            </div>


           
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>


      
            <button type="submit" class="btn btn-primary">Edit password</button>
        </form>
    </div>
    @endsection
