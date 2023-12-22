@if($config)
@isset($config['main'])
<table class="table">
    <thead>
        <tr>
            <th>
                Config /etc/borgmatic
            </th>
            <th>
                Status
            </th>
        </tr>
    </thead>
    <tbody>

    <tr>
        <td>
         
            @if($config['main']['status'] == 'success')
                @foreach($config['main']['result'] as $file)
                <div class="text-success">{{$file}}</div>
                @endforeach
            @else
                <div class="text-danger">X {{($config['main']['status'])}}</div>
            @endif
        </td>
        <td>
            @if($config['main']['status'] ==='success')
                <div class="text-success"></div>
            @else
                <div class="text-danger">X {{($config['main']['result'])}}</div>
            @endif
        </td>
    </tr>

</table>
@endisset
@endif