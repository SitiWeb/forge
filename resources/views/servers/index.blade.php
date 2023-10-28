<!-- resources/views/servers/index.blade.php -->

@extends('layouts.app')  {{-- Assuming you have a layout --}}

@section('content')
    <div class="container"> 
        <h1 class="p-2">Servers</h1>
        <div class="row">
        @foreach ($servers as $server)
            @include('servers.index.card')
        @endforeach
        </div>
    </div>
@endsection
