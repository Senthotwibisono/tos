<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Android Dashboard</title>
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
    <!-- Header -->
        <div class="container">
        <h3>Android Dashboard</h3>
            <div class="card mt-5">
                <div class="card-header">
                    <h4 class="card-title">Menu</h4>
                </div>
                <div class="card-body">
                    <div class="col-12">
                        <div style="width: 250px;">
                          <div style="float: left; margin-right: 60px;">
                              <lottie-player src="https://assets10.lottiefiles.com/packages/lf20_7hgsy40v.json" background="#FFFFFF" speed="1" width="100" height="100" loop autoplay></lottie-player>
                              <a href="/android-cc" style="text-align: justify;" class="btn btn-primary rounded-pill">Discharge & Load</a>
                          </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div style="width: 250px;">
                          <div style="float: left; margin-right: 40px;">
                                <lottie-player src="https://assets6.lottiefiles.com/packages/lf20_EmRVgS9kej.json" background="#FFFFFF" speed="1" width="100" height="100" loop autoplay></lottie-player>
                                <a href="/android-yard" class="btn btn-info rounded-pill">Yard</a>
                          </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div style="width: 250px;">
                          <div style="float: left; margin-right: 40px;">
                                <lottie-player src="https://assets5.lottiefiles.com/packages/lf20_oqt1izjh.json" background="#FFFFFF" speed="1" width="150" height="150" loop autoplay></lottie-player>
                                <a href="/android-gate" class="btn btn-danger rounded-pill">Gate</a>
                          </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
                

                                
                

    <!-- Script -->
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
 </body>
 </html>


    <!-- <div class="card-body">
                    <div class="buttons">
                        <div class="child">
                            <div style="width: 300px;">
                                <div style="float: left; margin-right: 20px;">
                                    <lottie-player src="https://assets10.lottiefiles.com/packages/lf20_7hgsy40v.json" background="#FFFFFF" speed="1" width="100" height="100" loop autoplay></lottie-player>
                                    <a href="/disch/android" style="text-align: justify;" class="btn btn-primary rounded-pill">Confirm Disch</a>
                                </div>
                            </div>
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
                                            <a href="/delivery/android-in" class="btn btn-warning rounded-pill">Gate In</a>
                                        </div>
                                        <div style="width: 300px;">
                                            <div style="float: left; margin-right: 20px;">
                                                <lottie-player src="https://assets5.lottiefiles.com/packages/lf20_oqt1izjh.json" background="#FFFFFF" speed="1" width="150" height="150" loop autoplay></lottie-player>
                                                <a href="/delivery/android-out" class="btn btn-danger rounded-pill">Gate Out</a>
                                            </div> -->
                                            <!-- <div style="width: 300px;">
                                                <div style="float: left; margin-right: 20px;">
                                                    <lottie-player src="https://assets9.lottiefiles.com/packages/lf20_fi0ty9ak.json" background="#FFFFFF" speed="1" width="100" height="100" loop autoplay></lottie-player>
                                                    <a href="#" class="btn btn-success  rounded-pill">Success</a>
                                                </div> -->
                                                <!-- <a href="#" class="btn btn-light rounded-pill">Light</a> -->
                                                <!-- <a href="#" class="btn btn-dark rounded-pill">Dark</a> -->
                                            <!-- </div>
                                        </div>
                                    </div>
                                </div> -->