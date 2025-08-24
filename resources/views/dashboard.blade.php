@extends('partial.main')

@section('content')

<H1 style=> Dashboard Admin </H1>

<section class="row">
  <div class="col-12 col-lg-9">
    <!-- <div class="row">
                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card">
                        commited changes
                        <div class="card-body px-4 py-4-5">
                            <div class="row">
                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                    <div class="stats-icon purple mb-2">
                                        <i class="iconly-boldShow"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 class="text-muted font-semibold">Profile Views</h6>
                                    <h6 class="font-extrabold mb-0">112.000</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-4 py-4-5">
                            <div class="row">
                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                    <div class="stats-icon blue mb-2">
                                        <i class="iconly-boldProfile"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 class="text-muted font-semibold">Followers</h6>
                                    <h6 class="font-extrabold mb-0">183.000</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-4 py-4-5">
                            <div class="row">
                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                    <div class="stats-icon green mb-2">
                                        <i class="iconly-boldAdd-User"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 class="text-muted font-semibold">Following</h6>
                                    <h6 class="font-extrabold mb-0">80.000</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-4 py-4-5">
                            <div class="row">
                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                    <div class="stats-icon red mb-2">
                                        <i class="iconly-boldBookmark"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 class="text-muted font-semibold">Saved Post</h6>
                                    <h6 class="font-extrabold mb-0">112</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">

            <a href=""><img class="logoicon2" style="position: absolute; left: -250px; top: -100px;" src="{{asset('logo/ICON2.png')}}" alt="Logo"></a>
            <!-- <div style="position:  top: -200px;" id="lottie-animation"></div> -->
          </div>
          <div class="card-body">

          </div>
        </div>
      </div>
    </div>
    <div class="col-12 col-lg-12">
      <div class="card">
        <div class="card-header">
          <h4>Latest Activity</h4>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover table-lg">
              <thead>
                <tr>
                  <th>Operational Name</th>
                  <th>Activity</th>
                </tr>
              </thead>
              <tbody>
                @foreach($history_container as $history)
                <tr>
                  <td>
                    <p class=" mb-0">{{$history->oper_name}}</p>
                  </td>
                  <td>
                    @if($history->operation_name === 'ANNI')
                    <p class=" mb-0">Recive container <b>{{$history->container_no}}</b> from ship <b>{{$history->ves_code}}</b> <br>
                      at {{$history->update_time}}</p>

                    @elseif($history->operation_name === 'CORANNI')
                    <p class=" mb-0">Edit container profile <b>{{$history->container_no}}</b> from ship <b>{{$history->ves_code}}</b> <br>
                      at {{$history->update_time}}</p>

                    @elseif($history->operation_name === 'DISC')
                    <p class=" mb-0">Confirm container <b>{{$history->container_no}}</b> from ship <b>{{$history->ves_code}}</b> <br>
                      at {{$history->update_time}}</p>

                    @elseif($history->operation_name === 'PLC')
                    <p class=" mb-0">Placed container <b>{{$history->container_no}}</b> to <b>Block {{$history->yard_blok}}</b> <b>Slot {{$history->yard_slot}}</b> <b>Row {{$history->yard_row}}</b> <b>Tier {{$history->yard_tier}}</b><br>
                      at {{$history->update_time}}</p>

                    @elseif($history->operation_name === 'STR')
                    <p class=" mb-0">Move container <b>{{$history->container_no}}</b> to <b>Block {{$history->yard_blok}}</b> <b>Slot {{$history->yard_slot}}</b> <b>Row {{$history->yard_row}}</b> <b>Tier {{$history->yard_tier}}</b><br>
                      at {{$history->update_time}}</p>

                    @elseif($history->operation_name === 'GATI')
                    <p class=" mb-0">Get permit truck <b>{{$history->truck_no}}</b> to take <b>{{$history->container_no}}</b> from <b>Block {{$history->yard_blok}}</b> <b>Slot {{$history->yard_slot}}</b> <b>Row {{$history->yard_row}}</b> <b>Tier {{$history->yard_tier}}</b><br>
                      at {{$history->truck_in_date}}</p>

                    @elseif($history->operation_name === 'GATO')
                    <p class=" mb-0">Allowed truck <b>{{$history->truck_no}}</b> to take out<b>{{$history->container_no}}</b> from <b>Block {{$history->yard_blok}}</b> <b>Slot {{$history->yard_slot}}</b> <b>Row {{$history->yard_row}}</b> <b>Tier {{$history->yard_tier}}</b><br>
                      at {{$history->truck_out_date}}</p>
                    @endif
                  </td>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
            <div class="px-4">
              <a href="/reports/hist" class='btn btn-block btn-xl btn-outline-primary font-bold mt-3'>See More...</a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <!-- <div class="col-12 col-xl-4">
                    <div class="card">
                        <div class="card-header">
                            <h4>Profile Visit</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <div class="d-flex align-items-center">
                                        <svg class="bi text-primary" width="32" height="32" fill="blue"
                                            style="width:10px">
                                            <use
                                                xlink:href="assets/images/bootstrap-icons.svg#circle-fill" />
                                        </svg>
                                        <h5 class="mb-0 ms-3">Europe</h5>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <h5 class="mb-0">862</h5>
                                </div>
                                <div class="col-12">
                                    <div id="chart-europe"></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="d-flex align-items-center">
                                        <svg class="bi text-success" width="32" height="32" fill="blue"
                                            style="width:10px">
                                            <use
                                                xlink:href="assets/images/bootstrap-icons.svg#circle-fill" />
                                        </svg>
                                        <h5 class="mb-0 ms-3">America</h5>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <h5 class="mb-0">375</h5>
                                </div>
                                <div class="col-12">
                                    <div id="chart-america"></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="d-flex align-items-center">
                                        <svg class="bi text-danger" width="32" height="32" fill="blue"
                                            style="width:10px">
                                            <use
                                                xlink:href="assets/images/bootstrap-icons.svg#circle-fill" />
                                        </svg>
                                        <h5 class="mb-0 ms-3">Indonesia</h5>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <h5 class="mb-0">1025</h5>
                                </div>
                                <div class="col-12">
                                    <div id="chart-indonesia"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->
      <div class="col-12 col-xl-12">

      </div>
    </div>
  </div>
  <div class="col-12 col-lg-3">
    <!-- <div class="card">
                <div class="card-body py-4 px-4">
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-xl">
                            <img src="assets/images/faces/1.jpg" alt="Face 1">
                        </div>
                        <div class="ms-3 name">
                            <h5 class="font-bold">John Duck</h5>
                            <h6 class="text-muted mb-0">@johnducky</h6>
                        </div>
                    </div>
                </div>
            </div> -->
    <div class="card">
      <div class="card-header">
        <h4>On Going Ship</h4>
      </div>
      <div class="card-content pb-4">
        @foreach ($vessel_voyage as $voy)
        <div class="recent-message d-flex px-4 py-3">
          <div class="avatar avatar-lg">
            <div><i class="fa fa-ship"></i></div>
          </div>
          <div class="name ms-4">
            <h5 class="mb-1">{{$voy->ves_name}} || {{$voy->voy_out}}</h5>
            <h6 class="text-muted mb-0">{{$voy->etd_date}}</h6>
          </div>
        </div>
        @endforeach
        <div class="px-4">
          <a href="/planning/vessel-schedule" class='btn btn-block btn-xl btn-outline-primary font-bold mt-3'>See More...</a>
        </div>
      </div>
    </div>
    <div class="card">
      <div class="card-header">
        <h4>Yard Condition</h4>
      </div>
      <div class="card-body">
        <canvas id="myDonutChart"></canvas>
      </div>
    </div>
  </div>

