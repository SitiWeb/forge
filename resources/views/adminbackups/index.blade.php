
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row my-2">
        <div class="col-12 rounded shadow bg-white p-3">
            <table class="table ">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Delete</th>
                </tr>
                </thead>
                <tbody>
                    
                    @foreach($adminbackups as $adminbackup)
              
                    <tr>
                        <td>{{$adminbackup->name}}</td>
                        <td>
                        <button class="btn btn-danger" onclick="showConfirmModal(this)" data-toggle="modal" data-destroy-url="{{route('adminbackup.destroy',['adminbackup'=>$adminbackup])}}" data-target="#confirmDeleteModal"  data-type="role" data-id="{{ $adminbackup->id }}" data-name="{{ $adminbackup->name }}">
                            Destroy
                        </button>
                    </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <a href="{{route('adminbackups.create')}}" class="btn btn-primary">Create admin backup</a>
        </div>
    </div>
</div>

@endsection