
<div class="container">
    <div class="row justify-content-center">
        <div class="col">
            <div class="card">
                <div class="card-header">{{ __('Upload File') }}</div>

                <div class="card-body">
                    <form action="{{ route('uploadFile') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="file">Choose File:</label>
                            <input type="file" class="form-control-file" name="file" id="file" accept=".txt,.pdf,.jpg,.png">
                        </div>
                        <button type="submit" class="btn btn-primary mt-2">Upload File</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

