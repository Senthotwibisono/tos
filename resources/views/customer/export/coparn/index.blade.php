@extends('partial.customer.main')
@section('custom_styles')

@endsection

@section('content')

<section>
    <div class="page-content">
        <div class="card mb-3">
            <div class="card-header">
                <div class="text-center">
                    <h4><b>Jadwal Kapal Tersedia</b></h4>
                </div>
            </div>
            <div class="card-body">
                <table id="table1">
                    <thead>
                        <tr>
                            <th>Vessel Name</th>
                            <th>Vessel Code</th>
                            <th>Agent</th>
                            <th>Owner</th>
                            <th>Voy In</th>
                            <th>Voy Out</th>
                            <th>Arrival Date</th>
                            <th>Clossing Time</th>
                            <th>Estimate Departure Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($vessels as $ves)
                        <tr>
                            <td>{{$ves->ves_name ?? '-'}}</td>
                            <td>{{$ves->ves_code ?? '-'}}</td>
                            <td>{{$ves->agent ?? '-'}}</td>
                            <td>{{$ves->voyage_owner ?? '-'}}</td>
                            <td>{{$ves->voy_in ?? '-'}}</td>
                            <td>{{$ves->voy_out ?? '-'}}</td>
                            <td>{{$ves->arrival_date ?? '-'}}</td>
                            <td>{{$ves->clossing_date ?? '-'}}</td>
                            <td>{{$ves->etd_date ?? '-'}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card mb-3">
            <div class="card-header">
                <div class="row">
                    <div class="col-auto">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#singleModal">upload Single</button>
                    </div>
                    <div class="col-auto">
                        <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#fileModal">upload File</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table">
                    <table class="table-hover table-multiple" id="tableData">
                        <thead>
                            <tr>
                                <th>Container No</th>
                                <th>Iso Code</th>
                                <th>Size</th>
                                <th>Status</th>
                                <th>Gross</th>
                                <th>Customer Code</th>
                                <th>Booking No</th>
                                <th>Edit</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade text-left" id="singleModal" role="dialog" aria-labelledby="myModalLabel110" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="myModalLabel110">Upload Single Data Coparn</h5>
              <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><i data-feather="x"></i></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('customer.coparn.storeSingle') }}" method="post" id="singleForm">
                    @csrf
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="">Vessel</label>
                                <select name="ves_id" id="ves_id" id="" class="js-example-basic-single select2 form-select" style="width: 100%;">
                                    <option disabeled selected value>Pilih Satu</option>
                                    @foreach($vessels as $ves)
                                        <option value="{{$ves->ves_id}}">{{$ves->ves_name}} -- {{$ves->voy_in}} / {{$ves->voy_out}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="">Iso Code</label>
                                <select name="iso_code" id="iso_code" id="" class="js-example-basic-single select2 form-select" style="width: 100%;">
                                    <option disabeled selected value>Pilih Satu</option>
                                    @foreach($isoCode as $iso)
                                        <option value="{{$iso->iso_code}}">{{$iso->iso_code}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="">Customer</label>
                                <select name="customer_id" id="customer_id" id="" class="js-example-basic-single select2 form-select" style="width: 100%;">
                                    <option disabeled selected value>Pilih Satu</option>
                                    @foreach($customers as $customer)
                                        <option value="{{$customer->code}}">{{$customer->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="">Operator</label>
                                <input type="text" name="ctr_opr" id="ctr_opr" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">Booking Number</label>
                                <input type="text" name="booking_no" id="booking_no" class="form-control">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="">Nomor Container</label>
                                <input type="text" name="container_no" id="container_no" id="container_no" class="form-control" required>
                                <input type="hidden" name="container_key" id="container_key" id="container_key" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="">Status</label>
                                <select name="ctr_status" id="ctr_status" class="js-example-basic-multiple form-control" style="width: 100%;">
                                    <option value="FCL">Full</option>
                                    <option value="MTY">Empty</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="">Load Port</label>
                                <select name="load_port" id="load_port" class="js-example-basic-multiple form-control" style="width: 100%;">
                                    @foreach($ports as $port)
                                        <option value="{{$port->port}}">{{$port->port}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="">Disch Port</label>
                                <select name="disch_port" id="disch_port" class="js-example-basic-multiple form-control" style="width: 100%;">
                                    @foreach($ports as $port)
                                        <option value="{{$port->port}}">{{$port->port}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="">Gross</label>
                                <input type="number" name="gross" id="gross" class="form-control">
                            </div>
                        </div>
                    </div>
                </form>
            </div>  
            <div class="modal-footer">
              <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal"> <i class="bx bx-x d-block d-sm-none"></i><span class="d-none d-sm-block">Close</span></button>
              <button type="button" class="btn btn-success ml-1" id="submitSingle"><i class="bx bx-check d-block d-sm-none"></i><span class="d-none d-sm-block">Confirm</span></button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade text-left" id="fileModal" role="dialog" aria-labelledby="myModalLabel110" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="myModalLabel110">Upload Single Data Coparn</h5>
              <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><i data-feather="x"></i></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('customer.coparn.storeFile') }}" method="post" id="fileForm" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="">Vessel</label>
                                <select name="ves_id" id="vesFile" class="js-example-basic-single select2 form-select" style="width: 100%;">
                                    <option disabeled selected value>Pilih Satu</option>
                                    @foreach($vessels as $ves)
                                        <option value="{{$ves->ves_id}}">{{$ves->ves_name}} -- {{$ves->voy_in}} / {{$ves->voy_out}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="">Customer</label>
                                <select name="customer_id" id="customerFile" class="js-example-basic-single select2 form-select" style="width: 100%;">
                                    <option disabeled selected value>Pilih Satu</option>
                                    @foreach($customers as $customer)
                                        <option value="{{$customer->code}}">{{$customer->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <input type="file" name="fileCoparn" id="fileExcel" accept=".xls,.xlsx" class="dropify" data-height="270" data-allowed-file-extensions="xls, xlsx">
                        </div>
                    </div>
                </form>
            </div>  
            <div class="modal-footer">
              <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal"> <i class="bx bx-x d-block d-sm-none"></i><span class="d-none d-sm-block">Close</span></button>
              <button type="button" class="btn btn-success ml-1" id="submitFile"><i class="bx bx-check d-block d-sm-none"></i><span class="d-none d-sm-block">Confirm</span></button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('custom_js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Attach event listener to the update button
        document.getElementById('submitSingle').addEventListener('click', function (e) {
            e.preventDefault(); // Prevent the default form submission

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
                      showLoading();
                    document.getElementById('singleForm').submit();
                }
            });
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Attach event listener to the update button
        document.getElementById('submitFile').addEventListener('click', function (e) {
            e.preventDefault(); // Prevent the default form submission

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
                    showLoading();
                    postFileCoparn();
                }
            });
        });
    });
</script>

<script>
    async function readExcelFile(file, fileExt) {
        return new Promise((resolve, reject) => {
            const reader = new FileReader();

            reader.onload = function (e) {
                let data;
                try {
                    if (fileExt === 'csv') {
                        const text = e.target.result;
                        data = text.split('\n').map(row => row.split(','));
                    } else {
                        const binary = new Uint8Array(e.target.result);
                        const workbook = XLSX.read(binary, { type: 'array' });
                        const sheetName = workbook.SheetNames[0];
                        const worksheet = workbook.Sheets[sheetName];
                        data = XLSX.utils.sheet_to_json(worksheet, { header: 1 });
                    }

                    const cleanedData = data
                        .filter(row => row.length > 0)
                        .slice(1)
                        .map(row => row.map(cell => typeof cell === 'string' ? cell.trim() : cell));

                    resolve(cleanedData);
                } catch (err) {
                    reject(err);
                }
            };

            if (fileExt === 'csv') {
                reader.readAsText(file);
            } else {
                reader.readAsArrayBuffer(file);
            }
        });
    }

    async function postFileCoparn() {
        const customerCode = document.getElementById('customerFile').value;
        const ves_id = document.getElementById('vesFile').value;
        const fileInput = document.getElementById('fileExcel');
        const file = fileInput.files[0];

        if (!file) {
            hideLoading();
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Silakan pilih file terlebih dahulu.', 
            });
            return;
        }
        if (!ves_id) {
            hideLoading();
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Silakan pilih Kapal terlebih dahulu.', 
            });
            return;
        }
        if (!customerCode) {
            hideLoading();
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Silakan pilih Customer terlebih dahulu.', 
            });
            return;
        }

        const fileName = file.name;
        const fileExt = fileName.split('.').pop().toLowerCase();
        const allowedExtensions = ['xlsx', 'xls', 'csv'];

        if (!allowedExtensions.includes(fileExt)) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Format file tidak didukung. Gunakan .xls, .xlsx, atau .csv', 
            });
            return;
        }
        let dataResult;
        try {
            dataResult = await readExcelFile(file, fileExt);
            console.log(dataResult);
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: error, 
            });
        }
        const formData = {
            customer: customerCode,
            ves_id: ves_id,
            dataResult: dataResult
        };
        const response = await fetch('{{route('customer.coparn.storeFile')}}',{
            method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify(formData)
        })
        console.log(response);
        hideLoading();
        try {
            if (response.ok) {
                var result = await response.json();
                if (result.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Suksesss!',
                        text: result.message,
                    }).then (() => {
                        location.reload();
                    });
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Something wrong in : ',
                        text: result.message,
                    });
                }
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.statusText, 
                });
            }
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: error, 
            });
        }
    }
