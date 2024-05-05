<!DOCTYPE html>
<html lang="en">

<head>
  @include('partial.head')
</head>

<body>
  <nav class="navbar navbar-light">
    <div class="container-fluid">
      <button onclick="goBack()" style="background: none; border: none;">
        <i class="bi bi-chevron-left" style="font-size: 30px; color: blacks;"></i>
      </button>
  </nav>

  <!-- content -->
  <div class="container">
    <div class="card mt-5">
      <div class="card-header">
        <h4 class="card-title">{{$truck}}</h4>
        <br>
        <button type="button" class="btn btn-outline-success ButtonPlacemenetContainerLuar" data-bs-toggle="modal" data-bs-target="#PlacemenetContainerLuar">Placement</i></button>
      </div>
      <div class="card-body">
        <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns" id="table1">
          <thead>
            <tr>
              <th>R.O NO</th>
              <th>Truck No</th>
              <th>Container No</th>
              <th>Detail</th>
          </thead>
          <tbody>
            @foreach($truck_table as $ro)
            <tr>
              <td>{{$ro->ro_no}}</td>
              <td>{{$ro->truck_no}}</td>
              <td>{{$ro->container_no}}</td>
              <td>{{$ro->container_key}}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <!-- end content -->

  <!-- Modal -->
  <div class="modal fade text-left" id="PlacemenetContainerLuar" role="dialog" aria-labelledby="myModalLabel110" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
      <div class="modal-content">
        <div class="modal-header bg-success">
          <h5 class="modal-title white" id="myModalLabel110">Stuffing</h5>
          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><i data-feather="x"></i></button>
        </div>
        <div class="modal-body">
          <!-- form -->
          <div class="form-body" id="place_cont">
            <div class="row">

              <div class="col-12">
                <div class="form-group">
                  <label for="first-name-vertical">Truck No</label>
                  <input type="text" id="truck" value="{{$truck}}" class="form-control" name="truck_no" readonly>
                  <input type="text" id="truck_id" value="{{$truck_id}}" class="form-control" name="truck_no" readonly>
                </div>
              </div>
              <div class="col-12">
                <div class="form-group">
                  <label for="first-name-vertical">Choose Vessel</label>
                  <select class="choices form-select" id="Vessel" name="ves_id" required>
                    <option value="">Select Container</option>
                    @foreach($vessel as $ves)
                    <option value="{{$ves->ves_id}}">{{$ves->ves_name}}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="row">
                <div class="col-4">
                  <div class="form-group">
                    <label for="first-name-vertical">Vessel Name</label>
                    <input type="text" id="nama-kapal" class="form-control" readonly>
                  </div>
                </div>
                <div class="col-4">
                  <div class="form-group">
                    <label for="first-name-vertical">Vessel Code</label>
                    <input type="text" id="kode-kapal" class="form-control" readonly>
                  </div>
                </div>
                <div class="col-4">
                  <div class="form-group">
                    <label for="first-name-vertical">Voy No</label>
                    <input type="text" id="nomor-voyage" class="form-control" name="ctr_type" readonly>
                  </div>
                </div>
              </div>
              <div class="col-12">
                  <div class="form-group">
                      <label for="first-name-vertical">Alat</label>
                      <select class="choices form-select" id="alat" required>
                          <option value="">Pilih Alata</option>
                          @foreach($alat as $alt)
                          <option value="{{$alt->id}}">{{$alt->name}}</option>
                          @endforeach
                      </select>
                  </div>
                  {{ csrf_field()}}
              </div>
              <div class="col-12">
                  <div class="form-group">
                      <label for="first-name-vertical">Op Alat</label>
                      <select class="choices form-select" id="operator">
                          <option disabeled selected value>Pilih Satu!</option>
                          @foreach($operator as $opr)
                          <option value="{{$opr->id}}">{{$opr->name}}</option>
                          @endforeach
                      </select>
                  </div>
              </div>
              <div class="col-12">
                <div class="form-group">
                  <label for="first-name-vertical">Choose Container Number</label>
                  <select class="choices form-select" id="key" name="container_key" required>
                    <option value="" disabled selected>Select Container</option>
                    @foreach($container as $item)
                    <option value="{{$item->container_key}}">{{$item->container_no}}</option>
                    @endforeach
                  </select>
                  <input type="hidden" id="container_no" class="form-control" name="container_no">
                </div>
                {{ csrf_field()}}
              </div>

              <h4>Stuffing Yard</h4>
              <div class="col-12" style="border:1px solid blue;">
                <div class="row">

                  <div class="col-3">
                    <div class="form-group">
                      <label for="first-name-vertical">Blok</label>
                      <select class="choices form-select" id="block" name="yard_block" required>
                        <option value="">-</option>
                        @foreach($yard_block as $block)
                        <option value="{{$block}}">{{$block}}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-3">
                    <div class="form-group">
                      <label for="first-name-vertical">Slot</label>
                      <select class="choices form-select" id="slot" name="yard_slot" required>
                        <option value="">-</option>
                        @foreach($yard_slot as $slot)
                        <option value="{{$slot}}">{{$slot}}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-3">
                    <div class="form-group">
                      <label for="first-name-vertical">Row</label>
                      <select class="choices form-select" id="row" name="yard_row" required>
                        <option value="">-</option>
                        @foreach($yard_row as $row)
                        <option value="{{$row}}">{{$row}}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-3">
                    <div class="form-group">
                      <label for="first-name-vertical">Tier</label>
                      <select class="choices form-select" id="tier" name="yard_tier" required>
                        <option value="">-</option>
                        @foreach($yard_tier as $tier)
                        <option value="{{$tier}}">{{$tier}}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="form-group">
                      <label for="first-name-vertical">Planner Place</label>
                      <input type="text" id="user" class="form-control" value="{{ Auth::user()->name }}" name="user_id" readonly>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-light-secondary d-block d-sm-none" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-success ml-1 d-block d-sm-none update_place_cont_luar">Confirm</button>

              <button type="button" class="btn btn-light-secondary d-none d-sm-block" data-bs-dismiss="modal"> <i class="bx bx-x"></i> Close</button>
              <button type="submit" class="btn btn-success ml-1 d-none d-sm-block update_place_cont_luar"> <i class="bx bx-check"></i> Confirm</button>
            </div>
          </div>
        </div>
      </div>


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
      <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
      <script src="https://cdn.datatables.net/v/bs5/dt-1.12.1/datatables.min.js"></script>
      <script>
        function goBack() {
          window.history.back();
        }
      </script>


      <script>
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
        $(document).ready(function() {
          $('#Vessel').on('change', function() {
            let id = $(this).val();
            $.ajax({
              type: 'POST',
              url: '/get-vessel-in-stuffing',
              data: {
                ves_id: id
              },
              success: function(response) {

                $('#nama-kapal').val(response.ves_name);
                $('#kode-kapal').val(response.ves_code);
                $('#nomor-voyage').val(response.voy_no);
              },
              error: function(data) {
                console.log('error:', data);
              },
            });
          });
        });
      </script>

      <script>
        $(document).on('click', '.update_place_cont_luar', function(e) {
          e.preventDefault();

          var ves_id = $('#Vessel').val();
          var ves_name = $('#nama-kapal').val();
          var ves_code = $('#kode-kapal').val();
          var voy_no = $('#nomor-voyage').val();
          var container_key = $('#key').val();
          var container_no = $('#container_no').val();
          var yard_block = $('#block').val();
          var yard_slot = $('#slot').val();
          var yard_raw = $('#raw').val();
          var yard_tier = $('#tier').val();
          var truck_no = $('#truck').val();
          var ro_id_gati = $('#id_truck').val();
          var alat = $('#alat').val();
          var data = {

            'ves_id': $('#Vessel').val(),
            'ves_name': $('#nama-kapal').val(),
            'ves_code': $('#kode-kapal').val(),
            'voy_no': $('#nomor-voyage').val(),
            'container_key': $('#key').val(),
            'container_no': $('#container_no').val(),
            'yard_block': $('#block').val(),
            'yard_slot': $('#slot').val(),
            'yard_row': $('#row').val(),
            'yard_tier': $('#tier').val(),
            'user_id': $('#user').val(),
            'truck_no': $('#truck').val(),
            'ro_id_gati': $('#id_truck').val(),
            'alat': $('#alat').val(),
            'operator': $('#operator').val(),

          }
          $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
          });
          Swal.fire({
            title: 'Are you Sure?',
            text: "Container " + container_no + " will be placed at Block " + yard_block + " Slot " + yard_slot + " Raw " + yard_raw + " and Tier " + yard_tier,
            icon: 'warning',
            showDenyButton: false,
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Confirm',
          }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {

              $.ajax({
                type: 'POST',
                url: '/stuffing-confirm-out-placement-luar',
                data: data,
                cache: false,
                dataType: 'json',
                success: function(response) {
                  console.log(response);
                  if (response.success) {
                    Swal.fire('Saved!', '', 'success')
                      .then(() => {
                        // Memuat ulang halaman setelah berhasil menyimpan data
                        window.location.reload();
                      });
                  } else {
                    Swal.fire('Error', response.message, 'error');
                  }
                },
                error: function(response) {
                  var errors = response.responseJSON.errors;
                  if (errors) {
                    var errorMessage = '';
                    $.each(errors, function(key, value) {
                      errorMessage += value[0] + '<br>';
                    });
                    Swal.fire({
                      icon: 'error',
                      title: 'Validation Error',
                      html: errorMessage,
                    });
                  } else {
                    console.log('error:', response);
                  }
                },
              });

            } else if (result.isDenied) {
              Swal.fire('Changes are not saved', '', 'info')
            }


          })

        });
      </script>
</body>

</html>