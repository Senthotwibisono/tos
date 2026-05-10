@php
    $layout = auth()->user()->hasRole('admin') ? 'partial.main' : 'partial.android';
@endphp

@extends($layout)

@section('custom_styles')
<style>
    .select2-container--bootstrap-5 .select2-selection {
        border: 1px solid rgb(0, 0, 0) !important; /* Border berwarna biru */
        border-radius: 1px; /* Agar sudutnya sedikit melengkung */
        padding: 6px; /* Tambahkan padding agar terlihat lebih rapi */
        height: 100%;
    }   

    .select2-container--bootstrap-5 .select2-selection:focus {
        border-color: #0056b3 !important; /* Border berubah saat fokus */
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.5); /* Efek shadow saat fokus */
    }
</style>
@endsection

@section('content')

<div class="page-content">
    <div class="card">
        <div class="card-header">
            <h4>{{$title}}</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-auto">
                    <div class="form-group">
                        <label for="">Select By</label>
                        <select style="width: 100%;" class="selectMultiple" id="select" placeholder="Pilih Filter"  multiple></select>
                    </div>
                </div>
                <div class="col-auto">
                    <div class="form-group">
                        <label for="">Start Date</label>
                        <input type="date" class="form-control date" id="start">
                    </div>
                </div>
                <div class="col-auto">
                    <div class="form-group">
                        <label for="">End Date</label>
                        <input type="date" class="form-control date" id="end">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom_js')

@endsection