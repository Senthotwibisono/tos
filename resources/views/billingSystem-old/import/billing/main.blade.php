@extends ('partial.invoice.main')


@section('content')

<div class="page-heading">
  <h3><?= $title ?></h3>
  <p>Management Data Billing Bongkar</p>

</div>
<div class="page-content">

  <section class="row">
    <div class="col-12 mb-3">
      <a href="{{ route('deliveryMenu')}}" type="button" class="btn btn-primary">
        <i class="fa fa-folder"></i>
        Bongkar Form
      </a>
    </div>
    <div class="card">
      <div class="card-header">
        <h4>Export to Zahir</h4>
        <form action="" method="GET" enctype="multipart/form-data">
          <div class="row">
            <div class="col-3">
              <div class="form-group">
                <label>Pick Start Date Range</label>
                <input name="start" type="date" class="form-control flatpickr-range mb-1 expired" placeholder="09/05/2023" id="startZahir">
                <!-- <input type="date" name="start" class="form-control" required> -->
              </div>
            </div>
            <div class="col-3">
              <div class="form-group">
                <label>Pick End Date Range</label>
                <input name="end" type="date" class="form-control flatpickr-range mb-1 expired" placeholder="09/05/2023" id="endZahir">
                <!-- <input type="date" name="end" class="form-control" required> -->
              </div>
            </div>
            <div class="col-sm-3">
              <div class="form-group">
                <label for="">Invoice Type</label>
                  <div class="row">
                     <div class="col-6">
                       <div class="form-check form-switch">
                         <input class="form-check-input" type="checkbox" name="inv_typeZahir[]" value="DSK">
                         <label class="form-check-label" for="checkbox-dsk">DSK</label>
                       </div>
                     </div>
                     <div class="col-6">
                       <div class="form-check form-switch">
                         <input class="form-check-input" type="checkbox" name="inv_typeZahir[]" value="DS">
                         <label class="form-check-label" for="checkbox-ds">DS</label>
                       </div>
                     </div>
                  </div>
              </div>
            </div>
            <div class="col-3 mt-4">
              <button class="btn btn-info" type="button" onClick="exportZahir()"><i class=" fa fa-file"></i> Export Active Invoice to CSV</button>
            </div>
          </div>
        </form>
      </div>
      <div class="card-header">
        <h4>Report Delivery</h4>
        <form action="{{ route('report-invoice-import-All')}}" method="GET" enctype="multipart/form-data">
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
      </div>
    </div>
  </section>

  <div class="row">
  <div class="col-6">
  <section class="row">
      <div class="col-12">
        <div class="card border border-danger">
          <div class="card-header">
            <h4 class="card-title">Tabel Data Billing Delivery (Belum Bayar)</h4>
            <p>Rekap Data Billing</p>
          </div>
          <div class="card-body">
            <form action="/invoice/import/report-unpaid" method="GET" enctype="multipart/form-data">
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
                <table class="dataTable-wrapperIMP dataTable-loading no-footer sortable searchable fixed-columns" id="tableImp">
                  <thead>
                    <tr>
                      <th>Jumlah Invoice</th>
                      <th>Total Amount</th>
                      <th>Grand Total Amount</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>{{$countUnpaids ?? 0}}</td>
                      <td>{{$totaltUnpaids ?? 0}}</td>
                      <td>{{$grandTotalUnpaids ?? 0}}</td>
                      <td>
                        <a href="/invoice/import/delivery-detail/unpaid">
                          <span class="badge bg-primary text-white">See moreee....</span>
                        </a>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

    <div class="col-6">
    <section class="row">
      <div class="col-12">
        <div class="card border border-warning">
          <div class="card-header">
            <h4 class="card-title">Tabel Data Billing Delivery (Piutang)</h4>
            <p>Rekap Data Billing</p>
          </div>
          <div class="card-body">
            <form action="/invoice/import/report-piutang" method="GET" enctype="multipart/form-data">
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
              <table class="dataTable-wrapperIMP dataTable-loading no-footer sortable searchable fixed-columns" id="tableImp">
                  <thead>
                    <tr>
                      <th>Jumlah Invoice</th>
                      <th>Total Amount</th>
                      <th>Grand Total Amount</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>{{$countPiutangs ?? 0}}</td>
                      <td>{{$totalPiutangs ?? 0}}</td>
                      <td>{{$grandTotalPiutangs ?? 0}}</td>
                      <td>
                        <a href="/invoice/import/delivery-detail/piutang">
                          <span class="badge bg-primary text-white">See moreee....</span>
                        </a>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    </div>
  </div>

