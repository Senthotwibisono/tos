@extends ('partial.invoice.main')
<!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css"> -->


@section('content')

<div class="page-heading text-center">
  <h3>Billing System</h3>
  <p>Pilihan Menu Billing System</p>

</div>


<div class="page-content">

  <div class="row mt-0">

    <div class="col-sm-6">
      <section>
        <div class="text-center">
          <h4>Invoice DS & DSK</h4>
        </div>
        <div class="row">
          
          <div class="col-6">
            <div class="card">
              <div class="card-header">
                <div class="text-center">
                  <h4>DS</h4>
                </div>
              </div>
              <div class="card-body">
                <canvas id="myDonutChartDS"></canvas>
              </div>
            </div>
          </div>
          
          <div class="col-6">
            <div class="card">
              <div class="card-header">
                <div class="text-center">
                  <h4>DSK</h4>
                </div>
              </div>
              <div class="card-body">
                <canvas id="myDonutChartDSK"></canvas>
              </div>
            </div>
          </div>

        </div>
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header text-center">
                <h4>Summary</h4>
              </div>
              <div class="card-body">
                <div class="table table-responsive">
                  <table class="table">
                    <thead>
                      <tr>
                        <th></th>
                        <th>Paid</th>
                        <th>Piutang</th>
                        <th>Unpaid</th>
                        <th>Total</th>
                      </tr>
                      <tbody>
                        <tr>
                          <th>DS</th>
                          <td><p>Rp{{number_format($ds->where('lunas', '=', 'Y')->sum('grand_total'), 2, ',', '.')}}</p></td>
                          <td><p>Rp{{number_format($ds->where('lunas', '=', 'P')->sum('grand_total'), 2, ',', '.')}}</p></td>
                          <td><p>Rp{{number_format($ds->where('lunas', '=', 'N')->sum('grand_total'), 2, ',', '.')}}</p></td>
                          <td><p>Rp{{number_format($ds->sum('grand_total'), 2, ',', '.')}}</p></td>
                        </tr>
                        <tr>
                          <th>DSK</th>
                          <td><p>Rp{{number_format($dsk->where('lunas', '=', 'Y')->sum('grand_total'), 2, ',', '.')}}</p></td>
                          <td><p>Rp{{number_format($dsk->where('lunas', '=', 'P')->sum('grand_total'), 2, ',', '.')}}</p></td>
                          <td><p>Rp{{number_format($dsk->where('lunas', '=', 'N')->sum('grand_total'), 2, ',', '.')}}</p></td>
                          <td><p>Rp{{number_format($dsk->sum('grand_total'), 2, ',', '.')}}</p></td>
                        </tr>
                        <tr>
                          <th></th>
                          <td><strong>Rp{{number_format($dsk->where('lunas', '=', 'Y')->sum('grand_total') + $ds->where('lunas', '=', 'Y')->sum('grand_total'), 2, ',', '.')}}</strong></td>
                          <td><strong>Rp{{number_format($dsk->where('lunas', '=', 'P')->sum('grand_total') + $ds->where('lunas', '=', 'P')->sum('grand_total'), 2, ',', '.')}}</strong></td>
                          <td><strong>Rp{{number_format($dsk->where('lunas', '=', 'N')->sum('grand_total') + $ds->where('lunas', '=', 'N')->sum('grand_total'), 2, ',', '.')}}</strong></td>
                          <td><strong>Rp{{number_format($dsk->sum('grand_total') + $ds->sum('grand_total'), 2, ',', '.')}}</strong></td>
                        </tr>
                      </tbody>
                    </thead>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>


    <div class="col-sm-6">
      <section>
        <div class="text-center">
          <h4>Invoice OS & OSK</h4>
        </div>
        <div class="row">
          
          <div class="col-6">
            <div class="card">
              <div class="card-header">
                <div class="text-center">
                  <h4>OS</h4>
                </div>
              </div>
              <div class="card-body">
                <canvas id="myDonutChartOS"></canvas>
              </div>
            </div>
          </div>
          
          <div class="col-6">
            <div class="card">
              <div class="card-header">
                <div class="text-center">
                  <h4>OSK</h4>
                </div>
              </div>
              <div class="card-body">
                <canvas id="myDonutChartOSK"></canvas>
              </div>
            </div>
          </div>

        </div>
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header text-center">
                <h4>Summary</h4>
              </div>
              <div class="card-body">
                <div class="table table-responsive">
                  <table class="table">
                    <thead>
                      <tr>
                        <th></th>
                        <th>Paid</th>
                        <th>Piutang</th>
                        <th>Unpaid</th>
                        <th>Total</th>
                      </tr>
                      <tbody>
                        <tr>
                          <th>OS</th>
                          <td><p>Rp{{number_format($os->where('lunas', '=', 'Y')->sum('grand_total'), 2, ',', '.')}}</p></td>
                          <td><p>Rp{{number_format($os->where('lunas', '=', 'P')->sum('grand_total'), 2, ',', '.')}}</p></td>
                          <td><p>Rp{{number_format($os->where('lunas', '=', 'N')->sum('grand_total'), 2, ',', '.')}}</p></td>
                          <td><p>Rp{{number_format($os->sum('grand_total'), 2, ',', '.')}}</p></td>
                        </tr>
                        <tr>
                          <th>OSK</th>
                          <td><p>Rp{{number_format($osk->where('lunas', '=', 'Y')->sum('grand_total'), 2, ',', '.')}}</p></td>
                          <td><p>Rp{{number_format($osk->where('lunas', '=', 'P')->sum('grand_total'), 2, ',', '.')}}</p></td>
                          <td><p>Rp{{number_format($osk->where('lunas', '=', 'N')->sum('grand_total'), 2, ',', '.')}}</p></td>
                          <td><p>Rp{{number_format($osk->sum('grand_total'), 2, ',', '.')}}</p></td>
                        </tr>
                        <tr>
                          <th></th>
                          <td><strong>Rp{{number_format($osk->where('lunas', '=', 'Y')->sum('grand_total') + $os->where('lunas', '=', 'Y')->sum('grand_total'), 2, ',', '.')}}</strong></td>
                          <td><strong>Rp{{number_format($osk->where('lunas', '=', 'P')->sum('grand_total') + $os->where('lunas', '=', 'P')->sum('grand_total'), 2, ',', '.')}}</strong></td>
                          <td><strong>Rp{{number_format($osk->where('lunas', '=', 'N')->sum('grand_total') + $os->where('lunas', '=', 'N')->sum('grand_total'), 2, ',', '.')}}</strong></td>
                          <td><strong>Rp{{number_format($osk->sum('grand_total') + $os->sum('grand_total'), 2, ',', '.')}}</strong></td>
                        </tr>
                      </tbody>
                    </thead>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  
  </div>

