@extends('partial.customer.main')

@section('content')

<section>
    <div class="page-content">
        <div class="card mb-3">
            <div class="card-header">
                <div class="text-center">
                    <h4><b>Form Invoice Export</b></h4>
                </div>
            </div>
            <div class="card-body">
                <form action="" method="post" id="exportForm" enctype="multipart/form-data">
                    @csrf
                    <div class="col-12">
                        <div class="divider divider-left">
                            <div class="divider-text">
                                <h4><b>Customer Information</b></h4>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <label for="">Customer</label>
                                <select name="cust_id" id="cust_id" class="js-example-basic-single select2 fomr-select" style="width: 100%:">
                                    <option disabled selected value>Pilih Satu!</option>
                                    @foreach($customers as $cust)
                                        <option value="{{$cust->id}}" {{$form->cust_id == $cust->id ? 'selected' : ''}}>{{$cust->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-4">
                                <label for="">NPWP</label>
                                <input type="text" name="npwp" value="{{$form->customer->npwp ?? null}}" id="npwp" class="form-control" readonly>
                            </div>
                            <div class="col-4">
                                <label for="">Alamat</label>
                                <input type="text" name="alamat" value="{{$form->customer->alamat ?? null}}" id="alamat" class="form-control" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="divider divider-left">
                            <div class="divider-text">
                                <h4><b>Booking Information</b></h4>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="">Booking Number</label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" id="booking_no" name="booking_no" value="{{$form->do_id ?? null}}">
                                        <button type="button" class="btn btn-info" onClick="searchByBookingNo()"><i class="fas fa-search"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="">Order Service</label>
                                    <select name="os_id" id="os_id" class="js-example-basic-single select2 form-select" style="width: 100%;">
                                        <option disabled selected value>Pilih Satu</option>
                                        @foreach($orderService as $os)
                                            <option value="{{$os->id}}" {{$form->os_id == $os->id ? 'selected' : ''}}>{{$os->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="row">
                                    <div class="divider divider-center">
                                        <div class="divider-text">
                                            Vessel
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Vessel Name</label>
                                        <select name="ves_id" id="ves_id" class="js-example-basic-single select2 form-select" style="width: 100%">
                                            <option disabled selected value>Pilih Satu!</option>
                                            @foreach($vessels as $ves)
                                                <option value="{{$ves->ves_id}}" {{$form->ves_id == $ves->ves_id ? 'selected' : ''}}>{{$ves->ves_name}} - {{$ves->voy_out}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-6">
                                                <label for="">Voy In</label>
                                                <input type="text" name="voy_in" value="{{$form->Kapal->voy_in ?? null}}" class="form-control" id="voy_in" readonly>
                                            </div>
                                            <div class="col-6">
                                                <label for="">Voy Out</label>
                                                <input type="text" name="voy_out" value="{{$form->Kapal->voy_out ?? null}}" class="form-control" id="voy_out" readonly>
                                            </div>
                                            <div class="col-6">
                                                <label for="">Clossing Date</label>
                                                <input type="datetime-local" name="clossing_date" value="{{$form->Kapal->clossing_date ?? null}}" class="form-control" id="clossing_date" readonly>
                                            </div>
                                            <div class="col-6">
                                                <label for="">Estmate Departure</label>
                                                <input type="datetime-local" name="etd" value="{{$form->Kapal->etd_date ?? null}}" class="form-control" id="etd" readonly>
                                            </div>
                                            <div class="col-auto">
                                                <a href="javascript:void(0)" onclick="openWindow('{{route('invoiceService.tracking.jadwalKapal')}}')" class="btn btn-info">Lihat Jadwal Kapal</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="row">
                                    <div class="divider divider-center">
                                        <div class="divider-text">
                                            Container
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Container Available</label>
                                        <select name="container_key[]" id="container_key" class="js-example-basic-multiple select2 form-select" style="width: 100%" multiple="multiple">
                                            @foreach($containers as $cont)
                                                <option value="{{$cont->container_key}}" selected>{{$cont->container_no}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-auto">
                        <button type="button" onClick="submitFormFirstStep()" class="btn btn-success">Submit</button>
                        <a href="{{ route('customer.export.indexForm')}}" class="btn btn-warning">Back</a>
                        <button type="button" class="btn btn-danger" onClick="deleteButton()">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@section('custom_js')

<script>
    $(document).ready(function(){

        $('#cust_id').on('change', function(){
            customersExport();
        });

        $('#ves_id').on('change', function(){
            vesselExport();
        });

    })

    function openWindow(url) {
        window.open(url, '_blank', 'width=1000,height=1000');
    }
</script>
<script>
    async function customersExport() {
        showLoading();
        const cust_id = document.getElementById('cust_id').value;
        // console.log(cust_id);
        const url = '{{route('api.customer.GetData-customer')}}';
        const response = await fetch(url, {
            method : 'POST',
            headers: {
                "Content-Type": "application/json",
                'X-CSRF-TOKEN': '{{ csrf_token() }}' // Optional kalau butuh CSRF untuk GET
            },
            body: JSON.stringify({ cust_id: cust_id }),
        });
        console.log(response);
        hideLoading();
        if (response.ok) {
            const hasil = await response.json();
            if (hasil.success) {
                $('#npwp').val(hasil.data.npwp);
                $('#alamat').val(hasil.data.alamat);
            }
        }
    }

    async function vesselExport() {
        showLoading();
        const ves_id = document.getElementById('ves_id').value;
        // console.log(ves_id);
        const url = '{{route('api.customer.GetData-vessel')}}';
        const response = await fetch(url, {
            method : 'POST',
            headers: {
                "Content-Type": "application/json",
                'X-CSRF-TOKEN': '{{ csrf_token() }}' // Optional kalau butuh CSRF untuk GET
            },
            body: JSON.stringify({ ves_id: ves_id }),
        });
        console.log(response);
        hideLoading();
        if (response.ok) {
            const hasil = await response.json();
            if (hasil.success) {
               $('#voy_in').val(hasil.data.voy_in);
               $('#voy_out').val(hasil.data.voy_out);
               $('#etd').val(hasil.data.etd_date);
               $('#clossing_date').val(hasil.data.clossing_date);
            }
        }
    }

    async function searchByBookingNo() {
        Swal.fire({
            icon: 'warning',
            title: 'Apakah nomor booking yg anda masukkan sudah benar?',
            showCancelButton: true,
        }).then( async(result) => {
            if (result.isConfirmed) {
                const bookingNo = document.getElementById('booking_no').value;
                const userId = {{Auth::user()->id}};
                console.log(userId);
                if (!bookingNo || bookingNo === null) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Anda Belum Mengisi',
                    });
                    return;
                }
                showLoading();
                const url = '{{route('api.customer.GetData-booking')}}';
                const response = await fetch(url, {
                    method : 'POST',
                    headers: {
                        "Content-Type": "application/json",
                        'X-CSRF-TOKEN': '{{ csrf_token() }}' // Optional kalau butuh CSRF untuk GET
                    },
                    body: JSON.stringify({ booking_no: bookingNo, userId:userId }),
                });
                console.log(response);
                hideLoading();
                if (response.ok) {
                    const hasil = await response.json();
                    if (hasil.success) {
                        Swal.fire({
                            icon: 'success'
                        }).then(async() => {
                            $('#cust_id').val(hasil.data.customer.id).trigger('change');
                            $('#ves_id').val(hasil.data.vessel.ves_id).trigger('change');

                            $('#container_key').empty();
                          
                            $.each(hasil.data.items, function(index, item) {
                                $('#container_key').append($('<option>', {
                                    value: item.container_key,
                                    text: item.container_no,
                                    selected: 'selected'
                                }));
                            });
                        });
                    }else{
                        Swal.fire({
                            icon: 'error',
                            title: 'Something wrong',
                            text: hasil.message,
                        }).then(() => {
                            location.reload();
                        });
                    }
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: response.status,
                        text: response.statusText,
                    }).then(() => {
                        location.reload();
                    });
                }
            }
        });
    }

    async function deleteButton() {
        Swal.fire({
            icon: 'warning',
            title: 'Yakin menghapus data ini?',
            showCancelButton: true,
        }).then(async(result) => {
            if (result.isConfirmed) {
                showLoading();
                const id = {{$form->id}};
                const url = '{{route('customer.export.deleteForm')}}';
                const response = await fetch(url, {
                    method : 'POST',
                    headers: {
                        "Content-Type": "application/json",
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    body: JSON.stringify({ id: id }),
                });
                console.log(response);
                hideLoading();
                if (response.ok) {
                    var hasil = await response.json();
                    if (hasil.success) {
                        Swal.fire({
                            icon: 'success',
                            text: hasil.message,
                        }).then(() => {
                            window.location.href = '{{ route('customer.export.indexForm')}}';
                        });
                    }else{
                        Swal.fire({
                            icon: 'error',
                            text: hasil.message,
                        });
                    }
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: response.status,
                        text: response.statusText,
                    });
                }
            }
        });
    }

    async function submitFormFirstStep() {
        Swal.fire({
            icon: 'warning',
            title: 'Apakah anda sudah yakin?',
            showCancelButton: true,
        }).then(async (result) => {
            if (result.isConfirmed) {
                showLoading();
                const cust_id = document.getElementById('cust_id').value;
                const ves_id = document.getElementById('ves_id').value;
                const containerKeySelect = document.getElementById('container_key');
                const container_key = Array.from(containerKeySelect.selectedOptions).map(option => option.value);
                const os_id = document.getElementById('os_id').value;

                if (!cust_id || !os_id || !ves_id || container_key === null) {
                    hideLoading();
                    Swal.fire({
                        icon: 'error',
                        title: 'Data Belum Lengkap',
                    });
                    return;
                }

                var formData = {
                    cust_id : cust_id,
                    ves_id : ves_id,
                    container_key : container_key,
                    os_id : os_id,
                    form_id: {{$form->id}},
                };
                console.log(formData);
                const url = '{{ route('customer.export.firstStepPost')}}';
                const response = await fetch(url, {
                    method : 'POST',
                    headers: {
                        "Content-Type": "application/json",
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    body: JSON.stringify(formData),
                })
                hideLoading();
                console.log(response);
                if (response.ok) {
                    var hasil = await response.json();
                    if (hasil.success) {
                        Swal.fire({
                            icon: 'success',
                            text: hasil.message,
                        }).then(() => {
                            window.location.href = '/customer-export/form/preinvoice-' + {{$form->id}};
                        });
                    }else{
                        Swal.fire({
                            icon: 'error',
                            text: hasil.message,
                        }).then(() => {
                            location.reload();
                        });
                    }
                }else
                Swal.fire({
                    icon: 'error',
                    title: response.status,
                    text: response.statusText,
                });
            }
        });
    }
</script>

@endsection