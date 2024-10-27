@extends('partial.customer.main')
@section('custom_styles')

@endsection

@section('content')

<div class="page-content">
    <div class="card">
        <div class="card-header">
            <h4>{{$title}}</h4>
        </div>
        <div class="card-body">
            @yield('table')
        </div>
    </div>
</div>

<div class="footer">
    <a href="/customer-import" class="btn btn-primary">Back</a>
</div>

@endsection

@section('custom_js')
@yield('table_js')

<script>
    $(document).on('click', '.Delete', function() {
        var formId = $(this).data('id'); // Ambil ID dari data-id atribut
        console.log(formId);

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
                    url: '/billing/import/delivery-deleteInvoice/' + formId, // Ganti dengan endpoint penghapusan Anda
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
</script>
@endsection
