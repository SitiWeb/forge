<!-- resources/views/create.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Create a New Project</h1>
        <form method="POST" action="{{ route('projects.store') }}">
            @csrf
            <input type="hidden" class="form-control" id="server" name="server" value="{{$server->forge_id}}" required>
            <div class="form-group">
                <label for="domain">Domain:</label>
                <input type="text" class="form-control" id="domain" name="domain" required>
            </div>

            <div class="form-group">
                <label for="project_type">Project Type:</label>
                <select class="form-control" id="project_type" name="project_type" required>
                    @foreach ($availableProjectTypes as $key => $description)
                        <option value="{{ $key }}">{{ $description }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="aliases">Aliases (optioneel):</label>
                <input type="text" class="form-control" id="aliases" name="aliases">
            </div>

            <div class="form-group ">
                <label for="directory">Directory:</label>
                <input type="text" class="form-control" id="directory" name="directory"  value="/public" required>
            </div>

            <div class="form-check pt-2">
                <input type="checkbox" class="form-check-input" id="isolated" name="isolated" checked>
                <label class="form-check-label" for="isolated">Isolated</label>
            </div>

            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>

            <div class="form-group">
                <label for="database">Database:</label>
                <input type="text" class="form-control" id="database" name="database" required>
            </div>

            <div class="form-group">
                <label for="php_version">PHP Version:</label>
                <select class="form-control" id="php_version" name="php_version" required>
                    @foreach ($availablePHP as $key => $php)
                        @if($key =='php82' )
                        <option value="{{ $key }}" selected>{{ $php }}</option>
                        @else
                        <option value="{{ $key }}" >{{ $php }}</option>
                        @endif
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="nginx_template">Nginx Template:</label>
                <select class="form-control" id="nginx_template" name="nginx_template" required>
                <option value="default" selected>default</option>
                @foreach($templates as $template)
                    
                    <option value="{{ $template->id }}" selected>{{ $template->name }}</option>
                @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary mt-3">Create Site</button>
        </form>
    </div>
    <script>
        $(document).ready(function() {
            // Get references to the input fields
            var $sourceField = $('#domain');
            var $targetField1 = $('#username');
            var $targetField2 = $('#database');
        
            // Listen for changes in the source field
            $sourceField.on('input', function() {
                // Get the value of the source field
                var inputValue = $sourceField.val();
        
                // Remove characters that are not numbers or letters using regex
                var cleanedValue = inputValue.replace(/[^a-zA-Z0-9]/g, '');
        
                // Set the cleaned value to the target fields
                $targetField1.val(cleanedValue);
                $targetField2.val(cleanedValue);
            });
        });
        </script>
@endsection
