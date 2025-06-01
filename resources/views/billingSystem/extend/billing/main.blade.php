@extends ('partial.invoice.main')


@section('content')

<div class="page-heading">
  <h3><?= $title ?></h3>
  <p>Management Data Billing Delivery</p>

</div>
<div class="page-content">

  <section class="row">
    <div class="col-12 mb-3">
      <a href="{{ route('listForm-extend')}}" type="button" class="btn btn-primary">
        <i class="fa fa-folder"></i>
        Delivery Extend
      </a>
    </div>
    <div class="card">
      <div class="card-header">
        <h4>EXport to Zahir</h4>
        <form action="{{ route('zahir-invoice-extend')}}" method="GET" enctype="multipart/form-data">
          <div class="row">
            <div class="col-4">
              <div class="form-group">
                <label>Pick Start Date Range</label>
                <!-- <input name="start" type="date" class="form-control flatpickr-range mb-1" placeholder="09/05/2023" id="expired"> -->
                <input type="date" name="start" class="form-control" required>
              </div>
            </div>
            <div class="col-4">
              <div class="form-group">
                <label>Pick End Date Range</label>
                <!-- <input name="end" type="date" class="form-control flatpickr-range mb-1" placeholder="09/05/2023" id="expired"> -->
                <input type="date" name="end" class="form-control" required>
              </div>
            </div>
            <div class="col-4 mt-4">
              <button class="btn btn-info" type="submit"><i class=" fa fa-file"></i> Export Active Invoice to CSV</button>
            </div>
          </div>
        </form>
      </div>

      <div class="card-header">
      <h4>Report Delivery</h4>
        <form action="{{ route('report-invoice-extend-All')}}" method="GET" enctype="multipart/form-data">
          <div class="row">
            <div class="col-sm-3">
              <div class="form-group">
                <label>Pick Start Date Range</label>
                <input name="start" type="date" class="form-control flatpickr-range mb-1" placeholder="09/05/2023" id="expired">
                <!-- <input type="date" name="start" class="form-control" required> -->
              </div>
            </div>
            <div class="col-sm-3">
              <div class="form-group">
                <label>Pick End Date Range</label>
                <input name="end" type="date" class="form-control flatpickr-range mb-1" placeholder="09/05/2023" id="expired">
                <!-- <input type="date" name="end" class="form-control" required> -->
              </div>
            </div>
            <div class="col-sm-3">
              <div class="form-group">
                <label for="">Invoice Type</label>
                 <div class="row">
                    <div class="col-6">
                      <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="inv_type" value="XTD" id="checkbox-dsk" checked>
                        <label class="form-check-label" for="checkbox-dsk">DS-P</label>
                      </div>
                    </div>
                 </div>
              </div>
            </div>
            <div class="col-3 mt-4">
              <button class="btn btn-primary" type="submit"><i class=" fa fa-file"></i> Export Active Invoice to Excel</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </section>

  
  <section class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h4 class="card-title">Tabel Data Billing Extend (Belum Bayar)</h4>
            <p>Rekap Data Billing</p>
          </div>
          <div class="card-body">
            <form action="{{ route('report-invoice-extendUnpaid')}}" method="GET" enctype="multipart/form-data">
              <div class="row">

                <div class="col-4">
                  <div class="form-group">
                    <label>Pick Start Date Range</label>

                    <input type="date" name="start" class="form-control" required>
                  </div>
                </div>
                <div class="col-4">
                  <div class="form-group">
                    <label>Pick End Date Range</label>

                    <input type="date" name="end" class="form-control" required>

                  </div>
                </div>
                <div class="col-4 mt-4">
                  <button class="btn btn-primary" type="submit"><i class=" fa fa-file"></i> Export Active Invoice to Excel</button>
                </div>
              </div>
            </form>

            <div class="row">

              <div class="col-12">
                <div class="table">
                  <table class="table-hover" id="unpaidTable">
                    <thead style="white-space: nowrap;">
                      <tr>
                        <th>Bukti Bayar</th>
                        <th>Proforma No</th>
                        <th>Customer</th>
                        <th>Order Service</th>
                        <th>Tipe Invoice</th>
                        <th>Dibuat Pada</th>
                        <th>Status</th>
                        <th>Pranota</th>
                        <th>Invoice</th>
                        <th>Job</th>
                        <th>Action</th>
                        <th>Cancel</th>
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

    <section class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h4 class="card-title">Tabel Data Billing Extend (Piutang)</h4>
            <p>Rekap Data Billing</p>
          </div>
          <div class="card-body">
            <form action="{{ route('report-invoice-extendPiutang')}}" method="GET" enctype="multipart/form-data">
              <div class="row">

                <div class="col-4">
                  <div class="form-group">
                    <label>Pick Start Date Range</label>

                    <input type="date" name="start" class="form-control" required>
                  </div>
                </div>
                <div class="col-4">
                  <div class="form-group">
                    <label>Pick End Date Range</label>

                    <input type="date" name="end" class="form-control" required>

                  </div>
                </div>
                <div class="col-4 mt-4">
                  <button class="btn btn-primary" type="submit"><i class=" fa fa-file"></i> Export Active Invoice to Excel</button>
                </div>
              </div>
            </form>

            <div class="row">

              <div class="col-12">
                <div class="table">
                  <table class="table-hover" id="piutangTable">
                    <thead style="white-space: nowrap;">
                      <tr>
                        <th>Bukti Bayar</th>
                        <th>Proforma No</th>
                        <th>Customer</th>
                        <th>Order Service</th>
                        <th>Tipe Invoice</th>
                        <th>Dibuat Pada</th>
                        <th>Status</th>
                        <th>Pranota</th>
                        <th>Invoice</th>
                        <th>Job</th>
                        <th>Action</th>
                        <th>Cancel</th>
                        <th>Edit</th>
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

  @foreach($service as $os)
    <section class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h4 class="card-title">Tabel Data Billing Delivery {{$os->name}}</h4>
            <p>Rekap Data Billing</p>
          </div>
          <div class="card-body">
          <form action="{{ route('report-invoice-extend-All')}}" method="GET" enctype="multipart/form-data">
              @CSRF
              <div class="row">

                <div class="col-4">
                  <div class="form-group">
                    <label>Pick Start Date Range</label>
                    <!-- <input name="start" type="date" class="form-control flatpickr-range mb-1" placeholder="09/05/2023" id="expired"> -->
                    <input type="date" name="start" class="form-control" required>
                  </div>
                </div>
                <div class="col-4">
                  <div class="form-group">
                    <label>Pick End Date Range</label>
                    <!-- <input name="end" type="date" class="form-control flatpickr-range mb-1" placeholder="09/05/2023" id="expired"> -->
                    <input type="date" name="end" class="form-control" required>
                    <input type="hidden" name="os_id" value="{{$os->id}}">

                  </div>
                </div>
                <div class="col-4 mt-4">
                  <button class="btn btn-primary" type="submit"><i class=" fa fa-file"></i> Export Active Invoice to Excel</button>
                </div>
              </div>
            </form>

            <div class="row">

              <div class="col-12">
                <div class="table">
                  <table class="table-hover" id="invoiceTable-{{$os->id}}">
                    <thead style="white-space: nowrap;">
                      <tr>
                        <th>Bukti Bayar</th>
                        <th>Proforma No</th>
                        <th>Customer</th>
                        <th>Order Service</th>
                        <th>Tipe Invoice</th>
                        <th>Dibuat Pada</th>
                        <th>Status</th>
                        <th>Pranota</th>
                        <th>Invoice</th>
                        <th>Job</th>
                        <th>Action</th>
                        <th>Cancel</th>
                        <th>Edit</th>
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
@endforeach
</div>

 @include('billingSystem.pay.searchToPay');
