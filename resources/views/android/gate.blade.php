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

        .stuffing-section {
            margin-top: 20px;
            /* Adjust the value as needed */
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
    <br>
    <div class="container">
        <h3>&nbspGate Dashboard</h3>
        <div class="card mt-5">
            <div class="card-header">
                <h4 class="card-title">Delivery Menu</h4>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <p><strong>Gate Bongkar</strong></p>
                    <div class="col-sm-6">
                        <div style="width: 300px;">
                            <div style="float: left; margin-right: 20px;">
                                <lottie-player src="https://assets5.lottiefiles.com/packages/lf20_oqt1izjh.json" background="#FFFFFF" speed="1" width="100" height="100" loop autoplay></lottie-player>
                                <a href="/delivery/android-in" class="btn btn-warning rounded-pill">Gate
                                    In</a>
                            </div>
                        </div>
                    </div>
                   <div class="col-sm-6">
                        <div style="width: 300px;">
                             <div style="float: left; margin-right: 20px;">
                                 <lottie-player src="https://assets5.lottiefiles.com/packages/lf20_oqt1izjh.json" background="#FFFFFF" speed="1" width="150" height="150" loop autoplay></lottie-player>
                                 <a href="/delivery/android-out" class="btn btn-danger rounded-pill">Gate
                                     Out</a>
                             </div>
                        </div>
                   </div>
                </div>

                <!-- SPPS -->
                <!-- <div class="row mb-3">
                    <p><strong>Gate SPPS</strong></p>
                    <div class="col-sm-6">
                        <div style="width: 300px;">
                            <div style="float: left; margin-right: 20px;">
                                <lottie-player src="https://assets5.lottiefiles.com/packages/lf20_oqt1izjh.json" background="#FFFFFF" speed="1" width="100" height="100" loop autoplay></lottie-player>
                                <a href="/delivery/android-in" class="btn btn-warning rounded-pill">Gate
                                    In</a>
                            </div>
                        </div>
                    </div>
                   <div class="col-sm-6">
                        <div style="width: 300px;">
                             <div style="float: left; margin-right: 20px;">
                                 <lottie-player src="https://assets5.lottiefiles.com/packages/lf20_oqt1izjh.json" background="#FFFFFF" speed="1" width="150" height="150" loop autoplay></lottie-player>
                                 <a href="/delivery/android-out" class="btn btn-danger rounded-pill">Gate
                                     Out</a>
                             </div>
                        </div>
                   </div>
                </div> -->

                <!-- Checking Dokumen -->
                <div class="row mb-3">
                    <p><strong>Check Container</strong></p>
                    <div class="col-sm-6">
                        <div style="width: 300px;">
                            <div style="float: left; margin-right: 20px;">
                                <lottie-player src="https://assets5.lottiefiles.com/packages/lf20_oqt1izjh.json" background="#FFFFFF" speed="1" width="100" height="100" loop autoplay></lottie-player>
                                <a href="/gate/checking-" class="btn btn-warning rounded-pill">Check Container Invoice</a>
                            </div>
                        </div>
                    </div>
                   <!-- <div class="col-sm-6">
                        <div style="width: 300px;">
                             <div style="float: left; margin-right: 20px;">
                                 <lottie-player src="https://assets5.lottiefiles.com/packages/lf20_oqt1izjh.json" background="#FFFFFF" speed="1" width="150" height="150" loop autoplay></lottie-player>
                                 <a href="/gate/checking-perpanjangan-" class="btn btn-danger rounded-pill">Container Extend (Perpanjangan)</a>
                             </div>
                        </div>
                   </div> -->
                </div>

            </div>
        </div>

        <!-- Stuffing Section -->
        <div class="card mt-4 stuffing-section">
            <div class="card-header">
                <h4 class="card-title">Stuffing Menu</h4>
            </div>
            <div class="card-body">
                <div class="buttons">
                    <div class="child">
                        <div class="row mb-3">
                            <div class="col-sm-6">
                                <div style="width: 300px;">
                                    <div style="float: left; margin-right: 20px;">
                                        <lottie-player src="https://lottie.host/cb74262b-92a5-488b-8e47-d12410b5fcd6/lZ1GfrbXOQ.json" background="#FFFFFF" speed="1" width="150" height="150" loop autoplay></lottie-player>
                                        <a href="/stuffing/gate-in-stuffing-android" class="btn btn-warning rounded-pill">Gate In Stuffing</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div style="width: 300px;">
                                    <div style="float: left; margin-right: 20px;">
                                        <lottie-player src="https://lottie.host/cb74262b-92a5-488b-8e47-d12410b5fcd6/lZ1GfrbXOQ.json" background="#FFFFFF" speed="1" width="150" height="150" loop autoplay></lottie-player>
                                        <a href="/stuffing/gate-out-stuffing-android" class="btn btn-danger rounded-pill">Gate Out Stuffing</a>
                                    </div>
                                    <!-- <hr>
                                    <a href="/docs/dokumen/ro-android" class="btn btn-primary rounded-pill">Dokumen RO</a> -->
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                    <div style="width: 300px;">
                                        <div style="float: left; margin-right: 20px;">
                                            <lottie-player src="https://lottie.host/a69618ca-9be0-4ca9-8bdc-f77c5515488b/hcDUnyATfv.json" background="#FFFFFF" speed="1" width="150" height="150" loop autoplay></lottie-player>
                                            <a href="/docs/dokumen/ro-android" class="btn btn-primary rounded-pill">Dokumen RO</a>
                                        </div>
                                        <!-- <hr>
                                        <a href="/docs/dokumen/ro-android" class="btn btn-primary rounded-pill">Dokumen RO</a> -->
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mt-4 stuffing-section">
            <div class="card-header">
                <h4 class="card-title">Gate Muat</h4>
            </div>
            <div class="card-body">
                <div class="buttons">
                    <div class="child">
                       <div class="row mb-3">
                            <div class="col-sm-6">
                                <div style="width: 300px;">
                                    <div style="float: left; margin-right: 20px;">
                                        <lottie-player src="https://assets5.lottiefiles.com/packages/lf20_oqt1izjh.json" background="#FFFFFF" speed="1" width="150" height="150" loop autoplay></lottie-player>
                                        <a href="/reciving/gate-in-android" class="btn btn-warning rounded-pill">Gate In</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div style="width: 300px;">
                                    <div style="float: left; margin-right: 20px;">
                                        <lottie-player src="https://assets5.lottiefiles.com/packages/lf20_oqt1izjh.json" background="#FFFFFF" speed="1" width="150" height="150" loop autoplay></lottie-player>
                                        <a href="/reciving/gate-out-android" class="btn btn-danger rounded-pill">Gate Out</a>
                                    </div>
                                </div>
                            </div>
                       </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-5">
            <div class="card-header">
                <h4 class="card-title">Gate Container Empty</h4>
            </div>
            <div class="card-body">
                <div class="buttons">
                    <div class="child">
                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <p><strong>Gate MT Balik IKS/MKB</strong></p>
                                <div style="width: 300px;">
                                    <div style="float: left; margin-right: 20px;">
                                        <lottie-player src="https://assets5.lottiefiles.com/packages/lf20_oqt1izjh.json" background="#FFFFFF" speed="1" width="100" height="100" loop autoplay></lottie-player>
                                        <a href="/delivery/balik-relokasi-android" class="btn btn-success rounded-pill">Open</a>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <p><strong>Gate In Ambil Empty</strong></p>
                                <div style="width: 300px;">
                                    <div style="float: left; margin-right: 20px;">
                                        <lottie-player src="https://assets5.lottiefiles.com/packages/lf20_oqt1izjh.json" background="#FFFFFF" speed="1" width="100" height="100" loop autoplay></lottie-player>
                                        <a href="/gate/gate-in/MT" class="btn btn-success rounded-pill">Open</a>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <p><strong>Gate Out Ambil Empty</strong></p>
                                <div style="width: 300px;">
                                    <div style="float: left; margin-right: 20px;">
                                        <lottie-player src="https://assets5.lottiefiles.com/packages/lf20_oqt1izjh.json" background="#FFFFFF" speed="1" width="100" height="100" loop autoplay></lottie-player>
                                        <a href="/gate/gate-out/MT" class="btn btn-success rounded-pill">Open</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <script src=" https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
</body>

</html>