<!-- resources/views/servers/index.blade.php -->

@extends('layouts.app')  {{-- Assuming you have a layout --}}

@section('content')
    <div class="container">
        <div class="p-3 bg-white rounded shadow"> 
            
        <h1>Server List</h1>
        
        <table class="table">
            <thead>
                <tr>
                    <th>Host</th>
                    <th>Status</th>
                    <th>User</th>
                    <th>Delete</th>
                    {{-- Add other table headings as needed --}}
                </tr>
            </thead>
            <tbody>
          
                @foreach ($directadmins as $directadmin)
    
                    <tr>
                        <td><a href="{{route('directadmin.show',['directadmin'=>$directadmin])}}">{{ $directadmin->host }}</a></td>
                        
                        <td><span class="directadmin-status-badge badge bg-warning" data-id="{{$directadmin->id}}" data-test-url="{{route('directadmin.test',['directadmin'=>$directadmin])}}">Checking</span></td>
                        <td>{{ $directadmin->user->name }}</td>
                        <td>
                            <button class="btn btn-danger" onclick="showConfirmModal(this)" data-toggle="modal" data-destroy-url="{{route('directadmin.destroy',['directadmin'=>$directadmin])}}" data-target="#confirmDeleteModal"  data-type="user" data-id="{{ $directadmin->id }}" data-name="{{ $directadmin->host }}">
                                Destroy
                            </button>
                        </td>
                        
                        {{-- Add other table cells as needed --}}
                    </tr>
                @endforeach
            </tbody>
        </table>
        <a href="{{route('directadmin.create')}}" class="btn btn-primary">Create new directadmin</a>
    </div>
    </div>
    <script>
function fetchBadgeData(index) {
    // Select all elements with the class 'directadmin-status-badge'
    var $badges = $('.directadmin-status-badge');
    
    if (index < $badges.length) {
        var $badge = $badges.eq(index);
        var badgeData = $badge.data('id');
        var testUrl = $badge.data('test-url');
        console.log($badge);

            // Send an AJAX request to the backend
            $.ajax({
                url: testUrl, // Replace with your actual backend URL
                method: 'GET', // You can change the HTTP method as needed
                success: function(response) {
                // Handle the backend response here if needed
                if (response.status == 'success') {
                    // Check if the badge has the 'bg-warning' class
                    if ($badge.hasClass('bg-warning')) {
                        // Remove 'bg-warning' class and add 'bg-success' class
                        $badge.removeClass('bg-warning').addClass('bg-success');
                        
                        // Update the badge text if needed
                        $badge.text('Success'); // Change the text to 'Success' or any other value
                    }
                } else {
                    if ($badge.hasClass('bg-warning')) {
                        // Remove 'bg-warning' class and add 'bg-danger' class
                        $badge.removeClass('bg-warning').addClass('bg-danger');
                        
                        // Update the badge text if needed
                        $badge.text('Failed'); // Change the text to 'Failed' or any other value
                    }
                }
                
                // Continue fetching data for the next badge
                fetchBadgeData(index + 1);
            },
            error: function(xhr, status, error) {
                // Handle errors here if the request fails
                console.error(error);
                
                // Continue fetching data for the next badge
                fetchBadgeData(index + 1);
            }
        });
    }
}

$(document).ready(function() {
    // Start fetching badge data from the first badge (index 0)
    fetchBadgeData(0);
});

    </script>
    
@endsection
