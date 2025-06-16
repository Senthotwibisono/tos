@extends ('partial.invoice.main')


@section('content')
<div class="page-content">  
  <section class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h4 class="card-title">Tabel Data Billing Reciving (Piutang)</h4>
            <p>Rekap Data Billing</p>
          </div>
          <div class="card-body">
          <form action="/invoice/export/report-piutang" method="GET" enctype="multipart/form-data">
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
                  <table class="table-hover" id="pitangTable">
                    <thead style="white-space: nowrap;">
                      <tr>
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

    <div class="footer">
        <a href="/billing/export/delivey-system" class="btn btn-primary">Back</a>
    </div>
</div>

 @include('billingSystem.pay.searchToPay');
<!-- end of Edit Modal Single Data Table  -->




@endsection

@section('custom_js')
<script>
  $(document).ready(function(){
    $('#pitangTable').DataTable({
      processing: true,
      serverSide: true,
      scrollY: '50hv',
      scrollX: true,
      ajax : {
        url : '/invoice/export/reciving-detail/table/dataTableExport',
        data: {type : 'piutang'}
      },
      columns: [
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
  })
</script>
@endsection