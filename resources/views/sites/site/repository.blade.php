@if($website->repository)
<div class=""> 
    <ul class="list-group">
        <li class="list-group-item"><strong>{{ $website->repository }}</strong><br>
        <div class="d-flex gap-3">
            <div>
                {{ $website->repositoryProvider }}
            </div>
            <div>
                {{ $website->repositoryBranch }}
            </div>
            <div>
                @php
                    $badge_value = $website->repositoryStatus;
                @endphp
                @include('badges')
            </div>
        </div>
        
        </li>
        <!-- Add more list items as needed -->
    </ul>
</div>
@endif
<div class="my-2 d-flex gap-3">
<a class="btn btn-primary" href="{{route('projects.install',['server'=>$server->forge_id,'site'=>$website->id])}}">Install</a>
<a class="btn btn-secondary" href="{{route('projects.deploy',['server'=>$server->forge_id,'site'=>$website->id])}}">deploy</a>
</div>