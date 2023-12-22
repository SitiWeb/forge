@if($borg)
<table class="table">
    <thead>
        <tr>
            <th>
                Borgmatic
            </th>
            <th>
                Status
            </th>
        </tr>
    </thead>
    <tbody>

    <tr>
        <td>
         
            @if($borg['status'] == 'success')
           
                <div class="text-success">Borgmatic is correctly installed</div>
            @else
                <div class="text-danger">X {{($borg['type'])}}</div>
            @endif
        </td>
        <td>
            @if($borg['status'] ==='success')
                <div class="text-success"></div>
            @else
                <div class="text-danger">X {{($borg['result'])}}</div>
            @endif
        </td>
    </tr>

</table>
@endif