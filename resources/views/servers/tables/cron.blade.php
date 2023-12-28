<table class="table">
    <thead>
        <tr>
            {{-- <th>ID</th> --}}
            <th>Command</th>
            <th>Frequency</th>
            <th>Cron</th>
            <th>User</th>
            <th>status</th>
            {{-- <th>createdAt</th> --}}
          
            {{-- Add other table headings as needed --}}
        </tr>
    </thead>
    <tbody>
            @foreach($server->jobs as $job)
            @if($job->user == 'root')
            @continue
            @endif
            <tr>
                {{-- <td>{{ $database->id }}</td> --}}
                <td>{{ $job->command }}</td>
                <td>{{ $job->frequency }}</td>
                <td>{{ $job->cron }}</td>
                <td>{{ $job->user }}</td>
                @php
                $badge_value = $job->status;
                @endphp
                <td>
                    @include('badges')    
                </td>
                {{-- <td>{{ $database->createdAt }}</td> --}}
                
                {{-- Add other table cells as needed --}}
            </tr>
            @endforeach
    </tbody>
</table>