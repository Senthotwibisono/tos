@extends('partial.main')
@section('custom_styles')
<style>
</style>
@endsection

@section('content')

<div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Realisasi Bongkar Report</h3>
                    <p class="text-subtitle text-muted"></p>
                </div>

               
            </div>
        </div>
<div class="container">
    <div class="card mt-5">
        <div class="card-header">
          
             <div class="row">
                    <div class="col-lg-3 mb-1">
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="">Choose Ves Id</span>
                                <select class="form-select" id="vesid" name="ves_id">
                                    <option value="-">-</option>
                                    @foreach($item as $itm)
                                        
                                    <option value="{{$itm}}">{{str_pad($itm,4,'0', STR_PAD_LEFT)}}</option>
                                    @endforeach
                                </select>
                        </div>
                    </div>
                    <div class="col-lg-2 mb-1">
                        <div class="input-group mb-3">
                            <span class="input-group-text">Ves Code</span>
                            <input type="text" class="form-control" id="code"  name="ves_code" disabled>
                        </div>
                    </div>
                    <div class="col-lg-3 mb-1">
                        <div class="input-group mb-3">
                            <span class="input-group-text">Ves Name</span>
                            <input type="text" class="form-control" id="name" name="ves_name"  disabled>
                        </div>
                    </div>
                   
                    <div class="col-lg-2 mb-1">
                        <a href="#" class="btn icon btn-info search"><i class="bi bi-search"></i></i></a>
                    </div>
            </div>
           
        </div>
        <hr>
        <div class="card-body">
    
        <h2>Review Dokumen</h2>
        <button id="cetakpdf" class="btn btn-primary">Cetak PDF</button>

    <!-- Konten lainnya -->
    <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns" id="table1">
        <thead>
            <tr>
                <th>Bay</th>
                <th>Row</th>
                <th>Tier</th>
                <th>Container No</th>
                <th>Size</th>
                <th>status</th>
                <th>Type</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
       


        </div>
    </div>
</div>

@endsection

@section('custom_js')
<script src="{{ asset('vendor/components/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('select2/dist/js/select2.full.min.js') }}"></script>
<script src="{{asset('dist/assets/extensions/sweetalert2/sweetalert2.min.js')}}"></script>    
<script src="{{asset('dist/assets/js/pages/sweetalert2.js')}}"></script>

<script>
// In your Javascript (external .js resource or <script> tag)
// $(document).ready(function() {
//     $('.vesid').select2();
// });

$(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(document).ready(function() {
            $('#vesid').on('change', function() {
                let id = $(this).val();
                $.ajax({
                    type: 'POST',
                    url: '/realisasi-get-ves',
                    data: { ves_id : id },
                    success: function(response) {
                       
                            $('#name').val(response.name);
                            $('#code').val(response.code);
                            
                        },
                    error: function(data) {
                        console.log('error:', data);
                    },
                });
            });
    });
});

$(function(){
        $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
$(function(){
            $('#vesid'). on('change', function(){
                let ves_id = $('#vesid').val();

                $.ajax({
                    type: 'POST',
                    url: '/realisasi-get-bay',
                    data : {ves_id : ves_id},
                    cache: false,
                    
                    success: function(msg){
                        $('#bay').html(msg);
                   
                    },
                    error: function(data){
                        console.log('error:',data)
                    },
                })                
            })
        })
    });

    $(document).on('click', '.search', function(e) {
    e.preventDefault();
    
    var vesId = $('#vesid').val();

    $.ajax({
        url: "/realisasi-get-container",
        type: "GET",
        data: { ves_id: vesId },
        success: function(response) {
            var tableBody = $('#table1 tbody');
            tableBody.empty(); // Menghapus konten tabel sebelumnya

            // Memasukkan data container ke dalam tabel
            response.forEach(function(item) {
                var newRow = $('<tr>');
                newRow.append('<td>' + item.bay_slot + '</td>');
                newRow.append('<td>' + item.bay_row + '</td>');
                newRow.append('<td>' + item.bay_tier + '</td>');
                newRow.append('<td>' + item.container_no + '</td>');
                newRow.append('<td>' + item.ctr_size + '</td>');
                newRow.append('<td>' + item.ctr_type + '</td>');
                newRow.append('<td>' + item.ctr_status + '</td>');
                tableBody.append(newRow);
            });
        },
        error: function(data) {
            console.log('error:', data);
        }
    });
});

$(document).on('click', '.btn-print-pdf', function(e) {
    e.preventDefault();

    // Mengambil data dari tabel
    var tableData = [];
    $('#table1 tbody tr').each(function() {
        var rowData = [];
        $(this).find('td').each(function() {
            rowData.push($(this).text());
        });
        tableData.push(rowData);
    });

    // Mengirim data ke server untuk mencetak PDF
    $.ajax({
        url: "{{ url('/generate-pdf') }}",
        method: 'POST',
        data: { tableData: tableData },
        success: function(response) {
            // Mengarahkan pengguna ke URL PDF yang dihasilkan
            window.open(response.url, '_blank');
        },
        error: function(data) {
            console.log('error:', data);
        }
    });
});

document.getElementById("cetakpdf").addEventListener("click", function() {
        var vesId = document.getElementById("vesid").value;
        var url = "{{ url('/generate-pdf-bongkar') }}?ves_id=" + vesId;
        window.open(url, "_blank");
    });

</script>

@endsection
