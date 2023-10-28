<!-- resources/views/create.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Create a New Project</h1>
        @include('forms.form')
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
