@extends('partial.main')
@section('custom_styles')
<link rel="stylesheet" href="{{asset('dist/assets/extensions/flatpickr/flatpickr.min.css')}}">
<style>
</style>
@endsection

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>{{$title}}</h3>
                    <p class="text-subtitle text-muted"></p>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-3">
                    <p>Pilih Tanggal</p> 
                </div>
            </div>
            <div class="row">
                <div class="col-3">
                    <input type="date" class="form-control flatpickr-range mb-3" id="time" placeholder="Select date..">
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
                    <th>NO</th>
                    <th>Container No</th>
                    <th>Batal dari Kapal</th>
                    <th>Posisi Container</th>
                    <th>Action</th>
                    <th>Kapal Baru</th>
                </thead>
                <tbody>
                @foreach($batalMuat as $cont)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$cont->container_no}}</td>
                    <td>{{$cont->old_ves_name}} || {{$cont->old_voy_no}}</td>
                    @if($cont->cont->ctr_intern_status == '49')
                        <td>Belum Masuk IKS</td>
                    @elseif($cont->cont->ctr_intern_status == '51')
                        <td>Gate In Reciving</td>
                    @elseif($cont->cont->ctr_intern_status == '53' || $cont->cont->ctr_intern_status == '03')
                        <td>Yard at Blok <strong>{{$cont->cont->yard_block}}</strong> Slot <strong>{{$cont->cont->yard_slot}}</strong> Row <strong>{{$cont->cont->yard_row}}</strong> Tier <strong>{{$cont->cont->yard_tier}}</strong></td>
                    @elseif($cont->cont->ctr_intern_status == '10')
                        <td>Gate In Delivery (Dalam Proses Keluar)</td>
                    @elseif($cont->cont->ctr_intern_status == '09')
                        <td>Gate Out Delivery (Sudah Meninggalkan Lapangan)</td>
                    @elseif($cont->cont->ctr_intern_status == '56')
                        <td>Sudah di Kapal Baru</td>
                    @endif

                    @if($cont->ctr_action == "OUT")
                    <td><strong>Keluar Dari IKS</strong></td>
                    @elseif($cont->cont == null)
                    <td><strong>Belum Di Proses</strong></td>
                    @else
                    <td><strong>Pindah Kapal</strong></td>
                    @endif

                    @if($cont->ctr_action == "OUT")
                    <td><strong>Keluar Dari IKS</strong></td>
                    @elseif($cont->cont == null)
                    <td><strong>Belum Di Proses</strong></td>
                    @else
                    <td><strong>{{$cont->new_ves_name}} || {{$cont->new_voy_no}}</strong></td>
                    @endif
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection

@section('custom_js')
<script src="{{asset('dist/assets/extensions/flatpickr/flatpickr.min.js')}}"></script>
<script src="{{asset('dist/assets/js/pages/date-picker.js')}}"></script>


<script>
     $(document).on('click', '.search', function(e) {
        e.preventDefault();
        var created_at = $('#time').val();
        var data = {
          'created_at' : $('#time').val(),
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
              url: '/get-data-batal-muat',
              data: data,
              cache: false,
              dataType: 'json',
              success: function(response) {
                console.log(response);
                if (response.success) {
                  Swal.fire('Saved!', '', 'success')
                  .then(() => {
                            
                    var url = '/laporan-batalMuat?created_at=' + created_at;
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
