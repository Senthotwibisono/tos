@extends ('partial.invoice.main')


@section('content')

<div class="page-heading">
  <h3><?= $title ?></h3>
  <p>Review Data Pranota Form & Kalkulasi</p>
</div>
<div class="page content mb-5">
  <form action="{{ route('extendCreate')}}" method="POST" enctype="multipart/form-data">
    @CSRF
    <input type="hidden" name="deliveryFormId" >
    <div class="card">
      <div class="card-body">
        <div class="row">
          <div class="col-12">
            <h4 class="card-title">
              Delivery Form Detail
            </h4>
            <p>Informasi Detil Formulir Delivery</p>
          </div>
          <div class="col-4">
            <div class="form-group">
              <label for="">Customer</label>
              <input type="text" name="cust_name" class="form-control" readonly value="{{$form->customer->name}}">
              <input type="hidden" name="cust_id" class="form-control" readonly value="{{$form->customer->id}}">
              <input type="hidden" name="inv_id" class="form-control" readonly value="{{$form->do_id}}">
              <input type="hidden" name="form_id" class="form-control" readonly value="{{$form->id}}">
            </div>
          </div>
          <div class="col-4">
            <div class="form-group">
              <label for="">NPWP</label>
              <input type="text" name="npwp" class="form-control" readonly value="{{$form->customer->npwp}}">
            </div>
          </div>
          <div class="col-4">
            <div class="form-group">
              <label for="">Expired Date</label>
              <input type="date" name="expired_date"class="form-control" readonly value="{{$form->expired_date}}">
            </div>
          </div>
          <div class="col-12">
            <div class="form-group">
              <label for="">Address</label>
              <input type="text" name="alamat" class="form-control" readonly value="{{$form->customer->alamat}}">
              <input type="hidden" name="fax" class="form-control" readonly value="{{$form->customer->fax}}">
            </div>
          </div>
          <div class="col-6">
            <div class="form-group">
              <label for="">Old Invoice Number</label>
              <input type="text" name="old_inv_no" class="form-control" readonly value="{{$form->oldInv->inv_no}}">
            </div>
          </div>
          <div class="col-6">
            <div class="form-group">
              <label for="">Old Invoice Expired</label>
              <input type="date" name="old_inv_no" class="form-control" readonly value="{{$form->disc_date}}">
            </div>
          </div>
        
        </div>

        <div class="row mt-3">
          <div class="col-12">
            <h4 class="card-title">
              Selected Container Detail
            </h4>
            <p>Informasi Detil Container</p>
          </div>
          <div class="col-12">
            <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns" id="table1">
              <thead>
                <tr>
                  <th>Container No</th>
                  <th>Vessel Name</th>
                  <th>Size</th>
                  <th>Type</th>
                  <th>CTR Status</th>
                  <th>CTR Intern Status</th>
                  <th>Gross</th>
                </tr>
              </thead>
              <tbody>
                @foreach($container as $cont)
                 <tr>
                  <td>{{$cont->container_no}}</td>
                  <td>{{$cont->ves_name}}</td>
                  <td>{{$cont->ctr_size}}</td>
                  <td>{{$cont->ctr_type}}</td>
                  <td>{{$cont->ctr_status}}</td>
                  <td>{{$cont->ctr_intern_status}}</td>
                  <td>{{$cont->gross}}</td>
                 </tr>
                 @endforeach
              </tbody>
            </table>
          </div>
        </div>
        <div class="row mt-3">
          @foreach($ctrGroup as $ukuran => $containers)
              <div class="col-12">
                <h4 class="card-title">
                  Pranota Summary 
                </h4>
                <p>Dengan Data Container <b>Container Size {{$ukuran}}</b></p>
              </div>
              <div class="col-12">
                <div class="card">
                  <div class="card-body">
                    <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns display ">
                      <thead>
                        <tr>
                          <th>Keterangan</th>
                          <th>Jumlah</th>
                          <th>Hari</th>
                          <th>Tarif Satuan</th>
                          <th>Amount</th>
                        </tr>
                      </thead>
                      <tbody>
                      @foreach($results as $result)
                        @if($result['ctr_size'] == $ukuran && $result['count_by'] != 'O')
                            <tr>
                                <td>{{ $result['keterangan'] }}</td>
                                <td>{{ $result['containerCount'] }}</td>
                                <td>{{ $result['jumlahHari'] }}</td>
                                <td>{{ $result['tarif'] }}</td>
                                <td>{{ $result['harga'] }}</td>
                            </tr>
                        @endif
                      @endforeach
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
              @endforeach
        
            <div class="col-12">
                <div class="card" style="border-radius:15px !important;background-color:#435ebe !important;">
                  <div class="card-body">
                    <div class="row text-white p-3">
                    <div class="col-6">
                        <h1 class="lead text-white">
                            Total Summary 
                        </h1>
                        <h4 class="text-white">Total Amount :</h4>
                        <h4 class="text-white">Admin :</h4>
                        <h4 class="text-white">Tax 11%      :</h4>
                        <h4 class="text-white">Grand Total  :</h4>
                    </div>

                      <div class="col-6 mt-4" style="text-align:right;">
                      <h4 class="text-white"> Rp. {{number_format($total, 0, ',', '.')}}</h4>
                        <input type="hidden" name="total" value="{{$total}}">
                        <input type="hidden" name="admin" value="{{$admin}}">
                        <h4 class="text-white"> Rp. {{number_format($admin, 0, ',', '.')}} </h4>
                        <input type="hidden" name="pajak" value="{{$pajak}}">
                        <h4 class="text-white">Rp. {{number_format($pajak, 0, ',', '.')}}</h4>
                        <input type="hidden" name="grand_total" value="{{$grandTotal}}">
                        <h4 class="color:#ff5265;"> Rp. {{number_format($grandTotal, 0, ',', '.')}} </h4>

                       
                      </div>
                    </div>
                  </div>
                </div>
              </div>
        </div>
        <div class="row mt-3">
          <div class="col-12 text-right">
            <button type="submit" class="btn btn-success"><i class="fa fa-check-circle"></i> Submit</button>
            <!-- <button class="btn btn-primary text-white opacity-50" data-toggle="tooltip" data-placement="top" title="Still on Development!">
              <a><i class="fa fa-pen"></i> Edit</a>
            </button> -->
            <a href="/billing/import/extend-editForm/{{$form->id}}"class="btn btn-primary text-white" data-toggle="tooltip" data-placement="top" title="Still on Development!"><i class="fa fa-pen"></i> Edit</a>
            <!-- <a type="button" class="btn btn-primary" style="opacity: 50%;"><i class="fa fa-pen "></i> Edit</a> -->
            <a class="btn btn-danger Delete" data-id="{{$form->id}}"><i class="fa fa-close"></i> Batal</a>
          </div>
        </div>

      </div>

    </div>
  </form>
</div>

@endsection
@section('custom_js')
<script>
$(document).ready(function() {
    $('.Delete').on('click', function() {
        var formId = $(this).data('id'); // Ambil ID dari data-id atribut

        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Anda tidak akan bisa mengembalikan ini!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/billing/import/delivery-deleteForm/' + formId, // Ganti dengan endpoint penghapusan Anda
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}' // Sertakan token CSRF untuk keamanan
                    },
                    success: function(response) {
                        Swal.fire(
                            'Dihapus!',
                            'Data berhasil dihapus.',
                            'success'
                        ).then(() => {
                            window.location.href = '/billing/import/extendIndex'; // Arahkan ke halaman beranda setelah penghapusan sukses
                        });
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        Swal.fire(
                            'Gagal!',
                            'Terjadi kesalahan saat menghapus data.',
                            'error'
                        );
                    }
                });
            }
        });
    });
});
</script>
@endsection