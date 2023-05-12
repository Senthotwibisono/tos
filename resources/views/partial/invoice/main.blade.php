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
              <a href="/invoice"><img src="{{asset('dist/assets/images/logo/logo.svg')}}" alt="Logo"></a>
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
        <nav class="main-navbar">
          @include('partial.invoice.navbar')
        </nav>

      </header>

      <div class="content-wrapper container">

        @yield('content')

      </div>

      <!-- <footer>
        @include('partial.invoice.footer')
      </footer> -->
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

  <!-- select 2 js  -->
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

</body>

<script>
  let jquery_datatable = $("#table1").DataTable()
  let jquery_datatable2 = $("#table2").DataTable()
  let jquery_datatable3 = $("#table3").DataTable()
  let jquery_datatable4 = $("#table4").DataTable()
</script>

<script>
  $(document).ready(function() {
    $('.js-example-basic-single').select2();
    $('.js-example-basic-multiple').select2();
  });
</script>

</html>