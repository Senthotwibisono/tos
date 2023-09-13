@extends('partial.main')

@section('custom_styles')

@endsection
@section('content')

<div class="page-heading">
  <div class="page-title">
    <div class="row">
      <div class="col-12 col-md-6 order-md-1 order-last">
        <h3>Dokumen RO</h3>
      </div>

      <div class="col-12 col-md-6 order-md-2 order-first">
        <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
          <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">Doc R.O</li>
          </ol>
        </nav>
      </div>
    </div>
  </div>

 <section>
    <div class="card">
        <div class="card-header">
            <h4>Waiting to Out</h4>
        </div>
        <div class="card-body">
            <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns" id="table1">
              <thead>
                  <tr>
                      <th>No</th>
                      <th>Nomor R.O</th>
                      <th>Service</th>
                      <th>Jumlah</th>
                      <th>Tanggal Buat</th>
                      <th>Detail</th>
                  </tr>
              </thead>
              <tbody>
                @foreach($ro as $r)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$r->ro_no}}</td>
                    <td>
                        @if($r->stuffing_service === 'in')
                            Stuffing Dalam
                        @endif
                        @if($r->stuffing_service === 'out')
                            Stuffing Luar
                        @endif
                    </td>
                    <td>{{$r->jmlh_cont}}</td>
                    <td>{{$r->created_at}}</td>
                    <td>
                    <button type="button" class="btn btn-outline-success detail-ro" data-bs-toggle="modal" data-id="{{$r->ro_no}}">
                      <i class="fa-solid fa-eye"></i>
                      </button>
                    </td>
                </tr>
                @endforeach
              </tbody>
            </table>
        </div>
    </div>
 </section>


 <div class="modal fade text-left w-100" id="contRO" tabindex="-1" role="dialog" aria-labelledby="myModalLabel16" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success">
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
                        <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns" id="tableRO">
                    <thead>
                        <tr>
                            <th>R.O No</th>
                            <th>Container No</th>
                            <th>Truck No</th>
                                                     
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

<script>
 $(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
   

    $(document).on('click', '.detail-ro', function() {
        let id = $(this).data('id');
        $.ajax({
            type: 'GET',
            url: '/docs/dokumen/ro/detail-' + id,
            cache: false,
            data: {
                ro_no: id
            },
            dataType: 'json',
            success: function(response) {
                console.log(response);
                $('#contRO').modal('show');
                var tableBody = $('#contRO #tableRO tbody');
                tableBody.empty();
                if (response.data.cont === 0) {
                        var newRow = $('<tr>');
                        newRow.append('<td colspan="3">No Container Available</td>');
                        tableBody.append(newRow);
                    } else {
                        response.data.forEach(function(detail_cont) {
                            var newRow = $('<tr>');
                            newRow.append('<td>' + detail_cont.ro_no + '</td>');
                            newRow.append('<td>' + detail_cont.container_no + '</td>');
                            newRow.append('<td>' + detail_cont.truck_no + '</td>');
                            tableBody.append(newRow);
                        });
                        new simpleDatatables.DataTable('#tableRO');
                      }
            },
            error: function(data) {
                console.log('error:', data);
            }
        });
    });
});
</script>
@endsection