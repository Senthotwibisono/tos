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
          <form id="customer_form" action="/invoice/customer/register/editPost" method="POST" enctype="multipart/form-data">
            <!-- <form id="customer_form" action="{{ route('customer.store') }}" method="post" enctype="multipart/form-data"> -->
            @CSRF
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label for="">Name</label>
                  <input type="text" name="name" class="form-control" value="{{$user->name}}">
                  <input type="hidden" name="id" class="form-control" value="{{$user->id}}">
                </div>
                <div class="form-group">
                  <label for="">Customer</label>
                  <select name="customer_id[]" style="width:100%" class="js-example-basic-multiple" multiple="multiple">
                    <option disabled value>Pilih Satu!</option>
                    @foreach($custs as $cust)
                      <option value="{{$cust->id}}" {{ in_array($cust->id, $userInvoiceCustomerIds) ? 'selected' : '' }}>{{$cust->name}}</option>
                    @endforeach
                  </select>
                </div>
                <div class="form-group">
                  <label for="">Role</label>
                  <input name="role" type="text" class="form-control" value="customer" readonly>
                </div>
                <div class="form-group">
                  <label for="">Email</label>
                  <input name="email" type="email" class="form-control" value="{{$user->email}}" required>
                </div>
                <div class="form-group">
                  <label for="">Password</label>
                  <input name="password" type="password" class="form-control" placeholder="Kosongkan jika tidak mengubah password!!">
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