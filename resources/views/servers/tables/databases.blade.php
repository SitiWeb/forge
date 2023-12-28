<table class="table">
    <thead>
        <tr>
            {{-- <th>ID</th> --}}
            <th>Name</th>
            <th>status</th>
            {{-- <th>createdAt</th> --}}
            <th>Delete</th>
            {{-- Add other table headings as needed --}}
        </tr>
    </thead>
    <tbody>
            @foreach($server->databases as $database)
            <tr>
                {{-- <td>{{ $database->id }}</td> --}}
                <td>{{ $database->name }}</td>
                @php
                $badge_value = $database->status;
                @endphp
                <td>
                    @include('badges')    
                </td>
                {{-- <td>{{ $database->createdAt }}</td> --}}
                <td ><a class="d-none" href="{{route('projects.delete.database',['server' => $server->forge_id, 'database' => $database->id])}}" class="btn btn-danger">Verwijderen</a></td>
                {{-- Add other table cells as needed --}}
            </tr>
            @endforeach
    </tbody>
</table>