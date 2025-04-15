@extends ('partial.customer.main')


@section('content')

<div class="page-heading">
  <h3><?= $title ?></h3>
  <p>Review Data Pranota Form & Kalkulasi</p>
</div>
<div class="page content mb-5">
  <form action="{{route('customer.export.createInvoice')}}" method="POST" enctype="multipart/form-data" id="updateForm">
    @CSRF
    <input type="hidden" name="formId" value="{{$form->id}}">
    <div class="card">
      <div class="card-body">
        <div class="row">
          <div class="col-12">
            <h4 class="card-title">
              Export Form Detail
            </h4>
            <p>Informasi Detil Formulir Export</p>
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
              <label for="">Expired Date</label>
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
              <label for="">Booking Number</label>
              <input type="text" class="form-control" readonly value="{{$form->do_id ?? ''}}">
              <input type="hidden" class="form-control" name="do_id" readonly value="">
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
                @foreach($containers as $cont)
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
            @if($osk == 'Y')
            @include('customer.export.form.osk');
            @endif
            @if($os == 'Y')
            @include('customer.export.form.os');
            @endif
        </div>
        <div class="row mt-3">
            <div class="col-auto">
                <button type="button" onClick="createInvoice()" class="btn btn-success">Submit</button>
                <a href="/customer-export/form/firstStepIndex-{{$form->id}}" class="btn btn-warning">Back</a>
                <button type="button" class="btn btn-danger" onClick="deleteButton()">Delete</button>
            </div>
        </div>

      </div>

    </div>
  </form>
</div>

@endsection
@section('custom_js')

<script>
    async function createInvoice() {
        Swal.fire({
            icon: 'warning',
            title: 'Anda Yakin ?',
            text: 'Proforma akan terbit ketika anda melakukan sumbit',
            showCancelButton: true,
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('updateForm').submit();
            }
        });
        
    }
    async function deleteButton() {
        Swal.fire({
            icon: 'warning',
            title: 'Yakin menghapus data ini?',
            showCancelButton: true,
        }).then(async(result) => {
            if (result.isConfirmed) {
                showLoading();
                const id = {{$form->id}};
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
                            window.location.href = '{{ route('customer.export.indexForm')}}';
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