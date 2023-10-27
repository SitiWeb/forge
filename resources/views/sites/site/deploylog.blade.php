@if ($deploy_log)
<div class=" rounded shadow my-3"> 
    <div class="console rounded" style="max-height:600px;overflow-y:scroll">
        {!!$deploy_log!!}
    </div>
</div>
@else
Not available
@endif