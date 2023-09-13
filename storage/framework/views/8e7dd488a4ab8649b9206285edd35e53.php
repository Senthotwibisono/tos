<?php $__env->startSection('content'); ?>

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

            <a href=""><img class="logoicon2" style="position: absolute; left: -250px; top: -100px;" src="<?php echo e(asset('logo/ICON2.png')); ?>" alt="Logo"></a>
            <div style="position:  top: -200px;" id="lottie-animation"></div>
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
                <?php $__currentLoopData = $history_container; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $history): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                  <td>
                    <p class=" mb-0"><?php echo e($history->oper_name); ?></p>
                  </td>
                  <td>
                    <?php if($history->operation_name === 'ANNI'): ?>
                    <p class=" mb-0">Recive container <b><?php echo e($history->container_no); ?></b> from ship <b><?php echo e($history->ves_code); ?></b> <br>
                      at <?php echo e($history->update_time); ?></p>

                    <?php elseif($history->operation_name === 'CORANNI'): ?>
                    <p class=" mb-0">Edit container profile <b><?php echo e($history->container_no); ?></b> from ship <b><?php echo e($history->ves_code); ?></b> <br>
                      at <?php echo e($history->update_time); ?></p>

                    <?php elseif($history->operation_name === 'DISC'): ?>
                    <p class=" mb-0">Confirm container <b><?php echo e($history->container_no); ?></b> from ship <b><?php echo e($history->ves_code); ?></b> <br>
                      at <?php echo e($history->update_time); ?></p>

                    <?php elseif($history->operation_name === 'PLC'): ?>
                    <p class=" mb-0">Placed container <b><?php echo e($history->container_no); ?></b> to <b>Block <?php echo e($history->yard_blok); ?></b> <b>Slot <?php echo e($history->yard_slot); ?></b> <b>Row <?php echo e($history->yard_row); ?></b> <b>Tier <?php echo e($history->yard_tier); ?></b><br>
                      at <?php echo e($history->update_time); ?></p>

                    <?php elseif($history->operation_name === 'STR'): ?>
                    <p class=" mb-0">Move container <b><?php echo e($history->container_no); ?></b> to <b>Block <?php echo e($history->yard_blok); ?></b> <b>Slot <?php echo e($history->yard_slot); ?></b> <b>Row <?php echo e($history->yard_row); ?></b> <b>Tier <?php echo e($history->yard_tier); ?></b><br>
                      at <?php echo e($history->update_time); ?></p>

                    <?php elseif($history->operation_name === 'GATI'): ?>
                    <p class=" mb-0">Get permit truck <b><?php echo e($history->truck_no); ?></b> to take <b><?php echo e($history->container_no); ?></b> from <b>Block <?php echo e($history->yard_blok); ?></b> <b>Slot <?php echo e($history->yard_slot); ?></b> <b>Row <?php echo e($history->yard_row); ?></b> <b>Tier <?php echo e($history->yard_tier); ?></b><br>
                      at <?php echo e($history->truck_in_date); ?></p>

                    <?php elseif($history->operation_name === 'GATO'): ?>
                    <p class=" mb-0">Allowed truck <b><?php echo e($history->truck_no); ?></b> to take out<b><?php echo e($history->container_no); ?></b> from <b>Block <?php echo e($history->yard_blok); ?></b> <b>Slot <?php echo e($history->yard_slot); ?></b> <b>Row <?php echo e($history->yard_row); ?></b> <b>Tier <?php echo e($history->yard_tier); ?></b><br>
                      at <?php echo e($history->truck_out_date); ?></p>
                    <?php endif; ?>
                  </td>
                  </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
        <?php $__currentLoopData = $vessel_voyage; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $voy): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="recent-message d-flex px-4 py-3">
          <div class="avatar avatar-lg">
            <div><i class="fa fa-ship"></i></div>
          </div>
          <div class="name ms-4">
            <h5 class="mb-1"><?php echo e($voy->ves_name); ?> || <?php echo e($voy->voy_out); ?></h5>
            <h6 class="text-muted mb-0"><?php echo e($voy->etd_date); ?></h6>
          </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
        <div id="chart-visitors-profile"></div>
      </div>
    </div>
  </div>