</section>

@endsection

@section('custom_js')
<script src="{{ asset('vendor/components/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('select2/dist/js/select2.full.min.js') }}"></script>
<script src="{{asset('dist/assets/extensions/sweetalert2/sweetalert2.min.js')}}"></script>
<script src="{{asset('dist/assets/js/pages/sweetalert2.js')}}"></script>
<script src="{{asset('dist/assets/extensions/apexcharts/apexcharts.min.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0/dist/chartjs-plugin-datalabels.min.js"></script>
    <script src="{{asset('dist/assets/js/pages/dashboard.js')}}"></script>
<!-- <script src="{{asset('dist/assets/js/pages/dashboard.js')}}"></script> -->
<script src="{{asset('lottifiles/lokal.min.js')}}"></script>


<!-- <script>
  document.addEventListener('DOMContentLoaded', function() {
    var animation = lottie.loadAnimation({
      container: document.getElementById('lottie-animation'),
      renderer: 'svg',
      loop: true,
      autoplay: true,
      path: '/lottifiles/97854-imprint-genius-hero.json' // Ubah path sesuai dengan jalur file Lottie JSON Anda
    });
  });
</script> -->

<script>
    var ctx = document.getElementById('myDonutChart').getContext('2d');
    var donutChart = new Chart(ctx, {
        type: 'doughnut', // Tipe chart
        data: {
            labels: ['Terisi', 'Tidak Terisi'], // Label chart
            datasets: [{
                label: 'Kapasitas',
                data: [{{ $persentaseTerisi }}, {{ $persentaseTidakTerisi }}], // Data persentase
                backgroundColor: [
                    'rgba(75, 192, 192, 1)', // Warna untuk 'Terisi'
                    'rgba(211, 211, 211, 1)'  // Warna untuk 'Tidak Terisi' (abu-abu muda)
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return tooltipItem.label + ': ' + tooltipItem.raw + '%';
                        }
                    }
                },
                datalabels: {
                    color: '#fff',
                    font: {
                        weight: 'bold',
                        size: 20
                    },
                    formatter: function(value, context) {
                        if (context.dataIndex === 0) {
                            return context.chart.data.datasets[0].data[0] + '%';
                        } else {
                            return null;
                        }
                    },
                    anchor: 'center',
                    align: 'center'
                }
            }
        }
    });
</script>
@endsection