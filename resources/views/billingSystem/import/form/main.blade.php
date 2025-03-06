@extends ('partial.invoice.main')


@section('content')

<div class="page-heading">
  <h3><?= $title ?></h3>
  <p>Menu Untuk Delivery Form</p>

</div>
<div class="page-content">
  <section class="row">
    <div class="col-12 mb-3">
      <a href="{{ route('deliveryForm')}}" type="button" class="btn btn-success">
        <i class="fa-solid fa-plus"></i>
        Tambah Delivery Form
      </a>
    </div>

  </section>

  <section class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h4 class="card-title">Delivery Form Data Table</h4>
          <p>Tabel Form Delivery</p>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-12">
              <div class="table">
                <table class="table-hover" id="tableForm">
                  <thead style="white-space: nowrap;">
                    <tr>
                      <th>Customer</th>
                      <th>Do Number</th>
                      <th>Order Service</th>
                      <th>Expired Date</th>
                      <th>Bill Of Loading Number</th>
                      <th>Action</th>
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
</div>

@include('invoice.modal.modal')


@endsection
@section('custom_js')
<script>
  $(document).ready(function() {
    $('#tableForm').DataTable({
      processing: true,
      serverSide: true,
      ajax : '/billing/import/formData',
      columns : [
        { data:'customer', name: 'customer', className:'text-center'},
        { data:'doOnline', name: 'doOnline', className:'text-center'},
        { data:'service', name: 'service', className:'text-center'},
        { data:'expired', name: 'expired', className:'text-center'},
        { data:'blNo', name: 'blNo', className:'text-center'},
        { data:'edit', name: 'edit', className:'text-center'},
      ],
    })
  })
</script>
@endsection