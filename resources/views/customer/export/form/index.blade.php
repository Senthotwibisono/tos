@extends('partial.customer.main')
@section('custom_styles')

@endsection

@section('content')

<section>
    <div class="page-content">
        <div class="row mb-3">
            <div class="col-auto">
                <button type="button" class="btn btn-primary" id="createForm" onClick="createInvoice()">Create Form</button>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h4>List Index</h4>
            </div>
            <div class="card-body">
                <div class="table">
                    <table class="table-hover table-stripped" id="tableForm">
                        <thead>
                            <tr>
                                <th>Cutomer</th>
                                <th>Order Service</th>
                                <th>Booking Number</th>
                                <th>Edit</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@section('custom_js')
<script>
    $(document).ready(function () {
        $('#tableForm').dataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('customer.export.dataForm')}}',
            columns: [
                {name:'customer', data:'customer', className:'text-center'},
                {name:'service', data:'service', className:'text-center'},
                {name:'do_id', data:'do_id', className:'text-center'},
                {orderable:false, searchable:false, name:'edit', data:'edit', className:'text-center'},
                {orderable:false, searchable:false, name:'delete', data:'delete', className:'text-center'},
            ],
        });
    });
</script>

<script>

async function createInvoice(){
    Swal.fire({
        icon:'warning',
        title:'Aapakah anda yakin membuat form baru?',
        showCancelButton:true,
    }).then( async (result) => {
        if (result.isConfirmed) {
            
            showLoading();
            try {
                const url = '{{route('customer.export.createForm')}}';
                const response = await fetch(url,{
                    method : 'POST',
                    headers: {
                      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                });
                hideLoading();
                if (response.ok) {
                    var hasil = await response.json();
                    if (hasil.success) {
                        Swal.fire({
                            icon: 'success',
                            text: hasil.message,
                        }).then(() => {
                            window.location.href = '/customer-export/form/firstStepIndex-' + hasil.id;
                        });
                    }else{
                        Swal.fire({
                            icon: 'error',
                            title: 'Something Wrong in : ',
                            text: hasil.message,
                        });
                    }
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: response.status,
                        text: response.statusText,
                    });
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    text: error,
                });
            }
        }
    });
};

</script>


<script>
    async function deleteButton(event) {
        Swal.fire({
            icon: 'warning',
            title: 'Yakin menghapus data ini?',
            showCancelButton: true,
        }).then(async(result) => {
            if (result.isConfirmed) {
                showLoading();
                const id = event.getAttribute('data-id');
                const url = '{{route('customer.export.deleteForm')}}';
                const response = await fetch(url, {
                    method : 'POST',
                    headers: {
                        "Content-Type": "application/json",
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    body: JSON.stringify({ id: id }),
                });
                console.log(response);
                hideLoading();
                if (response.ok) {
                    var hasil = await response.json();
                    if (hasil.success) {
                        Swal.fire({
                            icon: 'success',
                            text: hasil.message,
                        }).then(() => {
                            location.reload();
                        });
                    }else{
                        Swal.fire({
                            icon: 'error',
                            text: hasil.message,
                        });
                    }
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: response.status,
                        text: response.statusText,
                    });
                }
            }
        });
    }
</script>
@endsection