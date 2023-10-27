<!-- Confirmation Modal -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Confirm Deletion</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"  onclick="closeConfirmModal()">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the <span id="typeToDelete"></span> "<span id="userNameToDelete"></span>"?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeConfirmModal()" data-dismiss="modal">Cancel</button>
                <!-- "Destroy" button within the modal -->
                <form id="deleteUserForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Destroy</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function showConfirmModal(e){
        var button = $(e); // Button that triggered the modal
        var userId = button.data('id'); // Extract data-id attribute from the button
        var userName = button.data('name'); // Extract data-name attribute from the button
        var type = button.data('type'); // Extract data-name attribute from the button
        var url = button.data('destroy-url'); // Extract data-name attribute from the button
        var modal = $('#confirmDeleteModal');
        console.log(modal.modal('show'));
        // Set data attributes in the modal
        modal.find('#deleteUserForm').attr('action', url);
        modal.find('#userNameToDelete').text(userName);
        modal.find('#typeToDelete').text(type);
    }
    function closeConfirmModal(e){
        var modal = $('#confirmDeleteModal');
        modal.modal('hide')
    }
</script>