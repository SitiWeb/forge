<div id="myToast" class="toast top right position-fixed " style="z-index:2" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="toast-header">
        <strong class="mr-auto">Bootstrap Toast</strong>
        <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="toast-body">
        <!-- Toast message content goes here -->
    </div>
</div>

<script>
    function showToast(type, message, duration = 2000) {
        var toast = new bootstrap.Toast(document.getElementById('myToast'));

// Set the type of toast by adding appropriate classes
var toastContainer = document.getElementById('myToast');
toastContainer.classList.remove('bg-primary', 'bg-success', 'bg-warning', 'bg-danger');
switch (type) {
    case 'success':
        toastContainer.classList.add('bg-success');
        break;
    case 'warning':
        toastContainer.classList.add('bg-warning');
        break;
    case 'error':
        toastContainer.classList.add('bg-danger');
        break;
    default:
        toastContainer.classList.add('bg-primary');
        break;
}

// Set the toast message
var toastMessage = document.querySelector('.toast-body');
toastMessage.textContent = 'test';

toast.show();

// Automatically hide the toast after 3000 milliseconds (3 seconds)
setTimeout(function() {
    duration.hide();
}, 3000);
}
</script>