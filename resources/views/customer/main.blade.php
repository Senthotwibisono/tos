@extends ('partial.customer.main')
@section('custom_styles')

<link rel="stylesheet" href="{{asset('dist/assets/extensions/filepond/filepond.css')}}">
<link rel="stylesheet" href="{{asset('dist/assets/extensions/filepond-plugin-image-preview/filepond-plugin-image-preview.css')}}">
<link rel="stylesheet" href="{{asset('dist/assets/extensions/toastify-js/src/toastify.css')}}">
<link rel="stylesheet" href="{{asset('dist/assets/css/pages/filepond.css')}}">

<style>
.panel {margin: 100px auto 40px; max-width: 500px; text-align: center;}
.button_outer {background: #83ccd3; border-radius:30px; text-align: center; height: 50px; width: 200px; display: inline-block; transition: .2s; position: relative; overflow: hidden;}
.btn_upload {padding: 17px 30px 12px; color: #fff; text-align: center; position: relative; display: inline-block; overflow: hidden; z-index: 3; white-space: nowrap;}
.btn_upload input {position: absolute; width: 100%; left: 0; top: 0; width: 100%; height: 105%; cursor: pointer; opacity: 0;}
.file_uploading {width: 100%; height: 10px; margin-top: 20px; background: #ccc;}
.file_uploading .btn_upload {display: none;}
.processing_bar {position: absolute; left: 0; top: 0; width: 0; height: 100%; border-radius: 30px; background:#83ccd3; transition: 3s;}
.file_uploading .processing_bar {width: 100%;}
.success_box {display: none; width: 50px; height: 50px; position: relative;}
.success_box:before {content: ''; display: block; width: 9px; height: 18px; border-bottom: 6px solid #fff; border-right: 6px solid #fff; -webkit-transform:rotate(45deg); -moz-transform:rotate(45deg); -ms-transform:rotate(45deg); transform:rotate(45deg); position: absolute; left: 17px; top: 10px;}
.file_uploaded .success_box {display: inline-block;}
.file_uploaded {margin-top: 0; width: 50px; background:#83ccd3; height: 50px;}
.uploaded_file_view {max-width: 300px; margin: 40px auto; text-align: center; position: relative; transition: .2s; opacity: 0; border: 2px solid #ddd; padding: 15px;}
.file_remove{width: 30px; height: 30px; border-radius: 50%; display: block; position: absolute; background: #aaa; line-height: 30px; color: #fff; font-size: 12px; cursor: pointer; right: -15px; top: -15px;}
.file_remove:hover {background: #222; transition: .2s;}
.uploaded_file_view img {max-width: 100%;}
.uploaded_file_view.show {opacity: 1;}
.error_msg {text-align: center; color: #f00}
.round-image {
  width: 300px; /* Sesuaikan dengan lebar yang diinginkan */
  height: 300px; /* Sesuaikan dengan tinggi yang diinginkan */
  border-radius: 50%;
  overflow: hidden;
}

.round-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  object-position: center;
}
</style>

@endsection

@section('content')

<div class="page-content">
    <div class="card">
        <div class="card-body">
            <div class="row d-flex justify-content-center align-items-center vh-50">
                <div class="col-4">
                    <div class="round-image" id="uploaded_view" data-bs-toggle="modal" data-bs-target="#galleryModal-{{ $user->id }}">
                        @if ($user->profil)
                            <img class="w-100 active" src="{{ asset('profil/' .$user->profil) }}" data-bs-target="#Gallerycarousel" data-bs-slide-to="0">
                        @else
                            <img class="w-100 active" src="{{ asset('dist/assets/images/faces/1.jpg') }}" data-bs-target="#Gallerycarousel" data-bs-slide-to="0">
                        @endif
                    </div>
                </div>
                <div class="col-8">
                    <div class="text-center">
                        <h1>Welcome to IKS Billing System, {{$user->name}}</h1><br>
                        <p>You handle {{$user->customerCount()}} customer :</p>
                        <div class="table table-responsive fixed-height">
                            <table class="tabelCustom table table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center">Customer Name</th>
                                        <th class="text-center">Customer Code</th>
                                        <th class="text-center">Customer NPWP</th>
                                        <th class="text-center">Customer Fax</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($customerList as $cust)
                                        <tr>
                                            <td>{{$cust->name}}</td>
                                            <td>{{$cust->code}}</td>
                                            <td>{{$cust->npwp}}</td>
                                            <td>{{$cust->fax}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <br>
                            <!-- @if($user->customerCount() > 3)
                            <a href=""><span>See more...</span></a>
                            @endif -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="row">
        <!-- Bongkar -->
        <div class="col-sm-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="text-center">Transaksi Bongkar</h4>
                </div>
                <div class="card-body d-flex justify-content-center align-items-center">
                    <div class="table table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th class="text-center">-</th>
                                    <th class="text-center">Lunas</th>
                                    <th class="text-center">Unpaid</th>
                                    <th class="text-center"><strong>Total</strong></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center">DSK</td>
                                    @if($dsk->isEmpty())
                                    <td class="text-center" colspan="3">Belum ada transaksi</td>
                                    @else
                                    <td class="text-center">{{$dskPaid ?? 'Belum Ada Transaksi'}} <strong>Rp. {{number_format($dskPaidAmount), 2, ',', '.'}}</strong></td>
                                    <td class="text-center">{{$dskUnpaid ?? 'Belum Ada Transaksi'}} <strong>Rp. {{number_format($dskUnpaidAmount), 2, ',', '.'}}</strong></td>
                                    <td class="text-center">{{$dskTotal ?? 'Belum Ada Transaksi'}} <strong>Rp. {{number_format($dskTotalAmount), 2, ',', '.'}}</strong></td>
                                    @endif
                                </tr>
                                <tr>
                                    <td class="text-center">DS</td>
                                    @if($ds->isEmpty())
                                    <td class="text-center" colspan="3">Belum ada transaksi</td>
                                    @else
                                    <td class="text-center">{{$dsPaid ?? 'Belum Ada Transaksi'}} <strong>Rp. {{number_format($dsPaidAmount), 2, ',', '.'}}</strong></td>
                                    <td class="text-center">{{$dsUnpaid ?? 'Belum Ada Transaksi'}} <strong>Rp. {{number_format($dsUnpaidAmount), 2, ',', '.'}}</strong></td>
                                    <td class="text-center">{{$dsTotal ?? 'Belum Ada Transaksi'}} <strong>Rp. {{number_format($dsTotalAmount), 2, ',', '.'}}</strong></td>
                                    @endif
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td class="text-center"><strong>Total</strong></td>
                                    @if($ds->isEmpty() && $dsk->isEmpty())
                                    <td class="text-center" colspan="3">Belum ada transaksi</td>
                                    @else
                                    <td class="text-center">{{$importPaid ?? 'Belum Ada Transaksi'}} <strong>Rp. {{number_format($importPaidAmount), 2, ',', '.'}}</strong></td>
                                    <td class="text-center">{{$importUnpaid ?? 'Belum Ada Transaksi'}} <strong>Rp. {{number_format($importUnpaidAmount), 2, ',', '.'}}</strong></td>
                                    <td class="text-center">{{$importTotal ?? 'Belum Ada Transaksi'}} <strong>Rp. {{number_format($importTotalAmount), 2, ',', '.'}}</strong></td>
                                    @endif
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Export -->
        <div class="col-sm-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="text-center">Transaksi Muat</h4>
                </div>
                <div class="card-body d-flex justify-content-center align-items-center">
                    <div class="table table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th class="text-center">-</th>
                                    <th class="text-center">Lunas</th>
                                    <th class="text-center">Unpaid</th>
                                    <th class="text-center"><strong>Total</strong></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center">OSK</td>
                                    @if($osk->isEmpty())
                                    <td class="text-center" colspan="3">Belum ada transaksi</td>
                                    @else
                                    <td class="text-center">{{$oskPaid ?? 'Belum Ada Transaksi'}} <strong>Rp. {{number_format($oskPaidAmount), 2, ',', '.'}}</strong></td>
                                    <td class="text-center">{{$oskUnpaid ?? 'Belum Ada Transaksi'}} <strong>Rp. {{number_format($oskUnpaidAmount), 2, ',', '.'}}</strong></td>
                                    <td class="text-center">{{$oskTotal ?? 'Belum Ada Transaksi'}} <strong>Rp. {{number_format($oskTotalAmount), 2, ',', '.'}}</strong></td>
                                    @endif
                                </tr>
                                <tr>
                                    <td class="text-center">OS</td>
                                    @if($os->isEmpty())
                                    <td class="text-center" colspan="3">Belum ada transaksi</td>
                                    @else
                                    <td class="text-center">{{$osPaid ?? 'Belum Ada Transaksi'}} <strong>Rp. {{number_format($osPaidAmount), 2, ',', '.'}}</strong></td>
                                    <td class="text-center">{{$osUnpaid ?? 'Belum Ada Transaksi'}} <strong>Rp. {{number_format($osUnpaidAmount), 2, ',', '.'}}</strong></td>
                                    <td class="text-center">{{$osTotal ?? 'Belum Ada Transaksi'}} <strong>Rp. {{number_format($osTotalAmount), 2, ',', '.'}}</strong></td>
                                    @endif
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td class="text-center"><strong>Total</strong></td>
                                    @if($os->isEmpty() && $osk->isEmpty())
                                    <td class="text-center" colspan="3">Belum ada transaksi</td>
                                    @else
                                    <td class="text-center">{{$exportPaid ?? 'Belum Ada Transaksi'}} <strong>Rp. {{number_format($exportPaidAmount), 2, ',', '.'}}</strong></td>
                                    <td class="text-center">{{$exportUnpaid ?? 'Belum Ada Transaksi'}} <strong>Rp. {{number_format($exportUnpaidAmount), 2, ',', '.'}}</strong></td>
                                    <td class="text-center">{{$exportTotal ?? 'Belum Ada Transaksi'}} <strong>Rp. {{number_format($exportTotalAmount), 2, ',', '.'}}</strong></td>
                                    @endif
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Perpanjangan -->
        <div class="col-sm-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="text-center">Transaksi Perpanjangan</h4>
                </div>
                <div class="card-body d-flex justify-content-center align-items-center">
                    <div class="table table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th class="text-center">-</th>
                                    <th class="text-center">Lunas</th>
                                    <th class="text-center">Unpaid</th>
                                    <th class="text-center"><strong>Total</strong></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center">DS-P</td>
                                    @if($extend->isEmpty())
                                    <td class="text-center" colspan="3">Belum ada transaksi</td>
                                    @else
                                    <td class="text-center">{{$extendPaid ?? 'Belum Ada Transaksi'}} <strong>Rp. {{number_format($extendPaidAmount), 2, ',', '.'}}</strong></td>
                                    <td class="text-center">{{$extendUnpaid ?? 'Belum Ada Transaksi'}} <strong>Rp. {{number_format($extendUnpaidAmount), 2, ',', '.'}}</strong></td>
                                    <td class="text-center">{{$extendTotal ?? 'Belum Ada Transaksi'}} <strong>Rp. {{number_format($extendTotalAmount), 2, ',', '.'}}</strong></td>
                                    @endif
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection