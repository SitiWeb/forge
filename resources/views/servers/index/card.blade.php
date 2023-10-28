<div class="col-4">
    <div class="card shadow-sm sw-card">
        <div class="card-body">
            <div class="d-flex flex-column">
                <div class="py-1">
                    <a class="sw-link" href="{{ route('servers.show', ['id' => $server->forge_id]) }}">{{ $server->name }}</a>
                </div>
                <div  class="py-1">
                    {{$server->php_version}}, {{$server->region}}
                </div>
                <div  class="py-1">
                    {{$server->ip_address}}
                </div>
              
            </div>
        </div>
    </div>
</div>
