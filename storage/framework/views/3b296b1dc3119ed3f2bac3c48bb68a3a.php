<script>
    $("#cust_add").click(function(event) {
        $('#cust_invoice').modal('show').focus();

    });

    function canceladdCustomer() {
        Swal.fire({
            icon: "question",
            title: "Are You Sure want to cancel ?",
            showCancelButton: true,
        })
    }
    $("#btn_submit_cust").click(function(event) {

    });
    // $("#btn_cancel_cust").click(function(event) {

    // });
</script><?php /**PATH D:\Fdw Files\CTOS\dev\frontend\tos-dev-local\resources\views/partial/invoice/js/js_modal.blade.php ENDPATH**/ ?>