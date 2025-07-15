@extends ('partial.customer.main')
@section('custom_styles')
<style>
     .text-left {
      text-align: left;
    }
</style>
@endsection
@section('content')

<div class="page-heading">
  <h3><?= $title ?></h3>
  
</div>

<div class="page-content">
  <section class="row">
    <div class="col-12 text-center">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">
            <?= $title ?>
          </h3>
        </div>
        <form action="{{ route('doUpdate')}}" method="post"> 
            @csrf
            <div class="card-body">
              <div class="row mb-3">
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="">Do Number</label>
                        <input type="text" class="form-control" name="do_no" value="{{$do->do_no}}">
                        <input type="hidden" class="form-control" name="id" value="{{$do->id}}">
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="">BL Number</label>
                        <input type="text" class="form-control" name="bl_no" value="{{$do->bl_no}}">
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="">Expired</label>
                        <input type="date" class="form-control" name="expired" value="{{$do->expired}}">
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="">Customer Code</label>
                        <input type="text" class="form-control" name="customer_code" value="{{$do->customer_code}}">
                    </div>
                </div>
              </div>
              <hr>
              <div class="row">
                <div class="col-12">
                    <label for="">Container Number</label>
                    <textarea name="container_no" class="form-control" id="">
                        {{$do->container_no}}
                    </textarea>
                   <div class="row">
                        <div class="text-left">
                            <p><strong style="color: red;">Cara Pengisian Container :</strong><br>
                                1. Isi nomor kontainer di dalam tanda kurung [ ] <br>
                                2. Gunakan petik dua " "<br>
                                3. Jangan gunakan spasi <br>
                                4. Gunakan koma setalah kontainer sebelumnya <br>
                                5. contoh : ["CTRXXX","CTRYYY",....,"CTRZZZ"]</p>
                        </div>
                   </div>
                </div>
              </div>
            </div>
            <div class="card-footer">
                <div class="row mb-3">
                    <div class="col-sm-3">
                        <button type="submit" class="btn btn-success">Submit</button>
                        <a href="/customer-import/doOnline/index" class="btn btn-secondary">Back</a>
                    </div>
                </div>
            </div>
        </form>
      </div>
    </div>
  </section>
</div>

@endsection
@section('custom_js')

</script>
@endsection