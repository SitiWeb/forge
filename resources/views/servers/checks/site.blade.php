
<div class="container">
    <form action="{{route('backups.config',['site'=>$site->site_id])}}" method="POST">
        @csrf

        <div class="form-group">
            <input type="hidden" class="form-control" id="source_directories" name="source_directories" value="{{ json_encode($site->yamlconfig ['source_directories']) }}" readonly>
        </div>

        <div class="form-group">
            <input type="hidden" class="form-control" id="site_config" name="site_config" value="{{ ($site->config_path) }}" readonly>
        </div>

        <div class="form-group">
            <input type="hidden" class="form-control" id="repositories" name="repositories" value="{{ json_encode($site->yamlconfig ['repositories']) }}" readonly>
        </div>

        <div class="form-group">
            <input type="hidden" class="form-control" id="site" name="site" value="{{ $site->site_id }}" readonly>
        </div>

        <div class="form-group">
            <input type="hidden" class="form-control" id="archive_name_format" name="archive_name_format" value="{{ $site->yamlconfig['archive_name_format'] }}" readonly>
        </div>

        <div class="form-row d-flex">
            <div class="form-group col-md-3 p-2">
                <label for="keep_daily">Daily</label>
                <input type="number" class="form-control" id="keep_daily" name="keep_daily" value="{{ $site->yamlconfig['keep_daily'] }}">
            </div>
        
            <div class="form-group col-md-3 p-2">
                <label for="keep_weekly">Weekly</label>
                <input type="number" class="form-control" id="keep_weekly" name="keep_weekly" value="{{ $site->yamlconfig['keep_weekly'] }}">
            </div>
        
            <div class="form-group col-md-3 p-2">
                <label for="keep_monthly">Monthly</label>
                <input type="number" class="form-control" id="keep_monthly" name="keep_monthly" value="{{ $site->yamlconfig['keep_monthly'] }}">
            </div>
        
        
        <div class="form-group col-md-3 align-self-end p-2">
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
        
    </div>
    </form>
</div>