</script>


<script>
    $(document).ready(function () {
        $('#tableData').dataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('customer.coparn.dataIndex') }}',
            columns: [
                {name:'container_no', data:'container_no'},
                {name:'iso_code', data:'iso_code'},
                {name:'ctr_size', data:'ctr_size'},
                {name:'ctr_status', data:'ctr_status'},
                {name:'gross', data:'gross'},
                {name:'customer_code', data:'customer_code'},
                {name:'booking_no', data:'booking_no'},
                {name:'edit', data:'edit'},
                {name:'delete', data:'delete'},
            ],
        })
    })
</script>

<script>
    async function editItem(event) {
        const button = event.target.closest("button");
        const id = button.getAttribute("data-id"); // Get data-id value
        showLoading();
        const url = '{{ route('customer.coparn.editCoparn', ['id' => '_ID_']) }}'.replace('_ID_', id);
        const response = await fetch(url);
        console.log(response);
        hideLoading();
        if (response.ok) {
            var result = await response.json();
            console.log(result);
            if (result.success) {
               $('#singleModal').modal('show');
               $('#singleModal #ves_id').val(result.data.ves_id).trigger('change');
               $('#singleModal #iso_code').val(result.data.iso_code).trigger('change');
               $('#singleModal #customer_id').val(result.data.customer_code).trigger('change');
               $('#singleModal #ctr_opr').val(result.data.ctr_opr);
               $('#singleModal #booking_no').val(result.data.booking_no);
               $('#singleModal #container_no').val(result.data.container_no);
               $('#singleModal #container_key').val(result.data.container_key);
               $('#singleModal #gross').val(result.data.gross);
               $('#singleModal #ctr_status').val(result.data.ctr_status).trigger('change');
               $('#singleModal #disch_port').val(result.data.disch_port).trigger('change');
               $('#singleModal #load_port').val(result.data.load_port).trigger('change');
            } else {
                Swal.fire({
                    icon : 'error',
                    title : 'Error',
                    text : result.message,
                });
            }
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: response.statusText,
            });
        }
    }
