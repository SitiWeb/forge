@if($result)
<table class="table">
    <thead>
        <tr>
            <th>
                files
            </th>
            <th>
                Status
            </th>
        </tr>
    </thead>
    <tbody>
    @foreach($result as $row)
    <tr>
        <td>
            @if($row['status'] === 'success')
                <div class="text-success">{{($row['filepath'])}}</div>
            @else
                <div class="text-danger">X {{($row['filepath'])}}</div>
            @endif
        </td>
        <td>
            @if($row['status'] === 'success')
                <div class="text-success">{{($row['status'])}}</div>
            @else
                <div class="text-danger">X {{($row['status'])}}</div>
            @endif
        </td>
    </tr>
    @endforeach
</table>
@endif