@extends('partial.invoice.main')

@section('custom_styles')

@endsection

@section('content')

<section>
    <div class="page-content">
        <div class="card mb-3">
            <div class="card-header">
                <div class="text-center">
                    <h4><b>{{$title}}</b></h4>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="row">
                                    <div class="col-3">
                                        Proforma No
                                    </div>
                                    <div class="col-1">
                                        :
                                    </div>
                                    <div class="col-auto">
                                        {{$header->proforma_no}}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-3">
                                        Invoice No
                                    </div>
                                    <div class="col-1">
                                        :
                                    </div>
                                    <div class="col-auto">
                                        {{$header->inv_no}}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-3">
                                        Customer
                                    </div>
                                    <div class="col-1">
                                        :
                                    </div>
                                    <div class="col-auto">
                                        {{$header->customer->name}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="row">
                                    <div class="col-3">
                                        Container No
                                    </div>
                                    <div class="col-1">
                                        :
                                    </div>
                                    <div class="col-auto">
                                        {{$nomorContainers}}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-3">
                                        Perpanjangan Dari
                                    </div>
                                    <div class="col-1">
                                        :
                                    </div>
                                    <div class="col-auto">
                                        {{$header->form->disc_date}}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-3">
                                        Sampai Dengan
                                    </div>
                                    <div class="col-1">
                                        :
                                    </div>
                                    <div class="col-auto">
                                        {{$header->expired_date}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-hover table-responsive">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Jumlah</th>
                            <th>Jumlah Hari</th>
                            <th>Tarif</th>
                            <th>Amount</th>
                            <th>Submit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($detils as $detil)
                            <tr>
                                <form action="/billing/import/extend-updateDetil" method="post">
                                    @csrf
                                    <input type="hidden" name="detilId" value="{{$detil->id}}">
                                    <td>{{$detil->master_item_name}}</td>
                                    <td>{{$detil->jumlah}}</td>
                                    <td>
                                        <input type="number" name="jumlah_hari" id="" class="form-control" value="{{$detil->jumlah_hari}}" style="width: 30%;">
                                    </td>
                                    <td>{{$detil->tarif}}</td>
                                    <td>
                                        <input type="number" name="total" id="" class="form-control" value="{{$detil->total}}" style="width: 30%;">
                                    </td>
                                    <td><button type="submit" class="btn btn-success">Submit</button></td>
                                </form>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="divider divider-center">
                    <div class="divider-text">
                        Header Form
                    </div>
                </div>
                <div class="row">
                    <form action="/billing/import/extend-update/{{$header->id}}" method="post">
                        @csrf
                        <div class="col-12">
                            <div class="row">
                                <div class="col-auto">
                                    <label for="">Customer</label>
                                    <select name="cust_id" id="" class="js-example-basic-single form-select select2" style="width: 100%">
                                        <option disabled selected value>Pilih Satu</option>
                                        @foreach ($customers as $customer)
                                            <option value="{{$customer->id}}" {{$header->cust_id == $customer->id ? 'selected' : ''}}>{{$customer->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-auto">
                                    <label for="">Tanggal Buat</label>
                                    <input type="datetime-local" name="order_at" id="" value="{{$header->order_at}}" class="form-control">
                                </div>
                                <div class="col-auto">
                                    <label for="">Piutang Pada</label>
                                    <input type="datetime-local" name="piutang_at" id="" value="{{$header->piutang_at}}" class="form-control">
                                </div>
                                <div class="col-auto">
                                    <label for="">Lunas Pada</label>
                                    <input type="datetime-local" name="lunas_at" id="" value="{{$header->lunas_at}}" class="form-control">
                                </div>
                                <div class="col-auto">
                                    <label for="">Expired Date</label>
                                    <input type="datetime-local" name="expired_date" id="" value="{{$header->expired_date}}" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="row">
                                <div class="col-auto">
                                    <div class="form-group">
                                        <label for="">Total</label>
                                        <input type="number" class="form-control" step="0.00001" name="total" id="" value="{{$header->total}}">
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="form-group">
                                        <label for="">Admin</label>
                                        <input type="number" class="form-control" step="0.00001" name="admin" id="" value="{{$header->admin}}">
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="form-group">
                                        <label for="">PPN</label>
                                        <input type="number" class="form-control" step="0.00001" name="pajak" id="" value="{{$header->pajak}}">
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="form-group">
                                        <label for="">Discount</label>
                                        <input type="number" class="form-control" step="0.00001" name="discount" id="" value="{{$header->discount}}">
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="form-group">
                                        <label for="">Grand Total</label>
                                        <input type="number" class="form-control" step="0.00001" name="grand_total" id="" value="{{$header->grand_total}}">
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-success">Submit</button>
                                </div>
                            </div>
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
            </div>
        </div>
    </div>
</section>

@endsection

@section('custom_js')

@endsection