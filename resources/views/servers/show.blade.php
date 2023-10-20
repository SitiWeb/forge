@extends('layouts.app')  {{-- Assuming you have a layout --}}

@section('content')
    <div class="container">
        @include('message')
        <h1>Server Details</h1>     
        @include('servers.tables.server')
    </div>

    <div class="container">
        
        <h1>Sites Details</h1>    
         
        @include('servers.tables.sites')
    </div>

    <div class="container">
        <h1>Databases Details</h1>     
        @include('servers.tables.databases')
    </div>
    @endsection