<?php
use App\Http\Controllers\CronController;
?>
<div class="row my-2">
    <div class="col-6">
<div class="card wp-sso">
    <div class="card-body">
    @include('forms.form')
    <small id="loadUsers">sync</small>
</div>
</div>
</div>
<div class="col-6">
<div class=" card wp-cron">
    
    <div class="card-body">
        <div class="card-title">
            WP Cron
        </div>

        <?php
        $cron = new CronController();
        $result = $cron->hasWPCron($website->site_id);
        if ($result){
            echo '<a href="'.route('wpcron.deletecron',['site'=>$website->site_id]).'" class="btn btn-danger">Disable WP CRON</a>';
        }
        else{
            echo '<a href="'.route('wpcron.createcron',['site'=>$website->site_id]).'" class="btn btn-success">Enable WP CRON</a>';
        }
        ?>
    </div>
</div>
</div>
</div>
<script>
    $(document).ready(function() {

        // Select the <select> element
        var userSelect = $('#user_select');

        // Check if the <select> element is empty
        if (userSelect.children().length === 0) {
            syncUsers();
        }
        $('#loadUsers').on('click', function() {
            syncUsers();
        });
    });

    function syncUsers() {
        // Send the GET request using Ajax
        var userSelect = $('#user_select');
        $.ajax({
            type: 'GET',
            url: '{{ env('APP_URL') }}/data/sites/admins/{{ $website->server->forge_id }}/{{ $website->site_id }}',
            success: function(response) {
                // Handle the success response from the server
                // Clear any existing options in the select element
                userSelect.empty();

                // Loop through the user data and add options to the select element
                for (var i = 0; i < response.length; i++) {
                    var user = response[i];
                    // Append an <option> element with the username
                    userSelect.append($('<option>', {
                        value: user.ID,
                        text: user.user_login
                    }));
                }
                console.log(response);
            },
            error: function(error) {
                // Handle any errors that occur during the request
                console.error(error);
            }
        });
    }
</script>
