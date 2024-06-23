@extends('partial.android')
@section('custom_styles')

@endsection
@section('content')

<div class="page-heading">
  <h3><?= $title ?></h3>
  <p>Report By Vessel</p>
</div>
<section>
    <div class="row">
        <div class="col-6">
            <div class="card">
                <div class="card-body px-4 py-4-5">
                    <div class="row">
                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                            <div class="stats-icon blue mb-2">
                                <i class="fa-solid fa-box"></i>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                            <h6 class="text-muted font-semibold">
                                Container Count
                            </h6>
                            <h6 class="font-extrabold mb-0">
                                {{$containerTotal}}
                            </h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-3">
            <div class="card">
                <div class="card-body px-4 py-4-5">
                    <div class="row">
                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                            <div class="stats-icon red mb-2">
                                <i class="fa-solid fa-box"></i>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                            <h6 class="text-muted font-semibold">
                                Import
                            </h6>
                            <h6 class="font-extrabold mb-0">
                                {{$import}}
                            </h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-3">
            <div class="card">
                <div class="card-body px-4 py-4-5">
                    <div class="row">
                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                            <div class="stats-icon purple mb-2">
                                <i class="fa-solid fa-box"></i>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                            <h6 class="text-muted font-semibold">
                                Export
                            </h6>
                            <h6 class="font-extrabold mb-0">
                                {{$export}}
                            </h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h4>Vessel List</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <form action="/container/sortByVes/{{$id}}" method="get">
                        <div class="row">
                            <div class="col-3">
                                <label for="">Import/Export</label>
                                <select name="ctr_i_e_t" class="form-select choices">
                                    <option selected value>Pilih Satu!</option>
                                    <option value="I" {{isset($_GET['ctr_i_e_t']) && $_GET['ctr_i_e_t'] == 'I' ? 'selected' : ''}}>Import</option>
                                    <option value="E" {{isset($_GET['ctr_i_e_t']) && $_GET['ctr_i_e_t'] == 'E' ? 'selected' : ''}}>Export</option>
                                </select>
                            </div>
                            <div class="col-2">
                                <label for="">Active Y/N</label>
                                <select name="ctr_active_yn" class="form-select choices">
                                    <option  selected value>Pilih Satu!</option>
                                    <option value="Y" {{isset($_GET['ctr_active_yn']) && $_GET['ctr_active_yn'] == 'Y' ? 'selected' : ''}}>Active</option>
                                    <option value="N" {{isset($_GET['ctr_active_yn']) && $_GET['ctr_active_yn'] == 'N' ? 'selected' : ''}}>No</option>
                                </select>
                            </div>
                            <div class="col-2">
                                <label for="">Is Damage</label>
                                <select name="is_damage" class="form-select choices">
                                    <option  selected value>Pilih Satu!</option>
                                    <option value="Y" {{isset($_GET['is_damage']) && $_GET['is_damage'] == 'Y' ? 'selected' : ''}}>Damage</option>
                                    <option value="N" {{isset($_GET['is_damage']) && $_GET['is_damage'] == 'N' ? 'selected' : ''}}>No</option>
                                </select>
                            </div>
                            <div class="col-2">
                                <label for="">Size</label>
                                <select name="ctr_size" class="form-select choices">
                                    <option  selected value>Pilih Satu!</option>
                                    <option value="20" {{isset($_GET['ctr_size']) && $_GET['ctr_size'] == '20' ? 'selected' : ''}}>20</option>
                                    <option value="21" {{isset($_GET['ctr_size']) && $_GET['ctr_size'] == '21' ? 'selected' : ''}}>21</option>
                                    <option value="40" {{isset($_GET['ctr_size']) && $_GET['ctr_size'] == '40' ? 'selected' : ''}}>40</option>
                                    <option value="41" {{isset($_GET['ctr_size']) && $_GET['ctr_size'] == '41' ? 'selected' : ''}}>41</option>
                                </select>
                            </div>
                            <div class="col-2">
                                <label for="">Status</label>
                                <select name="ctr_status" class="form-select choices">
                                    <option  selected value>Pilih Satu!</option>
                                    <option value="FCL" {{isset($_GET['ctr_status']) && $_GET['ctr_status'] == 'FCL' ? 'selected' : ''}}>FCL</option>
                                    <option value="MTY" {{isset($_GET['ctr_status']) && $_GET['ctr_status'] == 'MTY' ? 'selected' : ''}}>MTY</option>
                                </select>
                            </div>
                            <div class="col-2">
                                <label for="">Intern Status</label>
                                <select name="ctr_intern_status" class="form-select choices">
                                    <option  selected value>Pilih Satu!</option>
                                    @foreach($intern as $cti)
                                        <option value="{{$cti}}" {{isset($_GET['ctr_intern_status']) && $_GET['ctr_intern_status'] == $cti ? 'selected' : ''}}>{{$cti}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-2">
                                <label for="">Type</label>
                                <input type="text" name="ctr_type" class="form-control" value="{{isset($_GET['ctr_type']) ? $_GET['ctr_type'] : ''}}">
                            </div>
                            <div class="col-2">
                                <label for="">Operator</label>
                                <input type="text" name="ctr_opr" class="form-control" value="{{isset($_GET['ctr_opr']) ? $_GET['ctr_opr'] : ''}}">
                            </div>
                            <div class="col-2">
                                <label for="">Container No</label>
                                <input type="text" name="container_no" class="form-control" value="{{isset($_GET['container_no']) ? $_GET['container_no'] : ''}}">
                            </div>
                            <div class="col-sm-3">
                                <button type="submit" class="btn btn-primary mt-4">Search</button>
                                <a href="/container/sortByVes/{{$id}}" class="btn btn-secondary mt-4">Clear</a>
                            </div>
                        </div>
                    </form>
                    @include('container.detail.table', $containers)
                </div>
                <div class="card-footer">
                    <a href="/container/sortByVes/export/{{$id}}?{{ http_build_query($_GET) }}"" class="btn btn-success float-end">Export to Excel</a>
                    <!-- <a href=""></a> -->
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
@section('custom_js')
<!-- <script>
    $(document).ready(function() {
        $('.dataTable-wrapper').each(function() {
            $(this).DataTable();
        });
    });
</script> -->

<script>
    function openWindow(url) {
        window.open(url, '_blank', 'width=1000,height=1000');
    }
</script>


@endsection