@extends(Auth::user()->hasRole('customer') ? 'partial.customer.main' : 'partial.invoice.main')
@section('custom_styles')
<style>
     .text-left {
      text-align: left;
    }
</style>
@endsection
@section('content')

<div class="page-heading">
  <h3><?= $title ?></h3>
  
</div>

<div class="page-content">
  <section class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">
            <?= $title ?>
          </h3>
        </div>
        <form action="/billing/do/postManual" method="post" enctype="multipart/form-data" id="updateForm"> 
            @csrf
            <div class="card-body">
              <div class="row mb-3">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="">Do Number</label>
                        <input type="text" class="form-control" name="do_no" required>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="">BL Number</label>
                        <input type="text" class="form-control" name="bl_no" required>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="">Expired</label>
                        <input type="date" class="form-control" name="expired" required>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="">Customer</label>
                        <select name="customer" id="" class="js-example-basic-single select2 form-select" style="width: 100%;">
                            <option disabled selected value>Pilih Satu!</option>
                            @foreach($customers as $customer)
                                <option value="{{$customer->code}}">{{$customer->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="">Container</label>
                        <select name="container[]" id="" class="js-example-basic-multiple select2 form-select" multiple="multiple" style="width: 100%; height: 250%;">
                            @foreach($items as $item)
                                <option value="{{$item->container_no}}">{{$item->container_no}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
              </div>
              <hr>
            </div>
            <div class="card-footer">
                <div class="row mb-3">
                    <div class="col-sm-3">
                        <button type="button" id="updateButton" class="btn btn-success">Submit</button>
                        @if(Auth::user()->hasRole('customer'))
                        <a href="/customer-import/doOnline/index" class="btn btn-secondary">Back</a>
                        @else
                        <a href="/billing/dock-DO" class="btn btn-secondary">Back</a>
                        @endif
                    </div>
                </div>
            </div>
        </form>
      </div>
    </div>
  </section>
</div>

@endsection
@section('custom_js')

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Attach event listener to the update button
        document.getElementById('updateButton').addEventListener('click', function (e) {
            e.preventDefault(); // Prevent the default form submission

            let doNo = document.querySelector('[name="do_no"]').value.trim();
            let blNo = document.querySelector('[name="bl_no"]').value.trim();
            let expired = document.querySelector('[name="expired"]').value.trim();
            let customer = document.querySelector('[name="customer"]').value;
            let container = document.querySelector('[name="container[]"]'); // Select multiple container

            let errors = [];

            // Cek jika input kosong
            if (doNo === '') errors.push('DO Number');
            if (blNo === '') errors.push('BL Number');
            if (expired === '') errors.push('Expired Date');
            if (customer === '' || customer === 'Pilih Satu!') errors.push('Customer');
            if (!container.selectedOptions.length) errors.push('Container');

            // Jika ada field yang kosong, tampilkan peringatan
            if (errors.length > 0) {
                Swal.fire({
                    title: 'Incomplete Data!',
                    text: `Please fill out: ${errors.join(', ')}`,
                    icon: 'error',
                });
                return;
            }

            // Show SweetAlert confirmation dialog
            Swal.fire({
                title: 'Are you sure?',
                text: "",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, update it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit the form programmatically if confirmed
                        Swal.fire({
                        title: 'Processing...',
                        text: 'Please wait while we update the container',
                        icon: 'info',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                            willOpen: () => {
                                Swal.showLoading();
                            }
                        });
                    document.getElementById('updateForm').submit();
                }
            });
        });
    });
</script>
@endsection