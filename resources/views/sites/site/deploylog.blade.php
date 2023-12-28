@isset($deploy_log)
<div class=" rounded shadow my-3"> 
    <div class="console rounded" style="max-height:600px;overflow-y:scroll">
        {!!$deploy_log!!}
    </div>
</div>
@endif

@if ($website->type == 'WordPress')
@include('sites.site.wordpress')
@else

@include('sites.site.repository')
@endif
