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

  // $('form').bind('submit', function() {
  //   $(this).find(':input').prop('disabled', false);
  // });
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
</script>

<script>
  function paidConfig(invoiceId) {
    let id = invoiceId;
    let fd = new FormData();

    // Retrieve the CSRF token value from the page's meta tag
    let csrfToken = $('meta[name="csrf-token"]').attr('content');

    fd.append('id', id);
    $.ajax({
      headers: {
        'X-CSRF-TOKEN': csrfToken // Include the CSRF token in the request headers
      },
      type: "POST",
      url: `/invoice/singleData/invoiceForm`,
      cache: false,
      contentType: false,
      processData: false,
      data: fd,
      success: function(response) {
        let res = JSON.parse(response);
        $('#editModal').modal('show');
        console.log(response);
        // console.log(res.data.data[7]);
        $("#input_id").val(res.data.id);
        $("#customer").val(res.data.data6.customer);
        if (res.data.isPaid == 1) {
          $("#isPaid").addClass("bg-success").text("Paid");
          $("#verifyPayment").prop("disabled", true).text("Already Paid");
        } else {
          $("#isPaid").addClass("bg-danger").text("Not Paid");
        }
        console.log(res.data.isPiutang);
        if (res.data.isPiutang == 1) {
          $("#isPiutang").addClass("bg-warning").text("Piutang");
          $("#verifyPiutang").prop("disabled", true).text("Already Piutang");
          // $("#verifyPayment").style("display", "none");
        } else {
          $("#isPiutang").addClass("bg-danger").text("Not Piutang");
        }

        $("#verifyPayment").click(function(event) {
          Swal.fire({
            icon: 'question',
            title: 'Are You Sure?',
            text: 'You are about to do verifying this payment, and cannot be reverted!',
            showCancelButton: true,
          }).then((result) => {
            if (result.isConfirmed) {
              let fd = new FormData();
              let id = res.data.id;

              fd.append('id', id);
              $.ajax({
                headers: {
                  'X-CSRF-TOKEN': csrfToken // Include the CSRF token in the request headers
                },
                type: "POST",
                url: `/invoice/singleData/verifyPayment`,
                cache: false,
                contentType: false,
                processData: false,
                data: fd,
                success: function(response) {
                  let res = JSON.parse(response);
                  console.log(res);
                  Swal.fire({
                    icon: 'success',
                    title: 'Successfully Verify Payment!',
                    text: 'Please Check Again',
                  }).then((result) => {
                    if (result.isConfirmed) {
                      location.reload();
                    } else {
                      location.reload();

                    }
                  })
                },
                error(err) {
                  Swal.fire({
                    icon: 'error',
                    title: 'Oops!',
                    text: 'Something wrong happened! #VE42i',
                  }).then((result) => {
                    if (result.isConfirmed) {
                      location.reload();
                    } else {
                      location.reload();
                    }
                  });
                }
              });
            }
          })
        });
        $("#verifyPiutang").click(function(event) {
          Swal.fire({
            icon: 'question',
            title: 'Are You Sure?',
            text: 'You are about to do verifying Piutang this payment, and cannot be reverted!',
            showCancelButton: true,
          }).then((result) => {
            if (result.isConfirmed) {
              let fd = new FormData();
              let id = res.data.id;

              fd.append('id', id);
              $.ajax({
                headers: {
                  'X-CSRF-TOKEN': csrfToken // Include the CSRF token in the request headers
                },
                type: "POST",
                url: `/invoice/singleData/verifyPiutang`,
                cache: false,
                contentType: false,
                processData: false,
                data: fd,
                success: function(response) {
                  let res = JSON.parse(response);
                  console.log(res);
                  Swal.fire({
                    icon: 'success',
                    title: 'Successfully Verify Piutang!',
                    text: 'Please Check Again',
                  }).then((result) => {
                    if (result.isConfirmed) {
                      location.reload();
                    } else {
                      location.reload();

                    }
                  })
                },
                error(err) {
                  Swal.fire({
                    icon: 'error',
                    title: 'Oops!',
                    text: 'Something wrong happened! #VE42i',
                  }).then((result) => {
                    if (result.isConfirmed) {
                      location.reload();
                    } else {
                      location.reload();
                    }
                  });
                }
              });
            }
          })
        });

      },
      error(err) {
        Swal.fire({
          icon: 'error',
          title: 'Oops!',
          text: 'Something wrong happened! #VE42i',
        }).then((result) => {
          if (result.isConfirmed) {
            location.reload();
          } else {
            location.reload();
          }
        });
      }
    });

  }
</script><?php /**PATH D:\Fdw Files\CTOS\dev\frontend\tos-dev-local\resources\views/partial/invoice/js/js_customer.blade.php ENDPATH**/ ?>