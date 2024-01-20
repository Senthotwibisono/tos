<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yard Dashboard</title>
    <link rel="stylesheet" href="{{asset('dist/assets/css/main/app.css')}}">
    <link rel="shortcut icon" href="{{asset('logo/icon.png')}}" type="image/x-icon">
    <link rel="shortcut icon" href="{{asset('logo/icon.png')}}" type="image/png">

    <style>
        .logoicon {
            transform: scale(3);
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-light">
        <div class="container d-block">

            <a class="navbar-brand ms-4" href="#">
                <img style="position: relative; left: -20px;" src="{{asset('logo/ICON2.png')}}" alt="Logo" srcset="">
            </a>
        </div>

        <br>
        <br>

        <button class="btn btn-danger rounded-pill">
            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                                document.getElementById('logout-form').submit();"><i class="icon-mid bi bi-box-arrow-left me-2"></i>
                {{ __('Logout') }}
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </button>
    </nav>



    <div class="container">
        <div class="card mt-5">
            <div class="card-header">
                <h4 class="card-title">Yard Menu</h4>
            </div>
            <div class="card-body">
                <div class="buttons">
                    <div class="child">
                        <div class="child">
                            <div style="width: 300px;">
                                <div style="float: left; margin-right: 20px;">
                                    <lottie-player src="https://assets9.lottiefiles.com/packages/lf20_fi0ty9ak.json" background="#FFFFFF" speed="1" width="100" height="100" loop autoplay></lottie-player>
                                    <a href="/yard/android" class="btn btn-secondary rounded-pill">Placement Container</a>
                                </div>
                            </div>
                            <div style="width: 300px;">
                                <div style="float: left; margin-right: 20px;">
                                    <lottie-player src="https://assets6.lottiefiles.com/packages/lf20_EmRVgS9kej.json" background="#FFFFFF" speed="1" width="100" height="100" loop autoplay></lottie-player>
                                    <a href="/stripping/android" class="btn btn-info rounded-pill">Stripping</a>
                                </div>
                                <div style="width: 300px;">
                                    <div style="float: left; margin-right: 20px;">
                                        <lottie-player src="https://assets5.lottiefiles.com/packages/lf20_oqt1izjh.json" background="#FFFFFF" speed="1" width="100" height="100" loop autoplay></lottie-player>
                                        <a href="/stuffing/android" class="btn btn-warning rounded-pill">Stuffing</a>
                                    </div>
                                    <div style="width: 300px;">
                                        <div style="float: left; margin-right: 20px;">
                                            <lottie-player src="https://assets5.lottiefiles.com/packages/lf20_oqt1izjh.json" background="#FFFFFF" speed="1" width="100" height="100" loop autoplay></lottie-player>
                                            <a href="/yard/trucking-android" class="btn btn-success rounded-pill">Trucking</a>
                                        </div>
                                        </div>
                                            <div style="width: 300px;">
                                                <div style="float: left; margin-right: 20px;">
                                                    <lottie-player src="https://lottie.host/de29ff57-163b-4d22-9bea-e3ed861aafbc/QsTOsNhByF.json" background="#FFFFFF" speed="1" width="100" height="100" loop autoplay></lottie-player>
                                                    <a href="/yard/yard-view/android" class="btn btn-danger  rounded-pill">Yard View</a>
                                                </div>
                                        <!-- <a href="#" class="btn btn-light rounded-pill">Light</a> -->
                                        <!-- <a href="#" class="btn btn-dark rounded-pill">Dark</a> -->
                                    </div>
                                </div>
                            </div>
                        </div>

                        <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
</body>

</html>