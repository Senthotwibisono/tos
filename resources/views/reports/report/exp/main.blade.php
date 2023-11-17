@extends('partial.main')
@section('custom_style')

@endsection
@section('content')

<section>
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-4">
                <select class="choices form-control" id="kapal">
                    <option selected disabled default value="">Choose Vessel</option>
                    @foreach($kapal as $kpl)    
                        <option value="{{$kpl->ves_id}}">{{$kpl->ves_name}} -- {{$kpl->voy_out}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-3">
                        <button class="btn icon btn-info search"><i class="bi bi-search"></i></button>
            </div>
        </div>
    </div>
    <div class="card-body">
        <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns" id="table1">
            <thead>
              <tr>
                <th>No</th>
                <th>Name</th>
                <th>Code</th>
                <th>Voy</th>
                <th>Departure</th>
                <th>Count</th>
                <th>View</th>
              </tr>
            </thead>
            <tbody>
                @foreach($kapal as $kpl)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$kpl->ves_name}}</td>
                    <td>{{$kpl->ves_code}}</td>
                    <td>{{$kpl->voy_out}}</td>
                    <td>{{$kpl->deparature_date}}</td>
                    <td>{{ $containerKeyCounts[$kpl->ves_id] }}</td>
                    <td>
                    <a href="javascript:void(0)"class="btn icon icon-left btn-outline-info detail-cont-edi" data-id="{{$kpl->ves_id}}"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                        </svg> Detail</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="card-footer">

    </div>
</div>
</section>

<!-- EXP -->
<div class="modal fade text-left w-100" id="detail-edi" tabindex="-1" role="dialog" aria-labelledby="myModalLabel16" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h4 class="modal-title" id="myModalLabel16">Detail Container</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-md-12 border-right">
                    <div class="row" style="border-right: 2px solid blue;">
                        <h5>Container Fill</h5>
                        <div id="container-list">
                        <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns" id="tableDetail">
                    <thead>
                        <tr>
                            <th>Container No</th>
                            <th>Iso Code</th>
                            <th>Size</th>
                            <th>Type</th> 
                            <th>status </th>  
                            <th>Bay Row Tier</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Close</span>
                </button>
                <button type="button" class="btn btn-primary ml-1" data-bs-dismiss="modal">
                    <i class="bx bx-check d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Accept</span>
                </button>
            </div>
        </div>
    </div>
</div>


@endsection
@section('custom_js')
<script src="{{ asset('vendor/components/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{asset('dist/assets/extensions/sweetalert2/sweetalert2.min.js')}}"></script>
    <script src="{{asset('dist/assets/js/pages/sweetalert2.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>


    <script>
 $(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
   

    $(document).on('click', '.detail-cont-edi', function() {
        let id = $(this).data('id');
        $.ajax({
            type: 'GET',
            url: '/reports/detailCont-' + id,
            cache: false,
            data: {
                ves_id: id
            },
            dataType: 'json',
            success: function(response) {
                console.log(response);
                $('#detail-edi').modal('show');
                var tableBody = $('#detail-edi #tableDetail tbody');
                tableBody.empty();
                    if (response.data.cont === 0) {
                        var newRow = $('<tr>');
                        newRow.append('<td colspan="3">No Container Available</td>');
                        tableBody.append(newRow);
                    } else {
                        response.data.forEach(function(cont) {
                            var newRow = $('<tr>');
                            newRow.append('<td>' + cont.container_no + '</td>');
                            newRow.append('<td>' + cont.iso_code + '</td>');
                            newRow.append('<td>' + cont.ctr_size + '</td>');
                            newRow.append('<td>' + cont.ctr_type + '</td>');
                            newRow.append('<td>' + cont.ctr_status + '</td>');
                            newRow.append('<td>' + cont.bay_slot + cont.bay_row + cont.bay_tier + '</td>');
                          
                            tableBody.append(newRow);
                        });
                        new simpleDatatables.DataTable('#tableDetail');
                    }
            },
            error: function(data) {
                console.log('error:', data);
            }
        });
    });
});
</script>

<script>
     $(document).on('click', '.search', function(e) {
        e.preventDefault();

        var ves_id = $('#kapal').val();
        var data = {
    
          'ves_id': $('#kapal').val(),
        }
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
        Swal.fire({
          title: 'Yakin Membuat Laporan?',
          text: "",
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
              url: '/get-data-kapal',
              data: data,
              cache: false,
              dataType: 'json',
              success: function(response) {
                console.log(response);
                if (response.success) {
                  Swal.fire('Saved!', '', 'success')
                  .then(() => {
                            
                    var url = '/laporan-kapal?id=' + ves_id;
    window.open(url, '_blank');
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
@endsection