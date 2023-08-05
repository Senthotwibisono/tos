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
        // console.log(response);
        console.log(res.data.invoice.isPiutang);
        $("#input_id").val(res.data.invoice.id);
        $("#customer").val(res.data.invoice.data6.customer);
        if (res.data.invoice.isPaid == 1 && res.data.invoice.isPiutang == 0) {
          $("#isPaid").addClass("bg-success").text("Paid");
          $("#isPiutang").addClass("bg-danger").text("Not Piutang");
          $("#verifyPiutang").prop("disabled", true).text("Already Paid");
          $("#verifyPayment").prop("disabled", true).text("Already Paid");
        } else if (res.data.invoice.isPiutang == 1 && res.data.invoice.isPaid == 0) {
          $("#isPiutang").addClass("bg-warning").text("Piutang");
          $("#isPaid").addClass("bg-danger").text("Not Paid");
          $("#verifyPiutang").prop("disabled", true).text("Already Piutang");
          $("#verifyPayment").prop("disabled", false).text("Verify This Payment");
        } else if (res.data.invoice.isPiutang == 1 && res.data.invoice.isPaid == 1) {
          $("#verifyPiutang").prop("disabled", true).text("Already Piutang");
          $("#isPiutang").addClass("bg-warning").text("Piutang");
          $("#verifyPayment").prop("disabled", true).text("Already Paid");
          $("#isPaid").addClass("bg-success").text("Paid");
        } else {
          $("#isPiutang").addClass("bg-danger").text("Not Piutang");
          $("#isPaid").addClass("bg-danger").text("Not Paid");
          $("#verifyPiutang").prop("disabled", false).text("Piutang This Invoice");
          $("#verifyPaid").prop("disabled", false).text("Verify This Payment");
        }
        // console.log(res.data.invoice.isPiutang);
        // if (res.data.invoice.isPiutang == 1) {
        //   $("#isPiutang").addClass("bg-warning").text("Piutang");
        //   $("#verifyPiutang").prop("disabled", true).text("Already Piutang");
        //   // $("#verifyPayment").style("display", "none");
        // } else {
        //   $("#isPiutang").addClass("bg-danger").text("Not Piutang");
        //   $("#verifyPiutang").prop("disabled", false).text("Piutang This Invoice");
        // }

        $("#verifyPayment").click(function(event) {
          Swal.fire({
            icon: 'question',
            title: 'Are You Sure?',
            text: 'You are about to do verifying this payment, and cannot be reverted!',
            showCancelButton: true,
          }).then((result) => {
            if (result.isConfirmed) {
              let fd = new FormData();
              let id = res.data.invoice.id;

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
              let id = res.data.invoice.id;

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
</script>

<script>
  function tarifConfig(tarifId) {
    let id = tarifId;
    let fd = new FormData();

    // Retrieve the CSRF token value from the page's meta tag
    let csrfToken = $('meta[name="csrf-token"]').attr('content');
    fd.append('id', id);
    $.ajax({
      headers: {
        'X-CSRF-TOKEN': csrfToken // Include the CSRF token in the request headers
      },
      type: "POST",
      url: `/invoice/singleData/mastertarif`,
      cache: false,
      contentType: false,
      processData: false,
      data: fd,
      success: function(response) {
        let res = JSON.parse(response);
        // console.log(res.data.lokasi_sandar);
        $('#editModalTarif').modal('show');
        $("#id").val(res.data.id);
        $("#Lokasi_Sandar").val(res.data.lokasi_sandar);
        $("#Type").val(res.data.type);
        $("#Size").val(res.data.size);
        $("#Status").val(res.data.status);
        $("#Masa_1").val(res.data.masa1);
        $("#Masa_2").val(res.data.masa2);
        $("#Masa_3").val(res.data.masa3);
        $("#Masa_4").val(res.data.masa4);
        $("#Lift_On").val(res.data.lift_on);
        $("#Lift_Off").val(res.data.lift_off);
        $("#Pass_Truck").val(res.data.pass_truck);
        $("#Gate_Pass_Admin").val(res.data.gate_pass_admin);
        $("#Cost_Recovery").val(res.data.cost_recovery);
        $("#Surcharge").val(res.data.surcharge);
        $("#Packet_PLP").val(res.data.packet_plp);
        $("#Behandle").val(res.data.behandle);
        $("#Recooling").val(res.data.recooling);
        $("#Monitoring").val(res.data.monitoring);
        $("#Administrasi").val(res.data.administrasi);

        $("#editSubmit").click(function(event) {
          Swal.fire({
            icon: 'question',
            title: 'Are You Sure?',
            text: 'You are about to update the data!',
            showCancelButton: true,
          }).then((result) => {
            if (result.isConfirmed) {
              // Validate each field before submitting
              let fields = [{
                  selector: "#Lokasi_Sandar",
                  name: "Lokasi Sandar"
                },
                {
                  selector: "#Type",
                  name: "Type"
                },
                {
                  selector: "#Size",
                  name: "Size"
                },
                {
                  selector: "#Status",
                  name: "Status"
                },
                {
                  selector: "#Masa_1",
                  name: "Masa 1"
                },
                {
                  selector: "#Masa_2",
                  name: "Masa 2"
                },
                {
                  selector: "#Masa_3",
                  name: "Masa 3"
                },
                {
                  selector: "#Masa_4",
                  name: "Masa 4"
                },
                {
                  selector: "#Lift_On",
                  name: "Lift On"
                },
                {
                  selector: "#Lift_Off",
                  name: "Lift Off"
                },
                {
                  selector: "#Pass_Truck",
                  name: "Pass Truck"
                },
                {
                  selector: "#Gate_Pass_Admin",
                  name: "Gate Pass Admin"
                },
                {
                  selector: "#Cost_Recovery",
                  name: "Cost Recovery"
                },
                {
                  selector: "#Surcharge",
                  name: "Surcharge"
                },
                {
                  selector: "#Packet_PLP",
                  name: "Packet PLP"
                },
                {
                  selector: "#Behandle",
                  name: "Behandle"
                },
                {
                  selector: "#Recooling",
                  name: "Recooling"
                },
                {
                  selector: "#Monitoring",
                  name: "Monitoring"
                },
                {
                  selector: "#Administrasi",
                  name: "Administrasi"
                }
              ];

              for (let i = 0; i < fields.length; i++) {
                let value = $(fields[i].selector).val();
                if (value.trim() === "") {
                  Swal.fire({
                    icon: 'error',
                    title: 'Validation Error!',
                    text: fields[i].name + ' cannot be empty!',
                  });
                  return; // Stop execution if any field is empty
                }
              }

              let fd = new FormData();
              let id = res.data.id;
              let lokasi_sandar = $("#Lokasi_Sandar").val();
              let type = $("#Type").val();
              let size = $("#Size").val();
              let status = $("#Status").val();
              let masa1 = $("#Masa_1").val();
              let masa2 = $("#Masa_2").val();
              let masa3 = $("#Masa_3").val();
              let masa4 = $("#Masa_4").val();
              let lift_on = $("#Lift_On").val();
              let lift_off = $("#Lift_Off").val();
              let pass_truck = $("#Pass_Truck").val();
              let gate_pass_admin = $("#Gate_Pass_Admin").val();
              let cost_recovery = $("#Cost_Recovery").val();
              let surcharge = $("#Surcharge").val();
              let packet_plp = $("#Packet_PPLP").val();
              let behandle = $("#Behandle").val();
              let recooling = $("#Recooling").val();
              let monitoring = $("#Monitoring").val();
              let administrasi = $("#Administrasi").val();

              fd.append('id', id);
              fd.append('lokasi_sandar', lokasi_sandar)
              fd.append('type', type)
              fd.append('size', size)
              fd.append('status', status)
              fd.append('masa1', masa1)
              fd.append('masa2', masa2)
              fd.append('masa3', masa3)
              fd.append('masa4', masa4)
              fd.append('lift_on', lift_on)
              fd.append('lift_off', lift_off)
              fd.append('pass_truck', pass_truck)
              fd.append('gate_pass_admin', gate_pass_admin)
              fd.append('cost_recovery', cost_recovery)
              fd.append('surcharge', surcharge)
              fd.append('packet_plp', packet_plp)
              fd.append('behandle', behandle)
              fd.append('recooling', recooling)
              fd.append('monitoring', monitoring)
              fd.append('administrasi', administrasi)
              $.ajax({
                headers: {
                  'X-CSRF-TOKEN': csrfToken // Include the CSRF token in the request headers
                },
                type: "POST",
                url: `/invoice/singleData/updateMasterTarif`,
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

      }
    })
  }
</script>

<script>
  function createTarif() {
    $('#createModalTarif').modal('show');
    $("#createSubmit").click(function(event) {

      // Retrieve the CSRF token value from the page's meta tag
      let csrfToken = $('meta[name="csrf-token"]').attr('content');
      Swal.fire({
        icon: 'question',
        title: 'Are You Sure?',
        text: 'You are about to do updating data!',
        showCancelButton: true,
      }).then((result) => {
        if (result.isConfirmed) {
          let fd = new FormData();
          let id = res.data.id;
          let lokasi_sandar = $("#Lokasi_Sandar").val();
          let type = $("#Type").val();
          let size = $("#Size").val();
          let status = $("#Status").val();
          let masa1 = $("#Masa_1").val();
          let masa2 = $("#Masa_2").val();
          let masa3 = $("#Masa_3").val();
          let masa4 = $("#Masa_4").val();
          let lift_on = $("#Lift_On").val();
          let lift_off = $("#Lift_Off").val();
          let pass_truck = $("#Pass_Truck").val();
          let gate_pass_admin = $("#Gate_Pass_Admin").val();
          let cost_recovery = $("#Cost_Recovery").val();
          let surcharge = $("#Surcharge").val();
          let packet_plp = $("#Packet_PPLP").val();
          let behandle = $("#Behandle").val();
          let recooling = $("#Recooling").val();
          let monitoring = $("#Monitoring").val();
          let administrasi = $("#Administrasi").val();

          fd.append('id', id);
          fd.append('lokasi_sandar', lokasi_sandar)
          fd.append('type', type)
          fd.append('size', size)
          fd.append('status', status)
          fd.append('masa1', masa1)
          fd.append('masa2', masa2)
          fd.append('masa3', masa3)
          fd.append('masa4', masa4)
          fd.append('lift_on', lift_on)
          fd.append('lift_off', lift_off)
          fd.append('pass_truck', pass_truck)
          fd.append('gate_pass_admin', gate_pass_admin)
          fd.append('cost_recovery', cost_recovery)
          fd.append('surcharge', surcharge)
          fd.append('packet_plp', packet_plp)
          fd.append('behandle', behandle)
          fd.append('recooling', recooling)
          fd.append('monitoring', monitoring)
          fd.append('administrasi', administrasi)
          $.ajax({
            headers: {
              'X-CSRF-TOKEN': csrfToken // Include the CSRF token in the request headers
            },
            type: "POST",
            url: `/invoice/singleData/createMasterTarif`,
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
  }
</script>

<script>
  function methodpaymentConfig(paymentId) {
    let id = paymentId;
    let fd = new FormData();

    // Retrieve the CSRF token value from the page's meta tag
    let csrfToken = $('meta[name="csrf-token"]').attr('content');
    fd.append('id', id);
    $.ajax({
      headers: {
        'X-CSRF-TOKEN': csrfToken // Include the CSRF token in the request headers
      },
      type: "POST",
      url: `/invoice/payment/singleData/paymentmethod`,
      cache: false,
      contentType: false,
      processData: false,
      data: fd,
      success: function(response) {
        let res = JSON.parse(response);
        console.log(res);
        $('#editModalPayment').modal('show');
        $("#input_id").val(res.data.id);
        $("#name").val(res.data.name);
        $("#bankName").val(res.data.bank);
        $("#bankCode").val(res.data.bankCode);
        $("#bankNumber").val(res.data.bankNumber);
        $("#status").val(res.data.isActive);

        $("#editSubmitPayment").click(function(event) {
          Swal.fire({
            icon: 'question',
            title: 'Are You Sure?',
            text: 'You are about to update the data!',
            showCancelButton: true,
          }).then((result) => {
            if (result.isConfirmed) {
              // console.log(status);
              let fd = new FormData();
              let id = res.data.id;
              let name = $("#name").val();
              let bankName = $("#bankName").val();
              let bankCode = $("#bankCode").val();
              let bankNumber = $("#bankNumber").val();
              // let is = res.data.is;

              fd.append('id', id);
              fd.append('name', name);
              fd.append('bankCode', bankCode);
              fd.append('bankNumber', bankNumber);
              fd.append('bankName', bankName);
              // fd.append('isActive', status);

              $.ajax({
                headers: {
                  'X-CSRF-TOKEN': csrfToken // Include the CSRF token in the request headers
                },
                type: "POST",
                url: `/invoice/payment/singleData/updatePaymentMethod`,
                cache: false,
                contentType: false,
                processData: false,
                data: fd,
                success: function(response) {
                  let res = JSON.parse(response);
                  console.log(res);
                  Swal.fire({
                    icon: 'success',
                    title: 'Successfully Updated Data!',
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
                    text: 'Something wrong happened! #CSE15',
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

      }
    })
  }
</script>

<script>
  function createPaymentMethod() {
    // $('#createModalPayment').modal('show');
    Swal.fire({
      icon: 'warning',
      title: 'Mohon Maaf!',
      text: 'Saat ini hanya bisa menampilkan 1 data saja!'
    })
  }

  $(document).on('click', '#createSubmitPayment', function(event) {
    // Retrieve the CSRF token value from the page's meta tag
    let csrfToken = $('meta[name="csrf-token"]').attr('content');
    Swal.fire({
      icon: 'question',
      title: 'Are You Sure?',
      showCancelButton: true,
    }).then((result) => {
      if (result.isConfirmed) {
        let fd = new FormData();
        let name = $("#paymentname").val();
        let bankCode = $("#paymentbankCode").val();
        let bankNumber = $("#paymentbankNumber").val();
        let bankName = $("#paymentbankName").val();
        console.log(name);
        fd.append('name', name);
        fd.append('bankCode', bankCode);
        fd.append('bankNumber', bankNumber);
        fd.append('bankName', bankName);

        $.ajax({
          headers: {
            'X-CSRF-TOKEN': csrfToken // Include the CSRF token in the request headers
          },
          type: "POST",
          url: `/invoice/payment/singleData/createPaymentMethod`,
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
            });
          },
          error: function(err) {
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
    });
  });
</script>

<script>
  $('#submit').click(function() {
    // console.log("clicked");
    let date = $('#date').val();
    console.log(date);
  })
</script><?php /**PATH E:\Fdw File Storage 1\CTOS\dev\frontend\tos-dev-local\resources\views/partial/invoice/js/js_customer.blade.php ENDPATH**/ ?>