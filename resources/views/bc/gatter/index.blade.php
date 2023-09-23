@extends('partial.main')
<style>
    .border {
        border: 1px solid transparent; /* Set border dulu ke transparan */
        border-image: linear-gradient(to right, rgba(128,128,128,0.5), transparent); /* Gunakan linear gradient untuk border dengan gradasi */
        border-image-slice: 1; /* Memastikan border image mencakup seluruh border */
    }
</style>
@section('custom_styles')
@endsection

@section('content')
<div class="container">
    <div class="card">
                @include('bc.gatter.view.home')
    </div>
</div>
@endsection

@section('custom_js')
@endsection