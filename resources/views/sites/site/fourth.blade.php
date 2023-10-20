<div class="p-3 bg-white rounded shadow d-flex gap-3"> 

    <a class="btn btn-primary" href="{{route('projects.install',['server'=>$server->forge_id,'site',$website->id])}}">Install</a>
    <a class="btn btn-secondary" href="{{route('projects.deploy',['server'=>$server->forge_id,'site',$website->id])}}">deploy</a>
    <a class="btn btn-success" href="{{route('projects.ssl',['server'=>$server->forge_id,'site'=>$website->id])}}">SSL</a>
    <a class="btn btn-danger" href="{{route('projects.delete.site',['server'=>$server->forge_id,'site',$website->id])}}">Delete</a>
</div>