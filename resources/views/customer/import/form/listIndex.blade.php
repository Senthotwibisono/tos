@extends('partial.customer.main')
@section('custom_styles')

@endsection

@section('content')

<div class="page-header">
    <h4>{{$title}}</h4>
</div>

<div class="page-content">
    <div class="card">
        <div class="card-header">
            <form action="/customer-import/formStoreFirst" method="post">
                @csrf
              <button type="submit" class="btn btn-success"> <i class="fa-solid fa-plus"></i>Tambah Extend Form</button>
            </form>
        </div>
        <div class="card-body">
            <div class="table table-responsive">
                <table class="table table-hover" id="formList">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>Customer</th>
                            <th>Do Number</th>
                            <th>Order Service</th>
                            <th>Expired Date</th>
                            <th>Bill Of Loading Number</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@section('custom_js')
<script>
    $(document).ready(function() {
        $('#formList').DataTable({
            processing: true,
            serverSide: true,
            ajax: '/customer-import/formData',
            columns: [
                {
                    data: 'id',
                    name: 'id',
                    className: 'text-center',
                    render: function(data, row){
                        const formId = row.id;
                        return `<div class="button-container">
                            <button class="btn btn-danger Delete" data-id="${data}"><i class="fa fa-trash"></i> Delete</button>
                            <a href="/customer-import/formFirstStepId=${data}" class="btn btn-warning"><i class="fa fa-pencil"></i></a>
                            </div>
                        `;
                    }
                },
                { data: 'customer.name', name: 'customer.name', className: 'text-center',
                    render: function(data, row){
                        return data ? data : '';
                    }
                 },
                { data: 'doOnline.do_no', name: 'doOnline.do_no', className: 'text-center',
                    render: function(data, row){
                        return data ? data : '';
                    }
                 },
                { data: 'service.name', name: 'service.name', className: 'text-center',
                    render: function(data, row){
                        return data ? data : '';
                    }
                 },
                { data: 'expired_date', name: 'expired_date', className: 'text-center',
                    render: function(data, row){
                        return data ? data : '';
                    }
                 },
                { data: 'doOnline.bl_no', name: 'doOnline.bl_no', className: 'text-center',
                    render: function(data, type, row) {
                        return data ? data : ''; // Return the data if it exists, otherwise return an empty string
                    }
                },
            ],
        });
    });
</script>

<script>
$(document).ready(function() {
    $('#formList').on('click', '.Delete', function() {
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
                            window.location.reload(); // Arahkan ke halaman beranda setelah penghapusan sukses
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