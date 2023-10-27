<!-- resources/views/servers/index.blade.php -->

@extends('layouts.app') {{-- Assuming you have a layout --}}

@section('content')
    <div class="container">
        <div class="row ">
            <div class="col-6 ">
                <div class=" p-3 bg-white rounded shadow">
                    <h3>users</h3>
                    <div class="users-content w-100" style="max-height: 600px;overflow-y:scroll;">
                        <ul id="item-list" class="list-group">
                            <!-- List items will be added here dynamically -->
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class=" p-3 bg-white rounded shadow">
                    <div id="table-container"></div>
                    <!-- Add a button to trigger the toast -->
                    <button id="showToastBtn" class="btn btn-primary">Show Toast</button>

                    <!-- Initialize and display the toast using JavaScript -->
                    <script></script>

                </div>
            </div>
        </div>
    </div>
    <script>
        function createTable(data) {
            var table = $('<table>').addClass('table'); // Create a table element with Bootstrap class 'table'
            var tbody = $('<tbody>');

            // Loop through the 'data' object to create table rows and cells
            $.each(data, function(key, value) {
                var row = $('<tr>'); // Create a table row

                // Create table cell for the 'key' (column name)
                var keyCell = $('<td>').text(key);
                row.append(keyCell);

                // Check if the 'value' is 'ON' or 'OFF'
                if (value === 'ON' || value === 'OFF') {
                    // Create a dropdown for 'ON' and 'OFF' options
                    var valueCell = $('<td>');
                    var select = $('<select>').addClass('form-control');

                    // Create 'ON' option
                    var onOption = $('<option>').val('ON').text('ON');
                    if (value === 'ON') {
                        onOption.attr('selected', 'selected');
                    }

                    // Create 'OFF' option
                    var offOption = $('<option>').val('OFF').text('OFF');
                    if (value === 'OFF') {
                        offOption.attr('selected', 'selected');
                    }

                    // Add 'data-key' and 'data-value' attributes based on the selected option
                    select.attr('data-key', key);
                    select.attr('data-value', value);
                    select.attr('data-user', data.username);

                    // Add onchange event to the select
                    select.on('change', function() {
                        var selectedOption = $(this).find('option:selected');
                        var selectedKey = selectedOption.parent().attr('data-key');
                        var selectedValue = selectedOption.parent().attr('data-value');
                        var selectedUser = selectedOption.parent().attr('data-user');
                        console.log('Selected Key: ' + selectedKey);
                        console.log('Selected Value: ' + selectedValue);
                        console.log('Selected Value: ' + selectedUser);
                        updateOption(selectedUser,selectedKey,selectedValue);
                    });

                    select.append(onOption).append(offOption);
                    valueCell.append(select);
                    row.append(valueCell);
                } else {
                    // Create a regular table cell for other values
                    var valueCell = $('<td>').text(value);
                    row.append(valueCell);
                }

                tbody.append(row); // Append the row to the tbody
            });

            table.append(tbody); // Append the tbody to the table

            return table; // Return the generated table
        }

        function updateOption(user,option,value){
            var csrfToken = $('meta[name="csrf-token"]').attr('content'); // Get the CSRF token value
            $.ajax({
                url: "{{ route('directadmin.action', ['directadmin' => $directadmin, 'action' => 'updateOption']) }}", // Replace with your actual backend URL
                method: 'POST', // You can change the HTTP method as needed
                data: {
                    'user': user,
                    option : option,
                    value : value,
                    _token: csrfToken // Include the CSRF token in the data object
                },
                headers: {
                    'X-CSRF-TOKEN': csrfToken // Include the CSRF token in the headers
                },
                success: function(response) {
                    // Get the UL element where you want to add list items
                    if (response.error == 0){
                        showToast('success',response.text);
                    }
                    else{
                        showToast('error',response.text);
                    }
                
                }
            });
        }



        function fetchUser(e) {
            var csrfToken = $('meta[name="csrf-token"]').attr('content'); // Get the CSRF token value
            var user = $(e).data('name');
            console.log(user);

            $.ajax({
                url: "{{ route('directadmin.action', ['directadmin' => $directadmin, 'action' => 'getUser']) }}", // Replace with your actual backend URL
                method: 'POST', // You can change the HTTP method as needed
                data: {
                    'user': user,
                    _token: csrfToken // Include the CSRF token in the data object
                },
                headers: {
                    'X-CSRF-TOKEN': csrfToken // Include the CSRF token in the headers
                },
                success: function(response) {
                    // Get the UL element where you want to add list items
                    console.log(response);
                    //     var itemList = $('#item-list');

                    // // Loop through the "list" array in your JSON data
                    // $.each(response.data.list, function(index, item) {
                    //     consol
                    //     // Create a list item element
                    //     var listItem = $('<li onclick="fetchUser(this)" data-name="'+ item +'">')
                    //         .addClass('list-group-item button')
                    //         .text(item);

                    //     // Append the list item to the UL element
                    //     itemList.append(listItem);
                    // });
                    // Get the container element where you want to display the table
                    var tableContainer = $('#table-container');

                    // Generate the table and append it to the container
                    var table = createTable(response.data);
                    tableContainer.html(table);
                }
            });
        }

        function fetchUsers() {
            var csrfToken = $('meta[name="csrf-token"]').attr('content'); // Get the CSRF token value
            // Send an AJAX request to the backend
            $.ajax({
                url: "{{ route('directadmin.action', ['directadmin' => $directadmin, 'action' => 'getUsers']) }}", // Replace with your actual backend URL
                method: 'POST', // You can change the HTTP method as needed
                data: {

                    _token: csrfToken // Include the CSRF token in the data object
                },
                headers: {
                    'X-CSRF-TOKEN': csrfToken // Include the CSRF token in the headers
                },
                success: function(response) {
                    // Get the UL element where you want to add list items
                    var itemList = $('#item-list');

                    // Loop through the "list" array in your JSON data
                    $.each(response.data.list, function(index, item) {
                        // Create a list item element
                        var listItem = $('<li onclick="fetchUser(this)" data-name="' + item + '">')
                            .addClass('list-group-item button')
                            .text(item);

                        // Append the list item to the UL element
                        itemList.append(listItem);
                    });
                }
            });

        }
        $(document).ready(function() {
            // Start fetching badge data from the first badge (index 0)
            fetchUsers();
        });
    </script>
@endsection
