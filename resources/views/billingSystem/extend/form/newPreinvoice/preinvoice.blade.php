@extends ('partial.customer.main')


@section('content')

<div class="page-heading">
  <h3><?= $title ?></h3>
  <p>Review Data Pranota Form & Kalkulasi</p>
</div>
<div class="page content mb-5">
  <form action="{{route('extend.form.createInvoice')}}" method="POST" enctype="multipart/form-data" id="updateForm">
    @CSRF
    <input type="hidden" name="formId" value="{{$form->id}}">
    <div class="card">
      <div class="card-body">
        <div class="row">
          <div class="col-12">
            <h4 class="card-title">
              Delivery Form Detail
            </h4>
            <p>Informasi Detil Formulir Delivery</p>
          </div>
          <div class="col-3">
            <div class="form-group">
              <label for="">Customer</label>
              <input type="text" name="cust_name" class="form-control" readonly value="{{$form->customer->name}}">
              <input type="hidden" name="cust_id" class="form-control" readonly value="{{$form->customer->id}}">
            </div>
          </div>
          <div class="col-3">
            <div class="form-group">
              <label for="">NPWP</label>
              <input type="text" name="npwp" class="form-control" readonly value="{{$form->customer->npwp}}">
            </div>
          </div>
          <div class="col-3">
            <div class="form-group">
              <label for="">Discharge Date</label>
              <input type="datetime-local" name="disc_date"class="form-control" readonly value="{{$form->disc_date}}">
            </div>
          </div>
          <div class="col-3">
            <div class="form-group">
              <label for="">Rencana Keluar</label>
              <input type="datetime-local" name="expired_date"class="form-control" readonly value="{{$form->expired_date}}">
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
              <label for="">Old Invoice</label>
              <input type="text" class="form-control" readonly value="{{$form->oldInv->inv_no}}">
              <input type="hidden" class="form-control" name="do_id" readonly value="{{$form->oldInv->id}}">
            </div>
          </div>
          <div class="col-6">
            <div class="form-group">
              <label for="">Order Service</label>
              <input type="text" class="form-control" name="os_name"readonly value="{{$form->service->name}}">
              <input type="hidden" class="form-control" name="os_id"readonly value="{{$form->service->id}}">

            
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
                @foreach($listContainers as $cont)
                    <tr>
                        <td>{{$cont->container_no ?? '-'}}</td>
                        <td>{{$cont->ves_name ?? '-'}}</td>
                        <td>{{$cont->ctr_size ?? '-'}}</td>
                        <td>{{$cont->ctr_type ?? '-'}}</td>
                        <td>{{$cont->ctr_status ?? '-'}}</td>
                        <td>{{$cont->ctr_intern_status ?? '-'}}</td>
                        <td>{{$cont->gross ?? '-'}}</td>
                    </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
        <div class="row mt-3">
              @if($dsk == 'Y')
               @include('billingSystem.extend.form.newPreinvoice.preinvoice.dsk')
              @endif     
              @if($ds == 'Y')
               @include('billingSystem.extend.form.newPreinvoice.preinvoice.ds')
              @endif     
        </div>
        <div class="row mt-3">
          <div class="col-12 text-right">
            <button type="button" id="updateButton" class="btn btn-success"><i class="fa fa-check-circle"></i> Submit</button>
            <!-- <button class="btn btn-primary text-white opacity-50" data-toggle="tooltip" data-placement="top" title="Still on Development!">
              <a><i class="fa fa-pen"></i> Edit</a>
            </button> -->
            <a href="{{route('extend.form.edit', ['id' => $form->id])}}" class="btn btn-primary text-white"><i class="fa fa-pen"></i> Edit</a>
            <!-- <a onclick="cancelAddCustomer();" type="button" class="btn btn-warning"><i class="fa fa-close"></i> Batal</a> -->
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
    document.addEventListener('DOMContentLoaded', function () {
        // Attach event listener to the update button
        document.getElementById('updateButton').addEventListener('click', function (e) {
            e.preventDefault(); // Prevent the default form submission

            // Show SweetAlert confirmation dialog
            Swal.fire({
                title: 'Are you sure?',
                text: "",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, update it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit the form programmatically if confirmed
                        showLoading();
                    document.getElementById('updateForm').submit();
                }
            });
        });
    });
</script>
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
                showLoading();
                $.ajax({
                    url: '/customer-extend/deleteInvoice', // Ganti dengan endpoint penghapusan Anda
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: formId, // Sertakan token CSRF untuk keamanan
                    },
                    success: function(response) {
                      hideLoading();
                        Swal.fire(
                            'Dihapus!',
                            response.message,
                            'success'
                        ).then(() => {
                            window.location.href = '/billing/import/extendIndexForm'; // Arahkan ke halaman beranda setelah penghapusan sukses
                        });
                    },
                    error: function(xhr) {
                      hideLoading();
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