
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row my-2">
        <div class="col-12 rounded shadow bg-white p-3">
            <table class="table ">
                <thead>
                <tr>
                    <th>Key</th>
                    <th>Type</th>
                    <th>Server</th>
                    <th>User</th>
                </tr>
                </thead>
                <tbody>
                    
                    @foreach($sshkeys as $sshkey)
              
                    <tr>
                        <td>{{$sshkey->key}}</td>
                        <td>{{$sshkey->type}}</td>
                        <td>{{$sshkey->server->name}}</td>
                        <td>{{$sshkey->user->name}}</td>
                        <td>
                        <button class="btn btn-danger" onclick="showConfirmModal(this)" data-toggle="modal" data-destroy-url="{{route('roles.destroy',['role'=>$role])}}" data-target="#confirmDeleteModal"  data-type="role" data-id="{{ $role->id }}" data-name="{{ $role->name }}">
                            Destroy
                        </button>
                    </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <a href="{{route('roles.create')}}" class="btn btn-primary">Add new key</a>
        </div>
    </div>
</div>

@endsection