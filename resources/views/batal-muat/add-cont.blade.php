@extends('partial.main')
@section('custom_styles')
<style>
    .border {
        border: 1px solid transparent; /* Set border dulu ke transparan */
        border-image: linear-gradient(to right, rgba(128,128,128,0.5), transparent); /* Gunakan linear gradient untuk border dengan gradasi */
        border-image-slice: 1; /* Memastikan border image mencakup seluruh border */
    }
</style>
@endsection

@section('content')
<div class="page-heading">
    <h4>{{$title}}</h4>
</div>
<div class="card">
    <div class="card-body">
    <form action="{{route('post-batal-muat')}}" method="post">
            @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">Select Container</label>
                        <select name="container_key[]" id="" class="form-select choices multiple-remove" data-placeholder="Pilih Container!!" multiple="multiple">
                            @foreach($item as $cont)
                            <option value="{{$cont->container_key}}">{{$cont->container_no}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Alasan Batal Muat</label>
                        <textarea class="form-control" name="alasan_batal_muat" id="exampleFormControlTextarea1" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal"> <i class="bx bx-x d-block d-sm-none"></i><span class="d-none d-sm-block">Close</span></button>
                    <button type="submit" class="btn btn-success ml-1 update_status"><i class="bx bx-check d-block d-sm-none"></i><span class="d-none d-sm-block">Confirm</span></button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection