@extends('partial.invoice.main')
@section('custom_styles')



<style>

.round-image {
  width: 100px; /* Sesuaikan dengan lebar yang diinginkan */
  height: 100px; /* Sesuaikan dengan tinggi yang diinginkan */
  border-radius: 50%;
  overflow: hidden;
}

.round-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  object-position: center;
}
</style>

@endsection
@section('content')

<div class="page-title">
    <h4>{{$title}}</h4>
</div>

<div class="page-content">
    <div class="card">
        <div class="card-header">
            <h6>List User</h6>
        </div>
        <div class="card-body">
            <div class="col-auto">
                <button type="button" class="btn btn-primary" onClick="openModal()"><i class="fas fa-plus"></i></button>
            </div>
            <div class="table">
                <table id="tableUser">
                    <thead>
                        <tr>
                            <th>Profile</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Edit</th>
                            <th>Assign Permission</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addManual" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable"role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Add Data Driver</h5>
                <button type="button" class="close" onClick="cancelModal()" aria-label="Close"> <i data-feather="x"></i></button>
            </div>
            <div class="modal-body">
                <div class="from-group">
                    <div class="col-12">
                        <label for="">Name</label>
                        <input type="text" name="name" id="name" class="form-control">
                    </div>
                    <div class="col-12">
                        <label for="">Email</label>
                        <input type="text" name="email" id="email" class="form-control">
                    </div>
                    <div class="col-12">
                        <label for="">Role</label>
                        <select name="role" id="role" class="js-example-basic-single form-select select2" style="width: 100%;">
                            <option disabled selected value>Pilih Satu!</option>
                            @foreach($roles as $role)
                                <option value="{{$role->name}}">{{$role->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label for="">Password</label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="isi jika diubah">
                        <input type="hidden" name="id" id="id" class="form-control">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary" onClick="cancelModal()"> <i class="bx bx-x d-block d-sm-none"></i> <span class="d-none d-sm-block">Cancel</span> </button>
                <button type="button" class="btn btn-primary ml-1" data-bs-dismiss="modal" onClick="postUser()"> <i class="bx bx-check d-block d-sm-none"></i> <span class="d-none d-sm-block">Submit</span> </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom_js')

<script>
    $(document).ready(function(){
        $('#tableUser').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{route('invoiceService.system.userData')}}',
            columns: [
                {name:'profile', data:'profile', className:'text-center'},
                {name:'name', data:'name', className:'text-center'},
                {name:'email', data:'email', className:'text-center'},
                {name:'roles', data:'roles', className:'text-center'},
                {name:'edit', data:'edit', className:'text-center'},
                {name:'permission', data:'permission', className:'text-center'},
            ],
        })
    })
</script>


<script>
    async function openModal(){
        showLoading();
        $('#addManual').modal('show').on('shown.bs.modal', function () {
            hideLoading();
        });
    }

    async function cancelModal() {
        showLoading();
        $('#addManual').modal('hide').on('hidden.bs.modal', function () {
            $('#addManual #name').val(null);
            $('#addManual #email').val(null);
            $('#addManual #role').val(null).trigger('change');
            $('#addManual #password').val(null);
            $('#addManual #id').val(null);
            hideLoading();
        });
    }

    async function editUser(event) {
        showLoading();
        const id = event.getAttribute('data-id');
        // console.log(id);
        const url = "{{route('invoiceService.system.userEdit')}}";

        const response = await fetch(url, {
            method: "POST",
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            body: JSON.stringify({ id: id }),
        });
        // console.log(response);
        hideLoading();

        if (response.ok) {
            const hasil = await response.json();
            if (hasil.success) {
                $('#addManual').modal('show');
                $('#addManual #name').val(hasil.data.name);
                $('#addManual #email').val(hasil.data.email);
                $('#addManual #password').val(hasil.data.password);
                $('#addManual #role').val(hasil.data.roles[0].name).trigger('change');
                $('#addManual #id').val(hasil.data.id);
            } else {
                errorHasil(hasil);
            }
        }else{
            errorResponse(response);
        }
    }

    async function postUser() {
        Swal.fire({
            icon: 'warning',
            title: 'Are you sure?',
            text: 'Data will updated when you submit this form',
            showCancelButton: true,
        }).then(async(result) => {
            if (result.isConfirmed) {
                showLoading();
                const id = document.getElementById('id').value;
                const name = document.getElementById('name').value;
                const email = document.getElementById('email').value;
                const password = document.getElementById('password').value;
                const role = document.getElementById('role').value;
                const data = {
                    id : id,
                    name : name,
                    email : email,
                    password : password,
                    role : role,
                };

                // console.log(data);
                const url = "{{route('invoiceService.system.userPost')}}";

                const response = await fetch(url, {
                    method: "POST",
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    body: JSON.stringify({ data: data }),
                }); 
                hideLoading();
                console.log(response);
                if (response.ok) {
                    const hasil = await response.json();
                    console.log(hasil);
                    if (hasil.success) {
                        successHasil(hasil);
                    } else {    
                        errorHasil(hasil);
                    }
                }else{
                    errorResponse(response);
                }
            } else {
                return ;
            }
        });
    }
</script>

@endsection