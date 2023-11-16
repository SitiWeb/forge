@extends('layouts.app') {{-- Assuming you have a layout --}}
@section('content')
    <div class="container">
        <div class="row my-2">
            <div class="col-6">
                <div class="p-3 bg-white rounded shadow ">
                    @include('sites.site.list')
                </div>

            </div>
            <div class="col-6">
                <div class="p-3 bg-white rounded shadow ">
                    @include('sites.site.tabs')
                    
                </div>
            </div>
        </div>
        @if($website->repository)
        <div class="row">
            <div class="col">

                <div class="p-3 bg-white rounded shadow ">
                    @include('sites.site.accordion')
                </div>
            </div>
        </div>
        @endif
        <div class="row m-2">
            <div class="col justify-content-end d-flex mt-4">
                <a class="btn btn-danger" href="{{route('projects.delete.site',['server'=>$server->forge_id,'site'=>$website->id])}}">Delete</a>
            </div>
        </div>
    </div>

@endsection
