<ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link active" id="deploy-tab" data-bs-toggle="tab" data-bs-target="#deploy" type="button" role="tab" aria-controls="deploy" aria-selected="true">Deploy log</button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="console-tab" data-bs-toggle="tab" data-bs-target="#console" type="button" role="tab" aria-controls="console" aria-selected="false">Console</button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="env-tab" data-bs-toggle="tab" data-bs-target="#env" type="button" role="tab" aria-controls="env" aria-selected="false">Env</button>
    </li>
  
  </ul>
  <div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active" id="deploy" role="tabpanel" aria-labelledby="deploy-tab">@include('sites.site.deploylog')</div>
    <div class="tab-pane fade" id="console" role="tabpanel" aria-labelledby="console-tab">@include('sites.site.console')</div>
    <div class="tab-pane fade" id="env" role="tabpanel" aria-labelledby="env-tab">@include('sites.site.env')</div>
  </div>
  