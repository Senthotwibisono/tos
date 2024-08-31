@extends ('partial.invoice.main')


@section('content')

<div class="page-heading">
  <h3><?= $title ?></h3>
  <p>Review Data Pranota Form & Kalkulasi</p>
</div>
<div class="page content mb-5">
  <form action="{{ route('rental-repair-invoice-post')}}" method="POST" enctype="multipart/form-data">
    @CSRF
    <input type="hidden" name="formId" value="{{$form->id}}">
    <div class="card">
      <div class="card-body">
        <div class="row">
          <div class="col-12">
            <h4 class="card-title">
              Form Detail
            </h4>
            <p>Informasi Detil Formulir</p>
          </div>
          <div class="col-4">
            <div class="form-group">
              <label for="">Customer</label>
              <input type="text" name="cust_name" class="form-control" readonly value="{{$customer->name}}">
              <input type="hidden" name="cust_id" class="form-control" readonly value="{{$customer->id}}">
            </div>
          </div>
          <div class="col-4">
            <div class="form-group">
              <label for="">NPWP</label>
              <input type="text" name="npwp" class="form-control" readonly value="{{$customer->npwp}}">
            </div>
          </div>
          
          <div class="col-4">
            <div class="form-group">
              <label for="">Address</label>
              <input type="text" name="alamat" class="form-control" readonly value="{{$customer->alamat}}">
              <input type="hidden" name="fax" class="form-control" readonly value="{{$customer->fax}}">
            </div>
          </div>
          <div class="col-6">
            <div class="form-group">
              <label for="">Order Service</label>
              <input type="text" class="form-control" name="os_name"readonly value="{{$service->name}}">
              <input type="hidden" class="form-control" name="os_id"readonly value="{{$service->id}}">
            </div>
          </div>
          <div class="col-6">
            <div class="form-group">
              <label for="">Jumlah Container</label>
              <input type="text" class="form-control" readonly value="{{$form->palka}}">
            </div>
          </div>

        </div>

        <!--  -->
        
        <div class="row mt-3">
          @include('billingSystem.rental-repair.form.preInvoice.ds')
        </div>
        <div class="row mt-3">
          <div class="col-12 text-right">
            <button type="submit" class="btn btn-success"><i class="fa fa-check-circle"></i> Submit</button>
            <a href="/renta&repair-create-edit-{{$form->id}}" class="btn btn-primary text-white"><i class="fa fa-pen"></i> Edit</a>
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
                            window.location.href = '/renta&repair'; // Arahkan ke halaman beranda setelah penghapusan sukses
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