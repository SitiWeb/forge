<!-- resources/views/servers/index.blade.php -->

@extends('layouts.app')  {{-- Assuming you have a layout --}}

@section('content')
    @include('servers.checks.borg')
    @include('servers.checks.files')
    @include('servers.checks.config')
    @include('servers.checks.sites')
    @include('servers.checks.cron')
    @include('servers.checks.backups')
@endsection
