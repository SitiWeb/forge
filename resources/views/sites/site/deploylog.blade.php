@if ($deploy_log)
<div class=" rounded shadow my-3"> 
    <div class="console rounded" style="max-height:600px;overflow-y:scroll">
        {!!$deploy_log!!}
    </div>
</div>
@else
Deploy log Not available
@endif

@if ($website->app == 'WordPress')
<div class="wp-sso">
@include('forms.form')
</div>
<script>
$(document).ready(function() {

        // Send the POST request using Ajax
        $.ajax({
            type: 'GET',
            url: '{{env('APP_URL')}}/data/sites/admins/{{$server->forge_id}}/{{$website->id}}', // The URL you defined in your Laravel routes
            // data: postData,
            success: function(response) {
                // Handle the success response from the server
                 // Select the <select> element
                var userSelect = $('#user_select');

                // Loop through the user data and add options to the select element
                for (var i = 0; i < response.length; i++) {
                    var user = response[i];
                    // Append an <option> element with the username
                    userSelect.append($('<option>', {
                        value: user.ID, // You can use a different value if needed
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
    });

</script>
@else

@include('sites.site.repository')
@endif
