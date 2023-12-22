<div class="col-4">
    <div class="card shadow-sm sw-card">
        <div class="card-body">
            <div class="d-flex flex-column">
                <div class="py-1">
                    <a class="sw-link" href="{{ route('servers.show', ['id' => $server->forge_id]) }}">{{ $server->name }}</a>
                </div>
                <div  class="py-1">
                    <i class="fa-brands fa-php" style="color: #008000;"></i> {{$server->php_version}}
                </div>
                <div  class="py-1">
                    <i class="fa-solid fa-globe-pointer" style="color: #008000;"></i> {{$server->region}}
                </div>
                <div  class="py-1">
                    <i class="fa-sharp fa-thin fa-network-wired fa-sm" style="color: #008000;"></i> {{$server->ip_address}}
                </div>
              
            </div>
        </div>
    </div>
</div>
