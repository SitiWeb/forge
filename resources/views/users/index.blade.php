
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row my-2">
        <div class="col-12 rounded shadow bg-white p-3">
            @include('message')
            <table class="table ">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>email</th>
                    <th>sites</th>
                    <th>roles</th>
                    <th>Delete</th>
                </tr>
                </thead>
                <tbody>
                    
                    @foreach($users as $user)
                 
                    <tr>
                        <td><a href="{{route('users.edit',['user'=>$user->id])}}">{{$user->name}}</a></td>
                        <td>{{$user->email}}</td>
                        <td>{{count($user->sites)}}</td>
                        <td>
                            @foreach($user->roles as $role)
                            {{$role->name}}
                            @endforeach
                        </td>
                        <td>
                               <!-- "Destroy" button with data attributes -->
                            <button class="btn btn-danger" onclick="showConfirmModal(this)" data-toggle="modal" data-destroy-url="{{route('users.destroy',['user'=>$user])}}" data-target="#confirmDeleteModal"  data-type="user" data-id="{{ $user->id }}" data-name="{{ $user->name }}">
                                Destroy
                            </button>
                        </td>
                                            
                    </tr>
                    
                    
                @endforeach
                </tbody>
            </table>
           
            <a href="{{route('users.create')}}" class="btn btn-primary">Create user</a>
            <a href="{{route('roles.index')}}" class="btn btn-secondary">Manage roles</a>
        </div>
    </div>
</div>

@endsection