
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
                    
                    @foreach($roles as $role)
              
                    <tr>
                        <td>{{$role->name}}</td>
                        <td>
                        <button class="btn btn-danger" onclick="showConfirmModal(this)" data-toggle="modal" data-destroy-url="{{route('roles.destroy',['role'=>$role])}}" data-target="#confirmDeleteModal"  data-type="role" data-id="{{ $role->id }}" data-name="{{ $role->name }}">
                            Destroy
                        </button>
                    </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <a href="{{route('roles.create')}}" class="btn btn-primary">Create Role</a>
        </div>
    </div>
</div>

@endsection