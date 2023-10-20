<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>status</th>
            <th>repository</th>
            <th>username</th>
            
            <th>PHP Version</th>


            {{-- Add other table headings as needed --}}
        </tr>
    </thead>
    <tbody>
            @foreach($websites as $website)
            <tr>
                <td>{{ $website->id }}</td>
                <td><a href="{{route('projects.show',['site'=>$website->id, 'server' => $server->id])}}">{{ $website->name }}</a></td>
                <td>{{ $website->status }}</td>
                <td>{{ $website->repository }}</td>
                <td>{{ $website->username }}</td>
            
                <td>{{ $website->phpVersion }}</td>
     
            </tr>
            @endforeach
            
    </tbody>
</table>
<a href="{{route('projects.create',['server'=> $server->id])}}" class="btn btn-primary mb-4">create site</a>