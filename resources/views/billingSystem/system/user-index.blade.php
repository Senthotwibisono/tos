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
            ],
        })
    })
</script>


<script>
    async function openModal(){
        showLoading();

    }
</script>

@endsection