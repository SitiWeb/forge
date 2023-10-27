<div class=" ">
    <div class="row ">
        <div class="col-12 my-2">
            < <a href="{{ route('servers.show', ['id' => $server->forge_id]) }}"> {{ $server->name }}</a>
                <h2>{{ $website->name }}</h2>
                <div class="card">
                    <div class="card-body">
                        <iframe src="https://{{ $website->name }}" height="300" style="width:100%"
                            frameborder="0"></iframe>
                    </div>
                </div>
        </div>
        <div class="col-6 my-2">
            <ul class="list-group">
                <li class="list-group-item"><strong>ID</strong><br>{{ $website->id }}</li>
                <li class="list-group-item"><strong>Name</strong><br>
                    @if ($website->isSecured)
                        <td><a target="_blank" href="https://{{ $website->name }}">{{ $website->name }}</a></td>
                    @else
                        <td><a target="_blank" href="http://{{ $website->name }}">{{ $website->name }}</a></td>
                    @endif
                </li>
                <li class="list-group-item"><strong>Username</strong><br>{{ $website->username }}</li>
                <li class="list-group-item"><strong>PHP version</strong><br>{{ $website->phpVersion }}</li>
                <li class="list-group-item"><strong>DNS Lookup</strong><br>
                    @if ($website->dns == $server->ip_address)
                        {{ $website->dns }}
                    @else
                        {{ $website->dns }}
                    @endif

                </li>

                <!-- Add more list items as needed -->
            </ul>
        </div>
        <div class="col-6 my-2">
            <ul class="list-group">
                <li class="list-group-item"><strong>Directory</strong><br>{{ $website->directory }}</li>
                <li class="list-group-item"><strong>Aliases</strong><br>{{ implode(', ', $website->aliases) }}</li>
                <li class="list-group-item"><strong>PHP version</strong><br>{{ $website->phpVersion }}</li>
                <li class="list-group-item"><strong>Secured?</strong><br>
                    @if ($website->isSecured)
                        Yes
                    @else
                        No
                    @endif
                </li>

                <!-- Add more list items as needed -->
            </ul>
        </div>
        <div class="d-flex gap-2"> 
            <a class="btn btn-success" href="{{route('projects.ssl',['server'=>$server->forge_id,'site'=>$website->id])}}">SSL</a>
            
        </div>
    </div>
</div>