</section>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('custom_js'); ?>
<script src="<?php echo e(asset('vendor/components/jquery/jquery.min.js')); ?>"></script>
<script src="<?php echo e(asset('select2/dist/js/select2.full.min.js')); ?>"></script>
<script src="<?php echo e(asset('dist/assets/extensions/sweetalert2/sweetalert2.min.js')); ?>"></script>
<script src="<?php echo e(asset('dist/assets/js/pages/sweetalert2.js')); ?>"></script>
<script src="<?php echo e(asset('dist/assets/extensions/apexcharts/apexcharts.min.js')); ?>"></script>
<!-- <script src="<?php echo e(asset('dist/assets/js/pages/dashboard.js')); ?>"></script> -->
<script src="<?php echo e(asset('lottifiles/lokal.min.js')); ?>"></script>


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
  let optionsVisitorsProfile = {
    series: [<?php echo json_encode($countNotNull, 15, 512) ?>, <?php echo json_encode($countNull, 15, 512) ?>],
    labels: ['Terisi', 'Kosong'],
    colors: ['#ff0000', '#55c6e8'],
    chart: {
      type: 'donut',
      width: '100%',
      height: '350px'
    },
    legend: {
      position: 'bottom'
    },
    plotOptions: {
      pie: {
        donut: {
          size: '30%'
        }
      }
    }
  }

  var optionsEurope = {
    series: [{
      name: 'series1',
      data: [310, 800, 600, 430, 540, 340, 605, 805, 430, 540, 340, 605]
    }],
    chart: {
      height: 80,
      type: 'bar',
      toolbar: {
        show: false,
      },
    },
    colors: ['#5350e9'],
    stroke: {
      width: 2,
    },
    grid: {
      show: false,
    },
    dataLabels: {
      enabled: false
    },
    xaxis: {
      type: 'datetime',
      categories: ["2018-09-19T00:00:00.000Z", "2018-09-19T01:30:00.000Z", "2018-09-19T02:30:00.000Z", "2018-09-19T03:30:00.000Z", "2018-09-19T04:30:00.000Z", "2018-09-19T05:30:00.000Z", "2018-09-19T06:30:00.000Z", "2018-09-19T07:30:00.000Z", "2018-09-19T08:30:00.000Z", "2018-09-19T09:30:00.000Z", "2018-09-19T10:30:00.000Z", "2018-09-19T11:30:00.000Z"],
      axisBorder: {
        show: false
      },
      axisTicks: {
        show: false
      },
      labels: {
        show: false,
      }
    },
    show: false,
    yaxis: {
      labels: {
        show: false,
      },
    },
    tooltip: {
      x: {
        format: 'dd/MM/yy HH:mm'
      },
    },
  };

  let optionsAmerica = {
    ...optionsEurope,
    colors: ['#008b75'],
  }
  let optionsIndonesia = {
    ...optionsEurope,
    colors: ['#dc3545'],
  }


  var chartEurope = new ApexCharts(document.querySelector("#chart-europe"), optionsEurope);
  var chartAmerica = new ApexCharts(document.querySelector("#chart-america"), optionsAmerica);
  var chartIndonesia = new ApexCharts(document.querySelector("#chart-indonesia"), optionsIndonesia);

  chartIndonesia.render();
  chartAmerica.render();
  chartEurope.render();
  var chartVisitorsProfile = new ApexCharts(document.getElementById('chart-visitors-profile'), optionsVisitorsProfile)
  chartVisitorsProfile.render()
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('partial.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\CTOS\Dev\Frontend\tos-dev-local\resources\views/dashboard.blade.php ENDPATH**/ ?>