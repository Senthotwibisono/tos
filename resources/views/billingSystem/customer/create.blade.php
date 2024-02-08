@extends ('partial.invoice.main')


@section('content')

<div class="page-heading">
  <h3>Add Customer Data</h3>
  <p>Masukan Informasi Data Customer</p>

</div>
<div class="page-content">

  <section class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <form id="customer_form" action="{{ route('storeCust')}}" method="POST" enctype="multipart/form-data">
            <!-- <form id="customer_form" action="{{ route('customer.store') }}" method="post" enctype="multipart/form-data"> -->
            @CSRF
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label for="">Customer Name</label>
                  <input name="name" type="text" class="form-control" placeholder="Customer Name" required>
                </div>
                <div class="form-group">
                  <label for="">Customer Code</label>
                  <input name="code" type="text" class="form-control" placeholder="Customer Code" required>
                </div>
                <div class="form-group">
                  <label for="">Phone Number</label>
                  <input name="phone" type="text" class="form-control" placeholder="0878377728" required />
                </div>
                <div class="form-group">
                  <label for="">Email</label>
                  <input name="email" type="text" class="form-control" name="email"  required />
                </div>
                <div class="form-group">
                  <label for="">Fax Code</label>
                  <input name="fax" type="text" class="form-control" placeholder="13610" required>
                </div>
                <div class="form-group">
                  <label for="">NPWP</label>
                  <input name="npwp" type="text" class="form-control" placeholder="6673009219991" required>
                </div>
                <div class="form-group">
                  <label for="">Address</label>
                  <textarea required name="alamat" class="form-control" placeholder="Enter the address here" id="" cols="10" rows="3"></textarea>
                </div>

              </div>
            </div>
            <div class="row mt-5">
              <div class="col-12 text-right">
                <button type="submit" class="btn btn-success">Submit</button>
                <button type="button" class="btn btn-light-secondary" onclick="window.history.back();"><i class="bx bx-x d-block d-sm-none"></i><span class="d-none d-sm-block">Back</span></button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>
</div>

@include('invoice.modal.modal')


@endsection