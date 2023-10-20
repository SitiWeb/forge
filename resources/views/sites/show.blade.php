@extends('layouts.app')  {{-- Assuming you have a layout --}}
@section('content')
<div class="container">
    <div class="row m-2">
        <div class="col">
            @include('sites.site.first')
        </div>
    </div>
    <div class="row m-2">
        <div class="col">
            @include('sites.site.second')
        </div>
    </div>
    <div class="row m-2">
        <div class="col">
            @include('sites.site.third')
        </div>
    </div>
    <div class="row m-2">
        <div class="col">
            @include('sites.site.fourth')
        </div>
    </div>
</div>
@endsection