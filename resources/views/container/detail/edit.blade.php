@extends('partial.main')
@section('content')

<div class="page-heading">
  <h3><?= $title ?></h3>
  <p></p>
</div>
<section>
    <div class="card">
        <div class="card-header">
            <h4> </h4>
        </div>
        <form action="{{ route('container-update')}}" method="post">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="">Container No</label>
                            <input type="text" disabled class="form-control" value="{{$cont->container_no}}">
                            <input type="hidden" name="container_key" class="form-control" value="{{$cont->container_key}}">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="">Import/Export</label>
                            <input type="text" disabled class="form-control" @if($cont->ctr_i_e_t == "I") value="Import" @else value="Export" @endif>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="">Vessel Name</label>
                            <input type="text" disabled class="form-control" value="{{$cont->ves_name}}">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="">Voy No</label>
                            <input type="text" disabled class="form-control" value="{{$cont->voy_no}}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="">Iso Code</label>
                            <select name="iso_code" class="form-select choices">
                                <option disabled selected value>Pilih Satu!</option>
                                @foreach ($isoCode as $iso)
                                    <option value="{{$iso->iso_code}}"{{$cont->iso_code == $iso->iso_code ? 'selected' : ''}}>{{$iso->iso_code}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="">Active</label>
                            <select name="ctr_active_yn" class="form-select choices">
                                <option disabled selected value>Pilih Satu!</option>
                                <option value="Y" {{$cont->ctr_active_yn == 'Y' ? 'selected' : ''}}>Yes</option>
                                <option value="N" {{$cont->ctr_active_yn == 'N' ? 'selected' : ''}}>No</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="">Is Damage</label>
                            <select name="is_damage" class="form-select choices">
                                <option disabled selected value>Pilih Satu!</option>
                                <option value="Y" {{$cont->is_damage == 'Y' ? 'selected' : ''}}>Yes</option>
                                <option value="N" {{$cont->is_damage == 'N' ? 'selected' : ''}}>No</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="">Operator/MLO</label>
                            <input type="text" class="form-control" name="ctr_opr" value="{{$cont->ctr_opr}}">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="row mt-5">
                    <div class="col-12">
                        <button class="btn btn-warning" type="submit">Update</button>
                        <a href="/container/sortByVes/{{$cont->ves_id}}" class="btn btn-secondary">Back</a>
                    </div>
                </div>
            </div>
        </form>
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