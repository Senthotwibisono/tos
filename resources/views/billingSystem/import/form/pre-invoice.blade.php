@extends ('partial.invoice.main')


@section('content')

<div class="page-heading">
  <h3><?= $title ?></h3>
  <p>Review Data Pranota Form & Kalkulasi</p>
</div>
<div class="page content mb-5">
  <form action="{{ route('invoiceImport')}}" method="POST" enctype="multipart/form-data">
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
              <label for="">Expired Date</label>
              <input type="date" name="expired_date"class="form-control" readonly value="{{$expired}}">
              <input type="hidden" name="discDate"class="form-control" readonly value="{{$discDate}}">
            </div>
          </div>
          <div class="col-12">
            <div class="form-group">
              <label for="">Address</label>
              <input type="text" name="alamat" class="form-control" readonly value="{{$customer->alamat}}">
              <input type="hidden" name="fax" class="form-control" readonly value="{{$customer->fax}}">
            </div>
          </div>
          <div class="col-6">
            <div class="form-group">
              <label for="">DO Number</label>
              <input type="text" class="form-control" readonly value="{{$doOnline->do_no}}">
              <input type="hidden" class="form-control" name="do_id" readonly value="{{$doOnline->id}}">
            </div>
          </div>
          <div class="col-6">
            <div class="form-group">
              <label for="">Order Service</label>
              <input type="text" class="form-control" name="os_name"readonly value="{{$service->name}}">
              <input type="hidden" class="form-control" name="os_id"readonly value="{{$service->id}}">

              <input type="hidden" name="container_key[]" value="{{$contInvoice}}">
              <input type="hidden" name="massa1" value="{{$massa1seharusnya}}">
              <input type="hidden" name="massa2" value="{{$massa2}}">
              <input type="hidden" name="massa3" value="{{$massa3}}">
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
               @foreach($selectCont as $cont)
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
            @if($service->id == 1 || $service->id == 2 || $service->id == 3)
             @include('billingSystem.import.form.preInvoice.dsk')
              @endif
              
              <!-- DS -->
              @if($service->id == 1 || $service->id == 3 || $service->id == 4 || $service->id == 5)
              @include('billingSystem.import.form.preInvoice.ds')

              @endif
              
           

         
        </div>
        <div class="row mt-3">
          <div class="col-12 text-right">
            <button type="submit" class="btn btn-success"><i class="fa fa-check-circle"></i> Submit</button>
            <button class="btn btn-primary text-white opacity-50" data-toggle="tooltip" data-placement="top" title="Still on Development!">
              <a><i class="fa fa-pen"></i> Edit</a>
            </button>
            <!-- <a type="button" class="btn btn-primary" style="opacity: 50%;"><i class="fa fa-pen "></i> Edit</a> -->
            <a onclick="cancelAddCustomer();" type="button" class="btn btn-warning"><i class="fa fa-close"></i> Batal</a>
          </div>
        </div>

      </div>

    </div>
  </form>
</div>

@endsection