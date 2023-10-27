<!-- resources/views/create.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Create an import</h1>
        @include('errors')
        <form method="POST" action="{{ route('databaseusers.store') }}">
            @csrf
           
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>

         
            <button type="submit" class="btn btn-primary">Edit password</button>
        </form>
    </div>
    @endsection
