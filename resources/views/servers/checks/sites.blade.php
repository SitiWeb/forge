
<table class="table">
    <thead>
        <tr>
            <th>
                site
            </th>
            <th>
                App
            </th>
            <th>
                Config /etc/borgmatic.d
            </th>
            <th>
                Status
            </th>
        </tr>
    </thead>
    <tbody>
    @foreach($sites as $site)
    <tr>
        <td>
            {{$site->name}}
        </td>
        <td>
            {{$site->type}}
        </td>
        <td>
            {{$site->config_path}}        
        </td>
        <td>
          
            @if(in_array(basename($site->config_path),$config['d']['result']))
                <div class="text-success"></div>
            @else
                <div class="text-danger">X Not configured</div>
            @endif
        </td>
    </tr>
    <tr>
        <td colspan="4">
            @include('servers.checks.site')
        </td>
    </tr>
    @endforeach

</table>
