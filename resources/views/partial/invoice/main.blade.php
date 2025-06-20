<!DOCTYPE html>
<html lang="en">

<head>
  @include('partial.invoice.header')

</head>

<body>
  <div id="app">
    <div id="main" class="layout-horizontal">
      <header class="mb-5">
        <div class="header-top">
          <div class="container">
            <div class="logo">
              <a href="/"><img class="logoicon" src="{{asset('logo/ICON2.png')}}" alt="Logo"></a>
            </div>
            <div class="header-top-right">

              @include('partial.invoice.authheader')

              <!-- Burger button responsive -->
              <a href="#" class="burger-btn d-block d-xl-none">
                <i class="bi bi-justify fs-3"></i>
              </a>
            </div>
          </div>
        </div>
        <div class="container-fluid px-0">
          <nav class="main-navbar w-100">
            @include('partial.invoice.navbar')
          </nav>
        </div>

      </header>

        <div class="content-wrapper container" style="width: 100%; max-width: 1500px; margin: 0 auto;">
          @if(session('success'))
              <div class="alert alert-success">
                  {{ session('success') }}
              </div>
          @endif
          @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
          @endif
        @yield('content')
      </div>
    </div>
  </div>
  <script src="{{asset('dist/assets/js/bootstrap.js')}}"></script>
  <script src="{{asset('dist/assets/js/app.js')}}"></script>
  <script src="{{asset('dist/assets/js/pages/horizontal-layout.js')}}"></script>
  <script src="{{asset('dist/assets/extensions/apexcharts/apexcharts.min.js')}}"></script>
  <script src="{{asset('dist/assets/js/pages/dashboard.js')}}"></script>
  <script src="{{asset('fontawesome/js/all.js')}}"></script>
  <script src="{{asset('fontawesome/js/all.min.js')}}"></script>
  <script src="{{asset('dist/assets/extensions/simple-datatables/umd/simple-datatables.js')}}"></script>
  <script src="{{asset('dist/assets/js/pages/simple-datatables.js')}}"></script>
  <script src="{{ asset('vendor/components/jquery/jquery.min.js') }}"></script>
  <script src="{{asset('dist/assets/extensions/sweetalert2/sweetalert2.min.js')}}"></script>
  <script src="{{asset('dist/assets/js/pages/sweetalert2.js')}}"></script>
  <script src="{{asset('dist/assets/extensions/choices.js/public/assets/scripts/choices.js')}}"></script>
  <script src="{{asset('dist/assets/js/pages/form-element-select.js')}}"></script>
  <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
  <script>
    new simpleDatatables.DataTable('#table1bc');
  </script>
  <script>
    new simpleDatatables.DataTable('#table1pea');
  </script>
  <script>
    new simpleDatatables.DataTable('#table2');
  </script>
  <script>
    new simpleDatatables.DataTable('#table2Pea');
  </script>
  <script>
    new simpleDatatables.DataTable('#table2pkbe');
  </script>
  <script>
    new simpleDatatables.DataTable('#table3');
    new simpleDatatables.DataTable('#table4');
    new simpleDatatables.DataTable('#table5');
    new simpleDatatables.DataTable('#table6');
    new simpleDatatables.DataTable('#table7');
    new simpleDatatables.DataTable('#table8');
    new simpleDatatables.DataTable('#table9');
    new simpleDatatables.DataTable('#table10');
  </script>
  <script>
    new simpleDatatables.DataTable('#tableLain');
  </script>
  <script src="{{ asset('select2/dist/js/select2.min.js') }}"></script>
  <!-- flatpickr js -->
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

  <!-- Dropify Inject -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js" integrity="sha512-8QFTrG0oeOiyWo/VM9Y8kgxdlCryqhIxVeRpWSezdRRAvarxVtwLnGroJgnVW9/XBRduxO/z1GblzPrMQoeuew==" crossorigin="anonymous"></script>

  <!-- date-range-picker  -->
  <script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>

  <!-- moment.js  -->
  <script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>


  @yield('custom_js')

  @if (\Session::has('success'))
  <script type="text/javascript">
    // Add CSRF token to the headers
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    var successMessage = "{!! \Session::get('success') !!}";

    if (successMessage) {
      Swal.fire({
        icon: 'success',
        title: 'Success',
        text: successMessage,
      }).then(function() {
        // Make an AJAX request to unset session variable
        $.ajax({
          url: "{{ route('unset-session', ['key' => 'success']) }}",
          type: 'POST',
          success: function(response) {
            console.log('Success session unset');
            // {{logger('Success session unset')}} -> call func logger in helper
          },
          error: function(error) {
            console.log('Error unsetting session', error);
          }
        });
      });
    }
  </script>
  @endif

  @if (\Session::has('error'))
  <script type="text/javascript">
    // Add CSRF token to the headers
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    var successMessage = "{!! \Session::get('error') !!}";

    if (successMessage) {
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: successMessage,
      }).then(function() {
        // Make an AJAX request to unset session variable
        $.ajax({
          url: "{{ route('unset-session', ['key' => 'success']) }}",
          type: 'POST',
          success: function(response) {
            console.log('Success session unset');
            // {{logger('Success session unset')}} -> call func logger in helper
          },
          error: function(error) {
            console.log('Error unsetting session', error);
          }
        });
      });
    }
  </script>
  @endif

