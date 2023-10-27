
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row my-2">
        <div class="col-12 rounded shadow bg-white p-3">
            <table class="table ">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Server</th>
                    <th>User</th>
                </tr>
                </thead>
                <tbody>
                    
                    @foreach($sites as $site)
                    <a href=""></a>
                    <tr>
                        
                        <td>
                            <a 
                            href="{{route('projects.show',[
                                'server'=>$site->server->forge_id,'site'=>$site->site_id
                                ])}}">{{$site->name}}</a>
                        </td>
                        <td>
                            <a 
                            href="{{route('servers.show',[
                                'id'=>$site->server->forge_id
                                ])}}">{{$site->server->name}}</a></td>
                        <td>{{$site->user->name}}</td>
                    
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection