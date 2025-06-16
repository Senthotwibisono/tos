@extends ('partial.invoice.main')


@section('content')

<div class="page-content">
  <section class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h4 class="card-title">Tabel Data Billing Muatan {{$os->name}}</h4>
            <p>Rekap Data Billing</p>
          </div>
          <div class="card-body">
            <form action="{{ route('report-invoice-export')}}" method="GET" enctype="multipart/form-data">
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
                    <input type="hidden" name="os_id" value="{{$os->id}}">

                  </div>
                </div>
                <div class="col-sm-3">
                  <div class="form-group">
                    <label for="">Invoice Type</label>
                     <div class="row">
                        <div class="col-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="inv_type[]" value="DSK" id="checkbox-dsk">
                            <label class="form-check-label" for="checkbox-dsk">DSK</label>
                          </div>
                        </div>
                        <div class="col-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="inv_type[]" value="DS" id="checkbox-ds">
                            <label class="form-check-label" for="checkbox-ds">DS</label>
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

            <div class="row">

              <div class="col-12">
                <div class="table">
                  <table class="table-hover" id="lunasTable">
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
        <a href="/billing/import/delivey-system" class="btn btn-primary">Back</a>
    </div>
</div>
@include('billingSystem.pay.searchToPay');
<!-- end of Edit Modal Single Data Table  -->




@endsection

@section('custom_js')
@include('billingSystem.js.jsInvoice');
<script>
  $(document).ready(function(){
    $('#lunasTable').DataTable({
      processing: true,
      serverSide: true,
      scrollY: '50hv',
      scrollX: true,
      ajax : {
        url : '/invoice/export/reciving-detail/table/dataTableExport',
        data: {os_id : '{{ $os->id }}'}
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