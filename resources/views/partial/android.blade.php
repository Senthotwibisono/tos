<!DOCTYPE html>
<html lang="en">

<head>
    @include('partial.head')
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        @media (max-width: 480px) {

            .button {
                font-size: 1rem !important;
            }

            .btn-smaller {
                padding: 0.15rem 0.15rem !important;
            }

            s .card-header,
            .card-body {
                padding: 0.2rem !important;
                font-size: 10px !important;
            }

            /* Reduce modal size */
            .modal.fade.text-left .modal-dialog {
                max-width: 100% !important;
                font-size: 10px !important;
            }

            /* If you want to further adjust modal content size, you can do so by targeting modal content elements here. */
            .modal.fade.text-left .modal-body {
                max-width: 100% !important;
                font-size: 12px !important;
                /* Your styles for modal content here */
            }

            .modal-content {
                max-width: 100% !important;
                font-size: 10px !important;
                /* Your styles for modal content here */
            }

            #container-list {
                /* Add your custom styles here to make it smaller */
                max-width: 100% !important;
                /* Adjust the width to your desired size */
                margin: 0 auto !important;
                /* Center the div horizontally */
                padding: 5px !important;
                /* Add padding for spacing inside the div */
            }
        }
    </style>
</head>

<body class="" style="overflow-y: auto;">
    <div class="container-fluid">
        <button onclick="goBack()" style="background: none; border: none;">
            <i class="bi bi-arrow-left" style="font-size: 50px; color: lightskyblue;"></i>
        </button>
    </div>
    <div class="page-heading">
        @yield('content')
    </div>
    <footer>
        @include('partial.footer')
    </footer>
   <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
    <script src="{{asset('dist/assets/js/bootstrap.js')}}"></script>
    <script src="{{asset('dist/assets/js/app.js')}}"></script>
    <script src="{{asset('dist/assets/js/pages/horizontal-layout.js')}}"></script>
    <script src="{{asset('dist/assets/extensions/apexcharts/apexcharts.min.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0/dist/chartjs-plugin-datalabels.min.js"></script>
    <script src="{{asset('dist/assets/js/pages/dashboard.js')}}"></script>
    <script src="{{asset('fontawesome/js/all.js')}}"></script>
    <script src="{{asset('fontawesome/js/all.min.js')}}"></script>
    <script src="{{ asset('vendor/components/jquery/jquery.min.js') }}"></script>
    <script src="{{asset('dist/assets/extensions/sweetalert2/sweetalert2.min.js')}}"></script>
    <script src="{{asset('dist/assets/js/pages/sweetalert2.js')}}"></script>
    <script src="{{asset('dist/assets/extensions/choices.js/public/assets/scripts/choices.js')}}"></script>
    <script src="{{asset('dist/assets/js/pages/form-element-select.js')}}"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="{{asset('dist/assets/extensions/simple-datatables/umd/simple-datatables.js')}}"></script>
    <script src="{{asset('dist/assets/js/pages/simple-datatables.js')}}"></script>

    <!-- dataTables -->
    <script src="{{ asset('dataTables/datatables.min.js')}}"></script>

    <!-- QR Generator -->
  <script src="{{ asset('html5/html5.min.js') }}"></script>
  
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
  <script src="{{ asset('dropify/dist/js/dropify.js') }}"></script>

  <!-- date-range-picker  -->
  <script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>

  <!-- moment.js  -->
  <script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <!-- <script>
        $(document).ready(function() {
        // Initialize all tables with class 'dataTable-wrapper'
            $('.tabelCustom').each(function() {
                $(this).DataTable();
            });
        });
    </script> -->
    @if ($errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let errorMessages = '';
            @foreach ($errors->all() as $error)
                errorMessages += '{{ $error }}\n';
            @endforeach
            Swal.fire({
                icon: 'error',
                title: 'Validation Errors',
                text: errorMessages,
            });
        });
    </script>
    @endif

    @if (session('status'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const status = @json(session('status'));
            Swal.fire({
                icon: status.type,
                title: status.type === 'success' ? 'Success!' : 'Error!',
                text: status.message
            });
        });
    </script>
    @endif
    <script src="{{ asset('select2/dist/js/select2.min.js') }}"></script>
    <script>
      $(document).ready(function() {
            $('.selectSingle').each(function() {
                let parent = $(this).closest('.modal');
                if (parent.length) {
                    // Kalau select2 ada di dalam modal
                    $(this).select2({
                        dropdownParent: parent
                    });
                } else {
                    // Kalau select2 di luar modal
                    $(this).select2();
                }
            });
        
            $('.selectMultiple').each(function() {
                let parent = $(this).closest('.modal');
                if (parent.length) {
                    $(this).select2({
                        dropdownParent: parent
                    });
                } else {
                    $(this).select2();
                }
            });

            $('.datetime').flatpickr({
                enableTime: true,
                dateFormat: "Y-m-d H:i:s",
            });

            $('.date').flatpickr({
                enableTime: true,
                dateFormat: "Y-m-d",
            });

            $('.hour').flatpickr({
                enableTime: true,
                dateFormat: "H:i:s",
            });
        });
    </script>

    <script>
    $(document).ready(function() {
        $(".customSelect").select2({
        dropdownParent: $('#addManual .modal-content')
        });

        $(".editSelect").select2({
        dropdownParent: $('#editCust .modal-content')
        });
        $(".customSelectWMS").select2({
        dropdownParent: $('#addTarifWMS .modal-content')
        });

        $(".editSelectWMS").select2({
        dropdownParent: $('#editCustWMS .modal-content')
        });
    });
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
        function buttonLoading(button) {
           let blocker = document.createElement('div');
            blocker.id = 'globalBlocker';
            blocker.style.position = 'fixed';
            blocker.style.top = 0;
            blocker.style.left = 0;
            blocker.style.width = '100%';
            blocker.style.height = '100%';
            blocker.style.background = '';
            blocker.style.zIndex = 9999;
            blocker.style.cursor = 'wait';
            document.body.appendChild(blocker);
        
            // Simpan text asli button
            button.dataset.originalText = button.innerHTML;
        
            // Ganti isi button dengan spinner
            button.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...`;
        }

        function hideButton(button) {
            // Aktifkan semua elemen
            const blocker = document.getElementById('globalBlocker');
            if (blocker) {
                blocker.remove();
            }
        
            // Kembalikan isi button
            if (button.dataset.originalText) {
                button.innerHTML = button.dataset.originalText;
                delete button.dataset.originalText;
            }
        }
    </script>
    <script>
        async function errorResponse(response) {
            Swal.fire({
                icon: 'error',
                title: response.status,
                text: response.statusText,
            });
        }
        async function errorHasil(hasil) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: hasil.message,
            });
        }
        async function successHasil(hasil) {
            Swal.fire({
                icon: 'success',
                title: 'Sukses',
                text: hasil.message,
            });
        }
        
        async function confirmation() {
            return Swal.fire({
                icon: 'warning',
                title: 'Are you sure?',
                text: 'Apakah anda yakin melakukan aksi berikut?',
                showCancelButton: true,
            });
        }

        async function globalResponse(data, url) {
            console.log(data, url);
            return response = await fetch(url, {
                method: "POST",
                headers: {
                  "X-CSRF-TOKEN": "{{ csrf_token() }}",
                  "Content-Type": "application/json",
                },
                body: JSON.stringify(data),
            });
        }
    </script>

    <script>
        function checkFields() {
            const requiredFields = document.querySelectorAll('.required-field');
            const saveButton = document.getElementById('saveButton');      
                
            let allFilled = true;
            requiredFields.forEach(input => {
                if (!input.value.trim()) {
                    allFilled = false;
                }
            });
            if (saveButton) {
                saveButton.disabled = !allFilled;
            }
        }
        
        document.addEventListener('DOMContentLoaded', function () {
            checkFields();
        
            const requiredFields = document.querySelectorAll('.required-field');
            requiredFields.forEach(input => {
                input.addEventListener('input', checkFields);
            });
        });
    </script>
    <!-- <script src="{{ asset('query-ui/jquery-ui.js') }}"></script>
    <script src="{{ asset('query-ui/jquery-ui.min.js') }}" type="text/javascript"></script>
    <script src="{{asset('jquery-3.6.4.min.js')}}" type="text/javascript"></script> -->
    @yield('custom_js')
</body>

</html>