</div>
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


<script>
  document.addEventListener('DOMContentLoaded', function() {
    var animation = lottie.loadAnimation({
      container: document.getElementById('lottie-animation'),
      renderer: 'svg',
      loop: true,
      autoplay: true,
      path: '/lottifiles/97854-imprint-genius-hero.json' // Ubah path sesuai dengan jalur file Lottie JSON Anda
    });
  });
</script>

<script>
    var ctx = document.getElementById('myDonutChartDS').getContext('2d');
    var donutChart = new Chart(ctx, {
        type: 'doughnut', // Tipe chart
        data: {
            labels: ['Paid', 'Piutang', 'Lunas'], // Label chart
            datasets: [{
                label: 'Kapasitas',
                data: [{{$paidDS}}, {{$piutangDS}}, {{$unpaidDS}}], // Data persentase
                backgroundColor: [
                    'rgba(40, 167, 69, 1)', // Warna untuk 'Paid' (Hijau Muda)
                    'rgba(255, 255, 0, 1)',    // Warna untuk 'Piutang' (Kuning Cerah)
                    'rgba(255, 69, 0, 1)'  // Warna untuk 'Tidak Terisi' (abu-abu muda)
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
<script>
    var ctx = document.getElementById('myDonutChartDSK').getContext('2d');
    var donutChart = new Chart(ctx, {
        type: 'doughnut', // Tipe chart
        data: {
            labels: ['Paid', 'Piutang', 'Lunas'], // Label chart
            datasets: [{
                label: 'Kapasitas',
                data: [{{$paidDSK}}, {{$piutangDSK}}, {{$unpaidDSK}}], // Data persentase
                backgroundColor: [
                    'rgba(40, 167, 69, 1)', // Warna untuk 'Paid' (Hijau Muda)
                    'rgba(255, 255, 0, 1)',    // Warna untuk 'Piutang' (Kuning Cerah)
                    'rgba(255, 69, 0, 1)'  // Warna untuk 'Tidak Terisi' (abu-abu muda)
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

<script>
    var ctx = document.getElementById('myDonutChartOS').getContext('2d');
    var donutChart = new Chart(ctx, {
        type: 'doughnut', // Tipe chart
        data: {
            labels: ['Paid', 'Piutang', 'Lunas'], // Label chart
            datasets: [{
                label: 'Kapasitas',
                data: [{{$paidOS}}, {{$piutangOS}}, {{$unpaidOS}}], // Data persentase
                backgroundColor: [
                    'rgba(40, 167, 69, 1)', // Warna untuk 'Paid' (Hijau Muda)
                    'rgba(255, 255, 0, 1)',    // Warna untuk 'Piutang' (Kuning Cerah)
                    'rgba(255, 69, 0, 1)'  // Warna untuk 'Tidak Terisi' (abu-abu muda)
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
<script>
    var ctx = document.getElementById('myDonutChartOSK').getContext('2d');
    var donutChart = new Chart(ctx, {
        type: 'doughnut', // Tipe chart
        data: {
            labels: ['Paid', 'Piutang', 'Lunas'], // Label chart
            datasets: [{
                label: 'Kapasitas',
                data: [{{$paidOSK}}, {{$piutangOSK}}, {{$unpaidOSK}}], // Data persentase
                backgroundColor: [
                    'rgba(40, 167, 69, 1)', // Warna untuk 'Paid' (Hijau Muda)
                    'rgba(255, 255, 0, 1)',    // Warna untuk 'Piutang' (Kuning Cerah)
                    'rgba(255, 69, 0, 1)'  // Warna untuk 'Tidak Terisi' (abu-abu muda)
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