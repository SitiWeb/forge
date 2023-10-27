<!-- resources/views/imports/show.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Import Details</h1>
        <p><strong>Name:</strong> {{ $import->name }}</p>
        <p><strong>action:</strong> {{ $import->action }}</p>
        <p><strong>Status:</strong> {{ $import->status }}</p>
        <p><strong>Server:</strong> {{ $import->user->name }}</p>
        <p><strong>Data:</strong> {{ $import->data }}</p>
    </div>
    @include('imports.types.wordpress')
@endsection
