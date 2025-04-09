@extends('partial.customer.main')
@section('custom_styles')

@endsection

@section('content')

<div class="page-heading">
<h2>{{$title}}</h2> <br>
    <div class="button">
        <a href="{{route('customer.export.indexForm')}}" class="btn btn-success"><i class="fa fa-folder"></i> Create Invoice</a>    
    </div>
</div>

<div class="page-content">
   <div class="row match-height">
        <div class="col-12">
            <div class="card-group">
                <div class="card">
                    <div class="card-body text-center">
                        <h4>Total Invoice Muat</h4>
                        <a href="" class="btn btn-primary"><span class="badge bg-transparent">{{$exportTotal}}</span></a>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body text-center">
                        <h4>Paid Invoice</h4>
                        <a href="" class="btn btn-success"><span class="badge bg-transparent">{{$exportPaid}}</span></a>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body text-center">
                        <h4>Unpaid Invoice</h4>
                        <a href="" class="btn btn-warning"><span class="badge bg-transparent">{{$exportUnpaid}}</span></a>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body text-center">
                        <h4>Canceled Invoice</h4>
                        <a href="" class="btn btn-danger"><span class="badge bg-transparent">{{$exportCanceled}}</span></a>
                    </div>
                </div>
            </div>
        </div>
   </div>

   <div class="card">
    <div class="card-header">
        <h4>Table Invoice export</h4>
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
                        <td class="text-center text-white">{{$exportUnpaid}}</td>
                        <td class="text-center text-white">Rp. {{number_format($exportUnpaidAmount ?? '0'), 2, ',', '.'}}</td>
                        <td class="text-center"><a href="{{route('customer.export.indexDetail')}}" class="text-white">See more...</a></td>
                    </tr>
                    <tr style="background-color: yellow;">
                        <td>Piutang</td>
                        <td class="text-center">{{$exportPiutang}}</td>
                        <td class="text-center">Rp. {{number_format($exportPiutangAmount ?? '0'), 2, ',', '.'}}</td>
                        <td class="text-center"><a href="{{route('customer.export.indexDetail')}}">See more...</a></td>
                    </tr>
                    @foreach($orderService as $os)
                    <tr>
                        <td>{{$os->name}}</td>
                        <td class="text-center">{{$invoice->where('os_id', $os->id)->count() ?? ''}}</td>
                        <td class="text-center">Rp. {{number_format($invoice->where('os_id', $os->id)->sum('grand_total') ?? ''), 2, ',', '.'}}</td>
                        <td class="text-center"><a href="{{route('customer.export.indexDetail')}}">See more...</a></td>
                    </tr>
                    @endforeach
                    <tr style="background-color: maroon;">
                        <td class="text-white">Canceled</td>
                        <td class="text-center text-white">{{$exportCanceled}}</td>
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