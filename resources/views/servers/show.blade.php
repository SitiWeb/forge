@extends('layouts.app') {{-- Assuming you have a layout --}}

@section('content')
    <div class="container my-2 ">
        <div class="row">
            <div class="col-12">
                @include('message')
                <div class="p-3 bg-white rounded shadow">
                    < <a href="{{ route('servers.index') }}">Back to servers</a>
                        <h3>Server</h3>
                        @include('servers.tables.server')
                        <a href="{{route('servers.checkbackup',['server' =>$server ])}}" class="btn btn-primary">Check config</a>
                </div>
            </div>

            <div class="col-12 my-2">
                <div class="p-3 bg-white rounded shadow ">
                    <h3>Sites</h3>

                    @include('servers.tables.sites')
                </div>
            </div>
            <div class="col-12 my-2">
                <div class="p-3 bg-white rounded shadow ">
                    <h3>Databases</h3>

                    @include('servers.tables.databases')
                </div>
            </div>
            <div class="col-12 my-2">
                <div class="p-3 bg-white rounded shadow ">
                    <h3>Cron jobs</h3>

                    @include('servers.tables.cron')
                </div>
            </div>
        </div>
    </div>
@endsection
