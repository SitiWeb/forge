<table id="backups-table" class="table">
    <thead>
        <tr>
            <th>
                backup
            </th>
            <th>
                user
            </th>
            <th>
                time
            </th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="text-center" colspan="3">
                <i class="fa-solid fa-spinner fa-spin-pulse"></i>
            </td>
        </tr>
    </tbody>
</table>
<script>
    $(document).ready(function() {
        function formatTimestamp(timestamp) {
            const date = new Date(timestamp);
            return date.toLocaleString(); // Adjust the format as needed
        }
        // Define a function to make the API call and populate the table
        function fetchBackups() {
            $.ajax({
                url: '{{ env('APP_URL') }}/server/{{ $server->forge_id }}/all-backups',
                method: 'GET',
                success: function(data) {
                    // Clear existing table rows
                    $('#backups-table tbody').empty();

                    // Loop through the fetched data and add rows to the table
                    $.each(data.result, function(index, backup) {
                        console.log(backup);
                        var newRow = '<tr>' +
                            '<td>' + formatTimestamp(backup.time)+ '</td>' +
                            '<td>' + backup.site.username + '</td>' +
                            '<td>' + backup.site.name + '</td>' +
                            '</tr>';
                        $('#backups-table tbody').append(newRow);
                    });
                },
                error: function(xhr, status, error) {
                    // Handle errors here
                    console.error(error);
                }
            });
        }

        // Call the function to fetch backups and populate the table when the page loads
        fetchBackups();
    });
</script>
  