<div class="row">
  @foreach($service as $os)
  <div class="col-6">
    <section class="row">
      <div class="col-12">
        <div class="card border border-primary">
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
                  <div class="table-responsive">
                    <table class="table table-stripped">
                        <thead>
                            <tr>
                                <th>Jumlah Invoice</th>
                                <th>Total Amount</th>
                                <th>Grand Total Amount</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{$invoice->where('os_id', $os->id)->count()}}</td>
                                <td>{{$invoice->where('os_id', $os->id)->sum('total')}}</td>
                                <td>{{$invoice->where('os_id', $os->id)->sum('grand_total')}}</td>
                                <td>
                                    <a href="/invoice/import/delivery-detail/service?id={{ $os->id }}">
                                        <span class="badge bg-primary text-white">See more...</span>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
    @endforeach
  </div>
</div>

<!-- Edit Modal Single Data Table  -->
<div class="modal fade text-left modal-borderless" id="editModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Verify Payment</h5>
        <button type="button" class="close rounded-pill" data-bs-dismiss="modal" aria-label="Close">
          <i data-feather="x"></i>
        </button>
      </div>
      <form action="#">
        <div class="modal-body" style="height:auto;">
          <div class="form-group">
            <label for="">Jumlah Container</label>
            <input type="text" id="contInv" disabled value="kosong" class="form-control">
          </div>
          <div class="form-group">
            <label for="">Customer</label>
            <input type="text" id="customer" class="form-control" disabled value="kosong">
          </div>
          <input type="hidden" id="idInvoice">

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
            <i class="bx bx-x d-block d-sm-none"></i>
            <span class="d-none d-sm-block">Cancel</span>
          </button>
        
          <button id="payFull" type="button" class="btn btn-primary ml-1 payFull">
            Verify This Payment
          </button>
          <button id="piutang" type="button" class="btn btn-warning ml-1 piutang" >
            Piutang This Invoices
          </button>
          <button id="cancel" type="button" class="btn btn-danger ml-1 cancel" >
            Canceled This Invoices
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- end of Edit Modal Single Data Table  -->




@endsection

@section('custom_js')
<script>
    function openWindow(url) {
        window.open(url, '_blank', 'width=600,height=800');
    }
</script>

<script>
  async function exportZahir() {
    Swal.fire({
      icon: 'warning',
      title: 'Are you sure ?',
      text: 'Pastikan data yang dimasukkan sudah benar!!!',
      showCancelButton: true,
    }).then( async (result) => {
      if (result.isConfirmed) {
        showLoading();
        const start = document.getElementById('startZahir').value;
        const end = document.getElementById('endZahir').value;
        const type = Array.from(document.querySelectorAll('input[name="inv_typeZahir[]"]:checked')).map(el => el.value);
        if (start > end) {
          hideLoading();
          Swal.fire({
            icon: 'error',
            title: 'error',
            text: 'Tanggal Mulai tidah boleh lebih besar dari tanggal end',
          });
          return;
        }
        if (type.length == 0) {
          hideLoading();
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Anda belum mengisi tipe invoice',
          });
          return;
        }
        // console.log(type);
        const dataZahir = {
          start:start,
          end:end,
          inv_type:type
        };
        console.log(dataZahir);
        const url = '{{route('invoice.zahir.import')}}';
        const response = await fetch(url, {
          method: 'POST',
          headers: {
              "Content-Type": "application/json",
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
          },
          body: JSON.stringify(dataZahir),
        });
        hideLoading();
        if (response.ok) {
          const hasil = await response.json();
          if (hasil.success) {
            Swal.fire({
              icon: 'success',
              title: 'Sukses',
              text: 'Data ditemukan',
            }).then( async(then) => {
              showLoading();
              const data = hasil.data;
              // console.log(data);
              const urlCSV = '{{route('invoice.zahir.csvImport')}}';
              const formData = new FormData();
              formData.append('inv_id', JSON.stringify(data.inv_id));
              formData.append('fileName', data.fileName);
  
              try {
                // Menggunakan fetch untuk mendapatkan file CSV
                const response = await fetch(urlCSV, {
                  method: 'POST',
                  headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                  },
                  body: formData,
                });
              
                if (!response.ok) {
                  throw new Error('Failed to download file');
                }
                const blob = await response.blob();
                const url = window.URL.createObjectURL(blob);
                const link = document.createElement('a');
                link.href = url;
                link.download = data.fileName;
                link.click();
                window.URL.revokeObjectURL(url);
                hideLoading();
  
              } catch (error) {
                hideLoading();
                Swal.fire({
                  icon: 'error',
                  title: 'Download Gagal',
                  text: error.message,
                });
              }
            })
              
            // location.reload();
          } else {
            errorHasil(hasil);
          }
        } else {
          errorResponse(response);
        }
      } else {
        return;
      }
    });
  }
</script>
@endsection