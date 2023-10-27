
<div class="accordion accordion-flush" id="accordionFlushExample">
    @foreach($deploy_history['deployments'] as $index => $deployment)
    @php
    $badge_value = $deployment['status'];
    @endphp
    <div class="accordion-item">
      <h2 class="accordion-header" id="flush-heading-{{$index}}">
        <button class="accordion-button collapsed" onclick="getDeploymentLog(this)" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse-{{$index}}" data-server-id="{{$deployment['server_id']}}"  data-site-id="{{$deployment['site_id']}}"  data-deployment-id="{{$deployment['id']}}" aria-expanded="false" aria-controls="flush-collapseOne">
          <div class="d-flex flex-row">  
          <div class="w-25" >@include('badges')</div> <div>{{$deployment['commit_message']}} - {{$deployment['commit_author']}}</div>
          </div>
        </button>
      </h2>
      <div id="flush-collapse-{{$index}}" class="accordion-collapse collapse" aria-labelledby="flush-heading-{{$index}}" data-bs-parent="#accordionFlushExample">
        <div class="accordion-body console"></div>
      </div>
    </div>
 
    @endforeach
</div>

