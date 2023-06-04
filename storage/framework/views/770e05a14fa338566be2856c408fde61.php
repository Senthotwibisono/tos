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

  function submitStep2Delivery(id) {
    let fd = new FormData();
    let id_params = id;
    // console.log(id_params);
    // let containers = 

  }
</script>



<script>
  function rupiahFormat(bilangan) {
    // var bilangan = 23456789;

    var number_string = bilangan.toString(),
      sisa = number_string.length % 3,
      rupiah = number_string.substr(0, sisa),
      ribuan = number_string.substr(sisa).match(/\d{3}/g);

    if (ribuan) {
      separator = sisa ? '.' : '';
      rupiah += separator + ribuan.join('.');
    }

    // Cetak hasil
    document.write(rupiah); // Hasil: 23.456.789
  }
</script><?php /**PATH D:\Fdw Files\CTOS\dev\frontend\tos-dev-local\resources\views/partial/invoice/js/js_customer.blade.php ENDPATH**/ ?>