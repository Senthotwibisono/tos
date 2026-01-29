@extends ('partial.invoice.main')


@section('content')

<div class="page-content">
  <section class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h4 class="card-title">Tabel Data Billing Bongkaran {{$os->name}}</h4>
            <p>Rekap Data Billing</p>
          </div>
          <div class="card-body">
            <form action="{{ route('report-invoice-import')}}" method="GET" enctype="multipart/form-data">
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
                    <table class="table-hover" id="unpaidImport">
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
                                <th>E Materai</th>
                                <th>Job</th>
                                <th>Action</th>
                                <th>Edit</th>
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
    @include('billingSystem.pay.searchToPay');
    <div class="footer">
        <a href="/billing/import/delivey-system" class="btn btn-primary">Back</a>
    </div>
</div>

<!-- Edit Modal Single Data Table  -->

<!-- end of Edit Modal Single Data Table  -->




@endsection

@section('custom_js')
@include('billingSystem.js.jsInvoice');
@include('materai.js');
<script>
  $(document).ready(function() {
    var osId ="{{$osId}}"; // Ambil osId dari hidden input
    
      $('#unpaidImport').DataTable({
          processing: true,
          serverSide: true,
          scrollY: '50vh',
          scrollX: true,
          ajax: {
              url: '/invoice/import/delivery-data/service',
              type: 'GET',
              data: {
                  os_id: osId // Kirimkan osId sebagai parameter
              }
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
              {data:'materai', name:'materai', classNmae:'text-center'},
              {data:'job', name:'job', classNmae:'text-center'},
              {data:'action', name:'action', classNmae:'text-center'},
              {data:'editInvoice', name:'editInvoice', classNmae:'text-center'},
              {data:'delete', name:'delete', classNmae:'text-center'},
          ],
          pageLength: 50
      });
  });
</script>
<script>
    function openWindow(url) {
        window.open(url, '_blank', 'width=600,height=800');
    }
</script>
@endsection