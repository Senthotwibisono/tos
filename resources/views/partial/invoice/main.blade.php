
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

            <footer>
               @include('partial.invoice.footer')
            </footer>
        </div>
    </div>
    <script src="{{asset('dist/assets/js/bootstrap.js')}}"></script>
    <script src="{{asset('dist/assets/js/app.js')}}"></script>
    <script src="{{asset('dist/assets/js/pages/horizontal-layout.js')}}"></script>  
<script src="{{asset('dist/assets/extensions/apexcharts/apexcharts.min.js')}}"></script>
<script src="{{asset('dist/assets/js/pages/dashboard.js')}}"></script>
<script src="{{asset('fontawesome/js/all.js')}}"></script>
<script src="{{asset('fontawesome/js/all.min.js')}}"></script>

</body>

</html>
