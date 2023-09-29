<!DOCTYPE html>
<html lang="en">

<head>

    @include('partial.head')
</head>

<body class="theme-dark" style="overflow-y: auto;">

<div class="container-fluid">
    <button onclick="goBack()" style="background: none; border: none;">
        <i class="bi bi-arrow-left" style="font-size: 50px; color:lightskyblue;"></i>
    </button>
</div>
    </div>
    </div>
    <div class="page-heading">
        @yield('content')
    </div>

    <footer>
        @include('partial.footer')
    </footer>
    </div>
    </div>
    </div>
    <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
    <script src="{{asset('dist/assets/js/bootstrap.js')}}"></script>
    <script src="{{asset('dist/assets/js/app.js')}}"></script>
    <script src="{{asset('fontawesome/js/all.js')}}"></script>
    <script src="{{asset('fontawesome/js/all.min.js')}}"></script>
    <script src="{{asset('dist/assets/extensions/simple-datatables/umd/simple-datatables.js')}}"></script>
    <script src="{{asset('dist/assets/js/pages/simple-datatables.js')}}"></script>
    <script src="{{ asset('vendor/components/jquery/jquery.min.js') }}"></script>
    <script src="{{asset('dist/assets/extensions/sweetalert2/sweetalert2.min.js')}}"></script>
    <script src="{{asset('dist/assets/js/pages/sweetalert2.js')}}"></script>
    <script src="{{asset('dist/assets/extensions/choices.js/public/assets/scripts/choices.js')}}"></script>
    <script src="{{asset('dist/assets/js/pages/form-element-select.js')}}"></script>
    <!-- <script src="{{ asset('query-ui/jquery-ui.js') }}"></script>
    <script src="{{ asset('query-ui/jquery-ui.min.js') }}" type="text/javascript"></script>
    <script src="{{asset('jquery-3.6.4.min.js')}}" type="text/javascript"></script> -->
    @yield('custom_js')
</body>
@include('partial.invoice.js.js_customer')


</html>