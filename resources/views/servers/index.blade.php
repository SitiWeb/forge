<!-- resources/views/servers/index.blade.php -->

@extends('layouts.app')  {{-- Assuming you have a layout --}}

@section('content')
    <div class="container"> 
        <div class="d-flex align-items-center">
        <h1 class="p-2">Servers</h1>
        <form class="p-2 form-inline" style="max-width:350px;">
            <div class="input-group">
              <input type="text" class="form-control" name="q" value="{{request('q')}}" placeholder="Search...">
              <div class="input-group-append">
                <button class="btn btn-primary" type="submit">
                  <i class="fas fa-search"></i> <!-- Font Awesome Search Icon -->
                </button>
              </div>
            </div>
          </form>
        </div>
        <div class="row">

        @foreach ($servers as $server)
            @include('servers.index.card')
        @endforeach
        </div>
    </div>
@endsection