</body>

<script>
  $(document).ready(function() {
    $('.js-example-basic-single').select2();
    $('.js-example-basic-multiple').select2();
    flatpickr('#expired', {
      "minDate": null
    });
    flatpickr('.expired', {
      "minDate": null
    });
    flatpickr('#doexpired', {
      "minDate": new Date()
    });
    flatpickr('#hour', {
      enableTime: true,
      dateFormat: 'd-m-Y h:i K'
    });
  });
</script>
<script>
  $(document).ready(function() {
    $('table.display').DataTable();
  });
</script>

<script>
  function formatRupiah(angka, prefix) {
    var number_string = angka.replace(/[^,\d]/g, '').toString(),
      split = number_string.split(','),
      sisa = split[0].length % 3,
      rupiah = split[0].substr(0, sisa),
      ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    // tambahkan titik jika yang di input sudah menjadi angka ribuan
    if (ribuan) {
      separator = sisa ? '.' : '';
      rupiah += separator + ribuan.join('.');
    }

    rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
    return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
  }
</script>

<script>
  $('.dropify').dropify({
    messages: {
      'default': 'Drag and drop a file here or click',
      'replace': 'Drag and drop or click to replace',
      'remove': 'Remove',
      'error': 'Ooops, something wrong happended.'
    }
  });
</script>
<script>
  $("#manual").click(function() {
    // console.log("manual!");
    $("#do_manual").css("display", "block");
    $("#do_auto").css("display", "none");
    $("#auto").css("opacity", "50%");
    $("#manual").css("opacity", "100%");

  })
  $("#auto").click(function() {
    // console.log("auto!");
    $("#do_auto").css("display", "block");
    $("#do_manual").css("display", "none");
    $("#auto").css("opacity", "100%");
    $("#manual").css("opacity", "50%");

  })

  $("#nondomestic").click(function() {
    $("#beacukaiForm").css("display", "flex");
    $("#domestic").css("opacity", "50%");
    $("#nondomestic").css("opacity", "100%");
    $("#documentNumber").attr("required", true);
    $("#documentType").attr("required", true);
    $("#documentDate").attr("required", true);
    $("#beacukaiChecking").val("false");
  })
  $("#domestic").click(function() {
    $("#beacukaiForm").css("display", "none");
    $("#nondomestic").css("opacity", "50%");
    $("#domestic").css("opacity", "100%");
    $("#documentNumber").attr("required", false);
    $("#documentType").attr("required", false);
    $("#documentDate").attr("required", false);
    $("#beacukaiChecking").val("true");
  })

</script>

<script>
    function createLoading() {
        let loadingOverlay = document.createElement("div");
        loadingOverlay.id = "loadingOverlay";
        loadingOverlay.style.position = "fixed";
        loadingOverlay.style.top = "0";
        loadingOverlay.style.left = "0";
        loadingOverlay.style.width = "100%";
        loadingOverlay.style.height = "100%";
        loadingOverlay.style.background = "rgba(0, 0, 0, 0.5)";
        loadingOverlay.style.display = "flex";
        loadingOverlay.style.justifyContent = "center";
        loadingOverlay.style.alignItems = "center";
        loadingOverlay.style.zIndex = "9999";
        let spinner = document.createElement("div");
        spinner.style.width = "50px";
        spinner.style.height = "50px";
        spinner.style.border = "5px solid #f3f3f3";
        spinner.style.borderTop = "5px solid #3498db";
        spinner.style.borderRadius = "50%";
        spinner.style.animation = "spin 1s linear infinite";
        // Tambahkan animasi CSS ke dalam JavaScript
        let style = document.createElement("style");
        style.innerHTML = `
            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
        `;
        document.head.appendChild(style);
        loadingOverlay.appendChild(spinner);
        document.body.appendChild(loadingOverlay);
    }
    function showLoading() {
        if (!document.getElementById("loadingOverlay")) {
            createLoading();
        }
    }
    function hideLoading() {
        let loadingOverlay = document.getElementById("loadingOverlay");
        if (loadingOverlay) {
            loadingOverlay.remove();
        }
    }
</script>

<script>
    let loadingHidden = false;

    window.addEventListener("beforeunload", function () {
        showLoading();
        loadingHidden = false; // reset flag jika mau pindah halaman
    });

    // Tampilkan loading saat halaman mulai dimuat
    showLoading();

    // Sembunyikan loading setelah halaman benar-benar termuat
    window.onload = function () {
        hideLoading();
    };

    window.addEventListener("pageshow", function (event) {
        if (event.persisted) {
            hideLoading();
        }
    });
    
</script>

<script>
    async function errorResponse(response) {
        Swal.fire({
            icon: 'error',
            title: response.status,
            text: response.statusText,
        }).then(() => {
            showLoading();
            location.reload();
        });
    }
    async function errorHasil(hasil) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: hasil.message,
        }).then(() => {
            showLoading();
            location.reload();
        });
    }
    async function successHasil(hasil) {
        Swal.fire({
            icon: 'success',
            title: 'Sukses',
            text: hasil.message,
        }).then(() => {
            showLoading();
            location.reload();
        });
    }
</script>

</html>