<!-- resources/views/create.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Create an import</h1>
        @include('errors')
        <form method="POST" action="{{ route('imports.store') }}">
            @csrf
           
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>

            <div class="form-group">
                <label for="directadminserver">Choose directadminserver:</label>
                <select onchange="fetchUsers(this)"  class="form-control" id="directadminserver" name="directadminserver" required>
                    <option value="">Select server</option>
                    @foreach ($directadmins as $key => $directadmin)
                        <option value="{{ $directadmin->id }}">{{ $directadmin->host }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="directadminUser">Choose user:</label>
                <select class="form-control" id="directadminUser" name="directadminUser" required>
                </select>
            </div>

            <div class="form-group">
                <label for="server">Choose server:</label>
                <select class="form-control" onchange="fetchSites(this)" id="server" name="server" required>
                    <option value="">Select server</option>
                    @foreach ($servers as $key => $server)
                        <option value="{{$server->id}}">{{ $server->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="site">Choose site:</label>
                <select class="form-control" id="site" name="site" required>
                </select>
            </div>

            

            <button type="submit" class="btn btn-primary mt-3">Create Import</button>
        </form>
    </div>
    <script>
     function fetchUsers($serverId) {
            if ($serverId == ''){
                return;
            }
            var csrfToken = $('meta[name="csrf-token"]').attr('content'); // Get the CSRF token value
            var url = '{{env('APP_URL')}}/data/action/'+$($serverId).val()+'/getUsers';
            // Send an AJAX request to the backend
            $.ajax({
                url: url, // Replace with your actual backend URL
                method: 'POST', // You can change the HTTP method as needed
                data: {

                    _token: csrfToken // Include the CSRF token in the data object
                },
                headers: {
                    'X-CSRF-TOKEN': csrfToken // Include the CSRF token in the headers
                },
                success: function(response) {
                    console.log(response);
                    // Get the UL element where you want to add list items
                    var itemList = $('#directadminUser');
                    itemList = $('#directadminUser').html('<label for="site">Choose server:</label>');

                    // Loop through the "list" array in your JSON data
                    $.each(response.data.list, function(index, item) {
                        // Create a list item element
                        console.log(item);
                        var listItem = $('<option value="' + item + '">'+item+'</option>')
                         

                        // Append the list item to the UL element
                        itemList.append(listItem);
                    });
                }
            });

        }

        function fetchSites($serverId) {
            if ($serverId == ''){
                return;
            }
            var csrfToken = $('meta[name="csrf-token"]').attr('content'); // Get the CSRF token value
            var url = '{{env('APP_URL')}}/data/server/sites/'+$($serverId).val();
            // Send an AJAX request to the backend
            $.ajax({
                url: url, // Replace with your actual backend URL
                method: 'GET', // You can change the HTTP method as needed
            
                headers: {
                    'X-CSRF-TOKEN': csrfToken // Include the CSRF token in the headers
                },
                success: function(response) {
                   
                    // Get the UL element where you want to add list items
                    var itemList = $('#site');
                    itemList = $('#site').html('<label for="site">Choose site:</label>');

                    // Loop through the "list" array in your JSON data
                    $.each(response, function(index, item) {
                        // Create a list item element
                       
                        var listItem = $('<option value="' + item.id + '">'+item.name+'</option>')
                         

                        // Append the list item to the UL element
                        itemList.append(listItem);
                    });
                }
            });

        }
        </script>
@endsection
