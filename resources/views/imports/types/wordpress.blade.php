<div class="container">
<div class="row">
  <div class="col-6">
    <div class="card">
      <div class="card-body">
        <h3 class="card-title">Title</h3>
        <p class="card-text">
            <button id="#rsync" onclick="Rsync(this)" data-id="{{$import->id}}" class="btn btn-primary">Rsync files</button>
        </p>
      </div>
    </div>
  </div>
  <div class="col-6">
    <div class="card">
      <div class="card-body">
        <h3 class="card-title">SSH Key install</h3>
        <p class="card-text">Text</p>
      </div>
    </div>
  </div>
</div>
</div>

<script>
function Rsync(){
   
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
</script>