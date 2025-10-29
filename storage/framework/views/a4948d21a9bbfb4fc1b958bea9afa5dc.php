<!-- Footer Start -->
<div class="footer d-flex align-items-center justify-content-between flex-column flex-sm-row row-gap-2 border-top py-2 px-3">
    <p class="text-dark mb-0">2025 &copy; <a href="javascript:void(0);" class="link-primary">eSchool</a>, All Rights Reserved</p>
    <p class="text-dark mb-0">Design & Developed by <a href="#" target="_blank" class="link-primary">Gugun & Harsh</a></p>
</div>
<!-- Footer End -->

<!-- ========================
    End Page Content
========================= -->

<!-- Start Modal for delete -->
<div class="modal fade" id="delete_modal">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-body text-center">
                <div class="mb-3">
                    <span class="avatar bg-danger"><i class="ti ti-trash fs-24"></i></span>
                </div>
                <h6 class="mb-1">Delete Confirmation</h6>
                <p class="mb-3">Are you sure you want to delete this record?</p>
                <div class="d-flex justify-content-center">
                    <a href="javascript:void(0);" class="btn btn-outline-white w-100 me-2" data-bs-dismiss="modal">Cancel</a>
                    <form id="deleteForm" method="POST" class="w-100">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-danger w-100">Yes, Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
function confirmDelete(url) {
    let form = document.getElementById('deleteForm');
    form.action = url; // set the form action dynamically
    let modal = new bootstrap.Modal(document.getElementById('delete_modal'));
    modal.show();
}
</script>
<!-- End Modal -->
<?php /**PATH F:\Github\eschool\resources\views////elements/student/footer.blade.php ENDPATH**/ ?>