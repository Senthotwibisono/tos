<!DOCTYPE html>
<html lang="en">

<head>

    @include('partial.head')
</head>

<body class="theme-dark" style="overflow-y: auto;">
    <div id="app">
        <div id="sidebar" class="active">
            <div class="sidebar-wrapper active">
                <div class="sidebar-header position-flex">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="logoicon" style="left: -1000;">
                            <a href="/"><img src="{{asset('logo/ICON2.png')}}" alt="Logo" style="position: relative; left: 15px; width: 100%;" srcset=""></a>
                        </div>
                        <!-- Dark or Light mode -->
                        
                        <div class="sidebar-toggler  x">
                            <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                        </div>
                    </div>
                   <div style="line-height: 0.5;">&nbsp;</div>
                    <div class="theme-toggle d-flex gap-2  align-items-center mt-2" style="position: relative; left: 80px;">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" class="iconify iconify--system-uicons" width="20" height="20" preserveAspectRatio="xMidYMid meet" viewBox="0 0 21 21">
                                <g fill="none" fill-rule="evenodd" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M10.5 14.5c2.219 0 4-1.763 4-3.982a4.003 4.003 0 0 0-4-4.018c-2.219 0-4 1.781-4 4c0 2.219 1.781 4 4 4zM4.136 4.136L5.55 5.55m9.9 9.9l1.414 1.414M1.5 10.5h2m14 0h2M4.135 16.863L5.55 15.45m9.899-9.9l1.414-1.415M10.5 19.5v-2m0-14v-2" opacity=".3"></path>
                                    <g transform="translate(-210 -1)">
                                        <path d="M220.5 2.5v2m6.5.5l-1.5 1.5"></path>
                                        <circle cx="220.5" cy="11.5" r="4"></circle>
                                        <path d="m214 5l1.5 1.5m5 14v-2m6.5-.5l-1.5-1.5M214 18l1.5-1.5m-4-5h2m14 0h2"></path>
                                    </g>
                                </g>
                            </svg>
                            <div class="form-check form-switch fs-6">
                                <input class="form-check-input  me-0" type="checkbox" id="toggle-dark">
                                <label class="form-check-label"></label>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" class="iconify iconify--mdi" width="20" height="20" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
                                <path fill="currentColor" d="m17.75 4.09l-2.53 1.94l.91 3.06l-2.63-1.81l-2.63 1.81l.91-3.06l-2.53-1.94L12.44 4l1.06-3l1.06 3l3.19.09m3.5 6.91l-1.64 1.25l.59 1.98l-1.7-1.17l-1.7 1.17l.59-1.98L15.75 11l2.06-.05L18.5 9l.69 1.95l2.06.05m-2.28 4.95c.83-.08 1.72 1.1 1.19 1.85c-.32.45-.66.87-1.08 1.27C15.17 23 8.84 23 4.94 19.07c-3.91-3.9-3.91-10.24 0-14.14c.4-.4.82-.76 1.27-1.08c.75-.53 1.93.36 1.85 1.19c-.27 2.86.69 5.83 2.89 8.02a9.96 9.96 0 0 0 8.02 2.89m-1.64 2.02a12.08 12.08 0 0 1-7.8-3.47c-2.17-2.19-3.33-5-3.49-7.82c-2.81 3.14-2.7 7.96.31 10.98c3.02 3.01 7.84 3.12 10.98.31Z"></path>
                            </svg>
                        </div>
                </div>
                <div class="sidebar-menu">
                    @include('partial.sidebar')
                </div>
            </div>
        </div>
        <div id="main" class='layout-navbar'>
            <header class='mb-3'>
                @include('partial.authheader')
            </header>
            <div id="main-content">
                <div class="page-heading">
                  <h3>{{$itle ?? ''}}</h3>
                </div>
                    @yield('content')
                <footer>
                    @include('partial.footer')
                </footer>
            </div>
        </div>
    </div>
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