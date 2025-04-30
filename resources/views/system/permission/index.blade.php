@extends('partial.main')

@section('custom_styles')

@endsection

@section('content')

<section>
    <div class="page-header">
        <h4>{{$title}}</h4>
    </div>

    <div class="page-content">
        <div class="card">
            <div class="card-body">
                <div class="divider divider-left">
                    <div class="divider-text">
                        <h4>Form Permission</h4>
                    </div>
                </div>
                <div class="card">
                    <div class="row">
                        <div class="col-auto">
                            <label for="">Permission Name</label>
                            <input type="text" class="form-control" id="name">
                        </div>
                        <div class="col-auto">
                            <label for="">Guard Name</label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" id="guard_name" value="web" readonly>
                                <button type="button" class="btn btn-success" onClick="submitPermission()">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="divider divider-left">
                    <div class="divider-text">
                        <h4>List Data Permission</h4>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="table">
                            <table class="table-hover" id="permissionList">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Guard Name</th>
                                        <th>Created At</th>
                                        <th>Last Update</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@section('custom_js')
<script>
    $(document).ready(function(){
        $('#permissionList').dataTable({
            processing: true,
            serverSide: true,
            ajax: '{{route('system.permission.data')}}',
            columns: [
                {data:'name', name:'name', className:'text-center'},
                {data:'guard_name', name:'guard_name', className:'text-center'},
                {data:'created_at', name:'created_at', className:'text-center'},
                {data:'updated_at', name:'updated_at', className:'text-center'}
            ]
        })
    })
</script>

<script>
    async function submitPermission() {
        Swal.fire({
            icon: 'warning',
            title: 'Apakah Anda Yakin?',
            showCancelButton: true,
        }).then( async(result) => {
            if (result.isConfirmed) {
                showLoading();
                const name = document.getElementById('name').value;
                const guard_name = document.getElementById('guard_name').value;
                const data = {name, guard_name};

                console.log(data);

                const url = '{{route('system.permission.create')}}';

                const response = await fetch(url, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    body: JSON.stringify({ name: name, guard_name: guard_name }),
                });
                hideLoading();
                // console.log(response);
                if (response.ok) {
                    const hasil = await response.json();
                    if (hasil.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Data Stored',
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon : 'error',
                            title : 'Error',
                            text : hasil.message, 
                        });
                    };
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: response.status,
                        text: response.statusText
                    });
                };
            };
        });
    }
</script>
@endsection