</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Delegasi event ke tabel
        $('#tableData').on('click', '.btn-delete-coparn', function (e) {
            deleteCoparn(e);
        });
    });
    async function deleteCoparn(event) {
        const id = event.target.getAttribute('data-id');
        const containerNo = event.target.getAttribute('data-no');
        console.log(id);
        if (!id || id === null) {
            Swal.fire({
                icon: 'error',
                title: 'Sorry',
                text: 'Terjadi kesalahan dalam rendering data, coba beberapa saat lagi ya!!',
            }).then(() => {
                $('#tableData').DataTable().ajax.reload();
            });
        }
        Swal.fire({
            icon: 'warning',
            title: 'Apakah anda yakin untuk menghapus?',
            text: 'Data container ' + containerNo + ' akan terhapus secara permanen, apakah anda yakin?',
            showCancelButton: true,
        }).then( async (result) => {
            if (result.isConfirmed) {                
                showLoading();
                const urlDelete = '{{ route('customer.coparn.deleteCoparn')}}';
                const response = await fetch(urlDelete, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}" // ✅ Include CSRF token for Laravel
                    },
                    body: new URLSearchParams({ container_key: id })
                });
                console.log(response);
                hideLoading();
                if (response.ok) {
                    var hasil = await response.json();
                    if (hasil.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Suksesss!',
                            text: hasil.message,
                        }).then (() => {
                            location.reload();
                        });
                    }else{
                        Swal.fire({
                            icon: 'error',
                            title: 'Something wrong in : ',
                            text: hasil.message,
                        });
                    }
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Something wrong in : ',
                        text: response.statusText,
                    });
                }
            }
        });
    }
</script>

@endsection
