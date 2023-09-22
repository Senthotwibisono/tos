@extends('partial.main')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/searchpanes/2.2.0/css/searchPanes.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/select/1.7.0/css/select.dataTables.min.css">
<link href="https://cdn.datatables.net/v/bs5/jq-3.7.0/jszip-3.10.1/dt-1.13.6/af-2.6.0/b-2.4.2/b-colvis-2.4.2/b-html5-2.4.2/b-print-2.4.2/cr-1.7.0/date-1.5.1/fc-4.3.0/fh-3.4.0/kt-2.10.0/r-2.5.0/rg-1.4.0/rr-1.4.1/sc-2.2.0/sb-1.5.0/sp-2.2.0/sl-1.7.0/sr-1.3.0/datatables.css" rel="stylesheet">

@section('custom_styles')

@endsection
@section('content')


<div class="page-heading">
  <div class="page-title">
    <div class="row">
      <div class="col-12 col-md-6 order-md-1 order-last">
        <h3>Dokumen RO</h3>
      </div>

      <div class="col-12 col-md-6 order-md-2 order-first">
        <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
          <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">Doc R.O</li>
          </ol>
        </nav>
      </div>
    </div>
  </div>


    <div class="card">
        <div class="card-header">
            <h4>Waiting to Out</h4>
        </div>
        <div class="card-body">
            <table id="example" class="display wrap">
                <thead>
                    <tr>
                        <th>Container No</th>
                        <th>Ves Name</th>
                        <th>Iso Code</th>
                        <th>Type</th>
                        <th>Size</th>
                        <th>Intern Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                    <tr>
                        <td>{{$item->container_no}}</td>
                        <td>{{$item->ves_name}}</td>
                        <td>{{$item->iso_code}}</td>
                        <td>{{$item->ctr_type}}</td>
                        <td>{{$item->ctr_size}}</td>
                        <td>
                            @if($item->ctr_intern_status === '01')
                                In Vessel Import
                            @elseif($item->ctr_intern_status === '02')
                                Confirmed Disch
                            @elseif($item->ctr_intern_status === '03')
                                Placed Import
                            @elseif($item->ctr_intern_status === '04')
                                Stripped
                            @elseif($item->ctr_intern_status === '06')
                                Stuffing Out
                            @elseif($item->ctr_intern_status === '10')
                                Picked
                            @elseif($item->ctr_intern_status === '49')
                                Export Listed
                                @elseif($item->ctr_intern_status === '50')
                                Procces to Export
                                @elseif($item->ctr_intern_status === '51')
                                Placed Export
                                @elseif($item->ctr_intern_status === '53')
                                Stuffed
                                @elseif($item->ctr_intern_status === '56')
                                Confrimed Load
                                @else
                                @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>Caontainer No</th>
                        <th>Ves Name</th>
                        <th>Iso Code</th>
                        <th>Type</th>
                        <th>Size</th>
                        <th>Intenr Status</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>


@endsection
@section('custom_js')
    <script src="{{ asset('vendor/components/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{asset('dist/assets/extensions/sweetalert2/sweetalert2.min.js')}}"></script>
    <script src="{{asset('dist/assets/js/pages/sweetalert2.js')}}"></script>
 
<script src="https://cdn.datatables.net/v/bs5/jq-3.7.0/jszip-3.10.1/dt-1.13.6/af-2.6.0/b-2.4.2/b-colvis-2.4.2/b-html5-2.4.2/b-print-2.4.2/cr-1.7.0/date-1.5.1/fc-4.3.0/fh-3.4.0/kt-2.10.0/r-2.5.0/rg-1.4.0/rr-1.4.1/sc-2.2.0/sb-1.5.0/sp-2.2.0/sl-1.7.0/sr-1.3.0/datatables.js"></script>
<script>


    $(document).ready(function() {
    $('#example tfoot th').each(function() {
        var title = $(this).text();
        $(this).html('<input type="text" placeholder="Search ' + title + '" />');
    });
 
    var table = $('#example').DataTable({
        searchPanes: {
            viewTotal: true
        },
        dom: 'Plfrtip'
    });
 
     table.columns().every( function() {
        var that = this;
  
        $('input', this.footer()).on('keyup change', function() {
            if (that.search() !== this.value) {
                that
                    .search(this.value)
                    .draw();
            }
        });
    });
});
</script>
@endsection