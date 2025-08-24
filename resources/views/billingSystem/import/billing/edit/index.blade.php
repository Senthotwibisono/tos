@extends('partial.invoice.main')

@section('custom_styles')
<style>
.borderCard {
    position: relative;
    padding: 20px;
    background: white;
    border-radius: 10px;
}

.borderCard::before {
    content: "";
    position: absolute;
    inset: -3px;
    background: linear-gradient(to right, #0000ff, #4a90e2, #87cefa, #b0e0e6, #ffffff);
    z-index: -1;
    border-radius: 12px;
}
</style>
@endsection

@section('content')

<div class="card">
    <div class="card-header">
        <h4>{{$title}}</h4>
    </div>
    <div class="card-body">
        <form action="/invoice/import/updateInvoice" method="post" id="updateForm">
            @csrf
            <input type="hidden" name="form_id" value="{{$form->id}}">
            <div class="col-12">
                <div class="row">
                    <div class="divider divider-left">
                        <div class="divider-text">
                            Customer Information
                        </div>
                    </div>
                    <div class="col-auto">
                        <div class="form-group">
                            <label for="">Customer</label>
                            <select name="cust_id" id="customer" class="form-select select2 js-example-basic-single" style="width: 100%;">
                                <option disabled selected value>Pilih Satu</option>
                                @foreach($customers as $customer)
                                    <option value="{{$customer->id}}" {{$singleInvoice->cust_id == $customer->id ? 'selected' : ''}}>{{$customer->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-auto">
                        <div class="form-group">
                            <label for="">NPWP</label>
                            <input type="text" name="npwp" id="npwp" value="{{ $singleInvoice->npwp ?? '-' }}" class="form-control">
                        </div>
                    </div>
                    <div class="col-auto">
                        <div class="form-group">
                            <label for="">fax</label>
                            <input type="text" name="fax" id="fax" value="{{ $singleInvoice->fax ?? '-' }}" class="form-control">
                        </div>
                    </div>
                    <div class="col-auto">
                        <div class="form-group">
                            <label for="">Alamat</label>
                            <textarea name="alamat" class="form-control" id="alamat" cols="50" rows="2" style="text-align: justify; white-space: pre-wrap;">
                                {{$singleInvoice->alamat ?? '-'}}
                            </textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="divider divider-left">
                        <div class="divider-text">
                            Informasi Kapal Tanggal & DO
                        </div>
                    </div>
                    <div class="col-auto">
                        <div class="form-group">
                            <label for="">Kapal</label>
                            <select name="ves_id" id="ves_id" class="form-select js-example-basic-single select2" style="width: 100%;">
                                <option disabled selected value>Pilih Satu</option>
                                @foreach($vesels as $ves)
                                    <option value="{{$ves->ves_id}}" {{$form->ves_id == $ves->ves_id ? 'selected' : ''}} > {{$ves->ves_name ?? '-'}}/{{$ves->voy_in ?? '-'}} - {{$ves->voy_out ?? '-'}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-auto">
                        <label for="">Disc Date</label>
                        <input type="datetime-local" class="form-control" value="{{$singleInvoice->disc_date ?? '-'}}" readonly>
                    </div>
                    <div class="col-auto">
                        <label for="">Rencana Keluar</label>
                        <input type="date" class="form-control" value="{{$singleInvoice->expired_date ?? '-'}}" readonly>
                    </div>
                    <div class="col-auto">
                        <label for="">Nomor DO</label>
                        <input type="text" class="form-control" value="{{$singleInvoice->do_no}}" readonly>
                    </div>
                    <div class="col-auto">
                        <label for="">Order Service</label>
                        <select name="os_id" id="" class="form-control js-example-basic-single select2" style="width: 100%;">
                            <option disabled selected value>Pilih Satu !!</option>
                            @foreach($services as $service)
                                <option value="{{$service->id}}" {{$singleInvoice->os_id == $service->id ? 'selected' : '' }}>{{$service->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <br>
            <br>
            <div class="card-group">
                @if($flagDSK == 'Y')
                <div class="card" style="
                    border: 2px solid;
                    border-image-source: linear-gradient(to right, #0000ff, #4a90e2, #87cefa, #b0e0e6, #ffffff);
                    border-image-slice: 1;
                    border-radius: 10px;
                ">
                    <div class="card-content">
                        <div class="card-header">
                            <h6>Edit Invoice {{$dsk->inv_no ?? $dsk->proforma_no ?? '-'}}</h6>
                        </div>
                        <div class="card-body">
                            <div class="divider divider-left">
                                <div class="divider-text">
                                    Amount Information
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-auto">
                                    <div class="form-group">
                                        <label for="">Total</label>
                                        <input type="number" step="0.02" name="totalDSK" class="form-control" value="{{$dsk->total ?? ''}}">
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="form-group">
                                        <label for="">Admin</label>
                                        <input type="number" step="0.02" name="adminDSK" class="form-control" value="{{$dsk->admin ?? ''}}">
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="form-group">
                                        <label for="">Discount</label>
                                        <input type="number" step="0.02" name="discountDSK" class="form-control" value="{{$dsk->discount ?? ''}}">
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="form-group">
                                        <label for="">PPN</label>
                                        <input type="number" step="0.02" name="pajakDSK" class="form-control" value="{{$dsk->pajak ?? ''}}">
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="form-group">
                                        <label for="">Grand Toal</label>
                                        <input type="number" step="0.02" name="grand_totalDSK" class="form-control" value="{{$dsk->grand_total ?? ''}}">
                                    </div>
                                </div>
                            </div>
                            <div class="divider divider-left">
                                <div class="divider-text">
                                    Date Information
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-auto">
                                    <div class="form-group">
                                        <label for="">Order At</label>
                                        <input type="datetime-local" name="order_atDSK" value="{{$dsk->order_at}}" id="" class="form-control">
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="form-group">
                                        <label for="">Piutang At</label>
                                        <input type="datetime-local" name="piutang_atDSK" value="{{$dsk->piutang_at}}" id="" class="form-control">
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="form-group">
                                        <label for="">Lunas At</label>
                                        <input type="datetime-local" name="lunas_atDSK" value="{{$dsk->lunas_at}}" id="" class="form-control">
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="form-group">
                                        <label for="">Invoice Date</label>
                                        <input type="datetime-local" name="invoice_dateDSK" value="{{$dsk->invoice_date}}" id="" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                @if($flagDS == 'Y')
                <div class="card" style="
                    border: 2px solid;
                    border-image-source: linear-gradient(to right, #0000ff, #4a90e2, #87cefa, #b0e0e6, #ffffff);
                    border-image-slice: 1;
                    border-radius: 10px;
                ">
                    <div class="card-content">
                        <div class="card-header">
                            <h6>Edit Invoice {{$ds->inv_no ?? $ds->proforma_no ?? '-'}}</h6>
                        </div>
                        <div class="card-body">
                            <div class="divider divider-left">
                                <div class="divider-text">
                                    Amount Information
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-auto">
                                    <div class="form-group">
                                        <label for="">Total</label>
                                        <input type="number" step="0.02" name="totalDS" class="form-control" value="{{$ds->total ?? ''}}">
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="form-group">
                                        <label for="">Admin</label>
                                        <input type="number" step="0.02" name="adminDS" class="form-control" value="{{$ds->admin ?? ''}}">
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="form-group">
                                        <label for="">Discount</label>
                                        <input type="number" step="0.02" name="discountDS" class="form-control" value="{{$ds->discount ?? ''}}">
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="form-group">
                                        <label for="">PPN</label>
                                        <input type="number" step="0.02" name="pajakDS" class="form-control" value="{{$ds->pajak ?? ''}}">
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="form-group">
                                        <label for="">Grand Toal</label>
                                        <input type="number" step="0.02" name="grand_totalDS" class="form-control" value="{{$ds->grand_total ?? ''}}">
                                    </div>
                                </div>
                            </div>
                            <div class="divider divider-left">
                                <div class="divider-text">
                                    Date Information
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-auto">
                                    <div class="form-group">
                                        <label for="">Order At</label>
                                        <input type="datetime-local" name="order_atDS" value="{{$ds->order_at}}" id="" class="form-control">
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="form-group">
                                        <label for="">Piutang At</label>
                                        <input type="datetime-local" name="piutang_atDS" value="{{$ds->piutang_at}}" id="" class="form-control">
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="form-group">
                                        <label for="">Lunas At</label>
                                        <input type="datetime-local" name="lunas_atDS" value="{{$ds->lunas_at}}" id="" class="form-control">
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="form-group">
                                        <label for="">Invoice Date</label>
                                        <input type="datetime-local" name="invoice_dateDS" value="{{$ds->invoice_date}}" id="" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            <div class="card">
                <div class="card-header">
                    <h4>Container List</h4>
                </div>
                <div class="card-body">
                    <div class="table">
                        <table class="table-hover" id="table1">
                            <thead>
                                <tr>
                                    <th>Container No</th>
                                    <th>Container Size</th>
                                    <th>Order Service</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($items as $item)
                                    <tr>
                                        <td>{{ $item->container_no }}</td>
                                        <td>{{ $item->ctr_size }}</td>
                                        <td>
                                            <select name="order_service[{{ $item->container_key }}]" class="form-select">
                                                <option value="SP2" {{ $item->order_service == 'SP2' ? 'selected' : '' }}>SP2</option>
                                                <option value="SPPS" {{ $item->order_service == 'SPPS' ? 'selected' : '' }}>SPPS</option>
                                            </select>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="card-footer">
        <div class="col-auto">
            <button type="button" id="submitButton" class="btn btn-success">Sumbit</button>
        </div>
    </div>
</div>

@endsection

@section('custom_js')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Attach event listener to the update button
        document.getElementById('submitButton').addEventListener('click', function (e) {
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
                    // Submit the form programmatically if confirmed
                       showLoading();
                    document.getElementById('updateForm').submit();
                }
            });
        });
    });
</script>
<script>
    $(document).ready(function(){
        $('#customer').on('change', function(){
                let id = $('#customer').val();
                showLoading();
                $.ajax({
                    type: 'get',
                    url: "{{ route('getCust')}}",
                    data : {id : id},
                    cache: false,
                    
                    success: function(response){
                        hideLoading();
                        $('#npwp').val(response.data.npwp);
                        $('#alamat').val(response.data.alamat);
                        $('#fax').val(response.data.fax);
                   
                    },
                    error: function(data){
                        console.log('error:',data)
                    },
                })
            })
    })
</script>

@endsection