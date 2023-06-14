<script>
    $("#cust_add").click(function(event) {
        $('#cust_invoice').modal('show').focus();

    });

    function canceladdCustomer() {
        Swal.fire({
            icon: "question",
            title: "Are You Sure want to cancel ?",
            showCancelButton: true,
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "/invoice/customer";
            }
        })
    }

    function submitaddCustomer() {
        Swal.fire({
            icon: 'question',
            title: 'Are You Sure?',
            text: 'Please make sure you have right data before submitting',
            showCancelButton: true
        }).then((result) => {
            if (result.isConfirmed) {
                $("#customer_form").trigger("submit");
            }
        })

    }
</script><?php /**PATH D:\CTOS\Dev\Frontend\tos-dev-local\resources\views/partial/invoice/js/js_customer.blade.php ENDPATH**/ ?>