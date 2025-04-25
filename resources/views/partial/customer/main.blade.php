<!DOCTYPE html>
<html lang="en">

<head>
  @include('partial.customer.header')

</head>

<body>
  <div id="app">
    <div id="main" class="layout-horizontal">
      <header class="mb-5">
        <div class="header-top">
          <div class="container">
            <div class="logo">
              <a href="#"><img class="logoicon" src="{{asset('logo/ICON2.png')}}" alt="Logo"></a>
            </div>
            <div class="header-top-right">

              @include('partial.customer.authheader')

              <!-- Burger button responsive -->
              <a href="#" class="burger-btn d-block d-xl-none">
                <i class="bi bi-justify fs-3"></i>
              </a>
            </div>
          </div>
        </div>
        <nav class="main-navbar">
          @include('partial.customer.navbar')
        </nav>

      </header>

      <div class="content-wrapper container">
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

      <!-- <footer>
        @include('partial.customer.footer')
      </footer> -->
      <!-- adding commmited message  -->
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
  <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
  <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
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

    <script>
        $(document).ready(function() {
        // Initialize all tables with class 'dataTable-wrapper'
            $('.tabelCustom').each(function() {
                $(this).DataTable({});
                
            });
        });
    </script>
  <!-- select 2 js  -->
  <script src="{{ asset('select2/dist/js/select2.min.js') }}"></script>
  <!-- <link rel="stylesheet" href="{{ asset('select2/dist/css/select2.min.js') }}"> -->


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
          url: "{{ route('unset-session', ['key' => 'error']) }}",
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

<!-- <script>
new simpleDatatables.DataTable('#table2');
  new simpleDatatables.DataTable('#table3');
  new simpleDatatables.DataTable('#table4');
  new simpleDatatables.DataTable('#table5');
  new simpleDatatables.DataTable('#table6');
  new simpleDatatables.DataTable('#table7');
  new simpleDatatables.DataTable('#table8');
  new simpleDatatables.DataTable('#table9');
  new simpleDatatables.DataTable('#table10');
</script> -->

<script>
  $(document).ready(function() {
    $('.js-example-basic-single').select2();
    $('.js-example-basic-multiple').select2();
    flatpickr('#expired', {
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

    showLoading();
    window.onload = function(){
      hideLoading();
    };
</script>

<script>
  const Pop = {
    notif: function ({ icon = 'info', title = '', text = '' }) {
        return new Promise((resolve) => {
            // Hapus elemen lama jika ada
            const existingSwal = document.getElementById('customSwalOverlay');
            if (existingSwal) existingSwal.remove();

            // Buat elemen overlay
            const overlay = document.createElement('div');
            overlay.id = 'customSwalOverlay';
            overlay.style.position = 'fixed';
            overlay.style.top = '0';
            overlay.style.left = '0';
            overlay.style.width = '100%';
            overlay.style.height = '100%';
            overlay.style.background = 'rgba(0, 0, 0, 0.5)';
            overlay.style.display = 'flex';
            overlay.style.justifyContent = 'center';
            overlay.style.alignItems = 'center';
            overlay.style.zIndex = '9999';

            // Buat modal box
            const modal = document.createElement('div');
            modal.style.background = 'white';
            modal.style.padding = '20px';
            modal.style.borderRadius = '8px';
            modal.style.boxShadow = '0px 0px 10px rgba(0, 0, 0, 0.2)';
            modal.style.textAlign = 'center';
            modal.style.maxWidth = '300px';
            modal.style.animation = 'fadeIn 0.3s';

            // Tambahkan ikon (opsional)
            const iconElement = document.createElement('div');
            iconElement.style.fontSize = '40px';
            iconElement.style.marginBottom = '10px';

            if (icon === 'success') {
                iconElement.innerHTML = '✅';
            } else if (icon === 'error') {
                iconElement.innerHTML = '❌';
            } else if (icon === 'warning') {
                iconElement.innerHTML = '⚠️';
            } else {
                iconElement.innerHTML = 'ℹ️';
            }

            // Tambahkan title
            const titleElement = document.createElement('h2');
            titleElement.innerText = title;
            titleElement.style.margin = '0 0 10px';

            // Tambahkan text
            const textElement = document.createElement('p');
            textElement.innerText = text;

            // Tambahkan tombol
            const button = document.createElement('button');
            button.innerText = 'OK';
            button.style.padding = '8px 16px';
            button.style.border = 'none';
            button.style.background = '#007bff';
            button.style.color = 'white';
            button.style.borderRadius = '5px';
            button.style.cursor = 'pointer';
            button.style.marginTop = '10px';
            button.onclick = () => {
                overlay.remove();
                resolve();
            };

            // Susun elemen ke dalam modal
            modal.appendChild(iconElement);
            modal.appendChild(titleElement);
            modal.appendChild(textElement);
            modal.appendChild(button);
            overlay.appendChild(modal);
            document.body.appendChild(overlay);
        });
    }
};
</script>
<script>
 window.addEventListener("beforeunload", function () {
        showLoading();
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
    function openWindow(url) {
        window.open(url, '_blank', 'width=600,height=800');
    }
</script>

</html>