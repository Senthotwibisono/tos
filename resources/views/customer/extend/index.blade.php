@extends('partial.customer.main')
@section('custom_styles')

@endsection

@section('content')

<div class="page-heading">
<h2>{{$title}}</h2> <br>
    <div class="button">
        <a href="/customer-extend/formList" class="btn btn-success"><i class="fa fa-folder"></i> Create Invoice</a>    
    </div>
</div>

<div class="page-content">
   <div class="row match-height">
        <div class="col-12">
            <div class="card-group">
                <div class="card">
                    <div class="card-body text-center">
                        <h4>Total Invoice Bongkar</h4>
                        <a href="" class="btn btn-primary"><span class="badge bg-transparent">{{$extendTotal}}</span></a>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body text-center">
                        <h4>Paid Invoice</h4>
                        <a href="" class="btn btn-success"><span class="badge bg-transparent">{{$extendPaid}}</span></a>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body text-center">
                        <h4>Unpaid Invoice</h4>
                        <a href="" class="btn btn-warning"><span class="badge bg-transparent">{{$extendUnpaid}}</span></a>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body text-center">
                        <h4>Canceled Invoice</h4>
                        <a href="" class="btn btn-danger"><span class="badge bg-transparent">{{$extendCanceled}}</span></a>
                    </div>
                </div>
            </div>
        </div>
   </div>

   <div class="card">
    <div class="card-header">
        <h4>Table Invoice Import</h4>
    </div>
    <div class="card-body">
        <div class="table table-responsive">
            <table class="table tabel-hover table-responsive">
                <thead>
                    <tr>
                        <th class="">Order Service</th>
                        <th class="text-center">Invoice Count</th>
                        <th class="text-center">Grand Total</th>
                        <th class="text-center">Detil</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="background-color: orange;">
                        <td class="text-white">Unpaid</td>
                        <td class="text-center text-white">{{$extendUnpaid}}</td>
                        <td class="text-center text-white">Rp. {{number_format($extendUnpaidAmount ?? '0'), 2, ',', '.'}}</td>
                        <td class="text-center"><a href="{{route('customer.extend.listIndex')}}" class="text-white">See more...</a></td>
                    </tr>
                    <tr style="background-color: yellow;">
                        <td>Piutang</td>
                        <td class="text-center">{{$extendPiutang}}</td>
                        <td class="text-center">Rp. {{number_format($extendPiutangAmount ?? '0'), 2, ',', '.'}}</td>
                        <td class="text-center"><a href="{{route('customer.extend.listIndex')}}">See more...</a></td>
                    </tr>
                    @foreach($orderService as $os)
                    <tr>
                        <td>{{$os->name}}</td>
                        <td class="text-center">{{$invoice->where('os_id', $os->id)->count() ?? ''}}</td>
                        <td class="text-center">Rp. {{number_format($invoice->where('os_id', $os->id)->sum('grand_total') ?? ''), 2, ',', '.'}}</td>
                        <td class="text-center"><a href="{{route('customer.extend.listIndex')}}">See more...</a></td>
                    </tr>
                    @endforeach
                    <tr style="background-color: maroon;">
                        <td class="text-white">Canceled</td>
                        <td class="text-center text-white">{{$extendCanceled}}</td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
   </div>
</div>

@endsection

@section('custom_js')

@endsection