<!-- end of Edit Modal Single Data Table  -->




@endsection

@section('custom_js')
@include('billingSystem.js.jsInvoice');

<script>
  $(document).ready(function(){
    $('#unpaidTable').DataTable({
      processing: true,
      serverSide: true,
      scrollY: '50vh',
      scrollX: true,
      ajax : {
        url : '/billing/import/extend/dataIndex',
        data: {type : 'unpaid'}
      },
      columns: [
        {data:'viewPhoto', name:'viewPhoto', classNmae:'text-center'},
        {data:'proforma', name:'proforma', classNmae:'text-center'},
        {data:'customer', name:'customer', classNmae:'text-center'},
        {data:'service', name:'service', classNmae:'text-center'},
        {data:'type', name:'type', classNmae:'text-center'},
        {data:'orderAt', name:'orderAt', classNmae:'text-center'},
        {data:'status', name:'status', classNmae:'text-center'},
        {data:'pranota', name:'pranota', classNmae:'text-center'},
        {data:'invoice', name:'invoice', classNmae:'text-center'},
        {data:'job', name:'job', classNmae:'text-center'},
        {data:'action', name:'action', classNmae:'text-center'},
        {data:'delete', name:'delete', classNmae:'text-center'},
      ],
    })


    $('#piutangTable').DataTable({
      processing: true,
      serverSide: true,
      scrollY: '50vh',
      scrollX: true,
      ajax : {
        url : '/billing/import/extend/dataIndex',
        data: {type : 'piutang'}
      },
      columns: [
        {data:'viewPhoto', name:'viewPhoto', classNmae:'text-center'},
        {data:'proforma', name:'proforma', classNmae:'text-center'},
        {data:'customer', name:'customer', classNmae:'text-center'},
        {data:'service', name:'service', classNmae:'text-center'},
        {data:'type', name:'type', classNmae:'text-center'},
        {data:'orderAt', name:'orderAt', classNmae:'text-center'},
        {data:'status', name:'status', classNmae:'text-center'},
        {data:'pranota', name:'pranota', classNmae:'text-center'},
        {data:'invoice', name:'invoice', classNmae:'text-center'},
        {data:'job', name:'job', classNmae:'text-center'},
        {data:'action', name:'action', classNmae:'text-center'},
        {data:'delete', name:'delete', classNmae:'text-center'},
        {data:'edit', name:'edit', classNmae:'text-center'},
      ],
    })

    @foreach($service as $os)
      $('#invoiceTable-{{$os->id}}').DataTable({
        processing: true,
        serverSide: true,
        scrollY: '50vh',
        scrollX: true,
        ajax : {
          url : '/billing/import/extend/dataIndex',
          data: {os_id : '{{$os->id}}'}
        },
        columns: [
          {data:'viewPhoto', name:'viewPhoto', classNmae:'text-center'},
          {data:'proforma', name:'proforma', classNmae:'text-center'},
          {data:'customer', name:'customer', classNmae:'text-center'},
          {data:'service', name:'service', classNmae:'text-center'},
          {data:'type', name:'type', classNmae:'text-center'},
          {data:'orderAt', name:'orderAt', classNmae:'text-center'},
          {data:'status', name:'status', classNmae:'text-center'},
          {data:'pranota', name:'pranota', classNmae:'text-center'},
          {data:'invoice', name:'invoice', classNmae:'text-center'},
          {data:'job', name:'job', classNmae:'text-center'},
          {data:'action', name:'action', classNmae:'text-center'},
          {data:'delete', name:'delete', classNmae:'text-center'},
          {data:'edit', name:'edit', classNmae:'text-center'},
        ],
      })
    @endforeach
    
  })
</script>

<script>
    function openWindow(url) {
        window.open(url, '_blank', 'width=600,height=800');
    }
</script>
@endsection