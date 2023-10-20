<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>status</th>
            <th>createdAt</th>
            <th>Delete</th>
            {{-- Add other table headings as needed --}}
        </tr>
    </thead>
    <tbody>
            @foreach($databases as $database)
            <tr>
                <td>{{ $database->id }}</td>
                <td>{{ $database->name }}</td>
                <td>{{ $database->status }}</td>
                <td>{{ $database->createdAt }}</td>
                <td><a href="{{route('projects.delete.database',['server' => $server->forge_id, 'database' => $database->id])}}" class="btn btn-danger">Verwijderen</a></td>
                {{-- Add other table cells as needed --}}
            </tr>
            @endforeach
    </tbody>
</table>