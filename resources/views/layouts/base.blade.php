<!-- /tos/resources/views/layouts/base.blade.php -->
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>History Container</title>

    <!-- Styles -->
    @include('layouts.partials.styles')
</head>
<body>
    <div id="app">
        @include('layouts.partials.sidebar',['href' => route('dashboard'),'logo' => asset('logo/icon2.png'), 'title' => "Menu"])
        
        <div id="main" class='layout-navbar'>
            @include('layouts.partials.header')
            <div id="main-content">

                <div class="page-heading">
                	@hasSection('header')
                    <div class="page-title">
                        @yield('header')
                    </div>
					@endif
                    @yield('content')
                </div>

                @include('partial.footer')
            </div>
        </div>
    </div>

    <!-- Scripts -->
    @include('layouts.partials.scripts')

</body>
</html>
