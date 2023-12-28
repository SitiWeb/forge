<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>status</th>
            {{-- <th>repository</th>
            <th>username</th>
            <th>PHP Version</th> --}}
            {{-- Add other table headings as needed --}}
        </tr>
    </thead>
    <tbody>
            @foreach($server->sites as $website)
            <tr>
               
                <td>{{ $website->site_id }}</td>
                <td><a href="{{route('projects.show',['site'=>$website->site_id, 'server' => $server->forge_id])}}">{{ $website->name }}</a></td>
                @php
                $badge_value = $website->status;
                @endphp
                <td>
                    @include('badges')    
                </td>
                {{-- <td>{{ $website->repository }}</td>
                <td>{{ $website->username }}</td>
                <td>{{ $website->phpVersion }}</td> --}}
            </tr>
            @endforeach
            
    </tbody>
</table>
<a href="{{route('projects.create',['server'=> $server->forge_id])}}" class="btn btn-primary mb-4">create site</a>