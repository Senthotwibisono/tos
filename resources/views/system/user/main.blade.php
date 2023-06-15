@extends('partial.main')
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
  width: 100px; /* Sesuaikan dengan lebar yang diinginkan */
  height: 100px; /* Sesuaikan dengan tinggi yang diinginkan */
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

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Auth Table</h3>
                
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active" aria-current="page">DataTable</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="card">
            <div class="card-header">
               
                    <a href="/system/user/create_user" class="btn icon icon-left btn-success"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg> Add User</a>
             
            </div>
            <div class="card-body">
                <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns" id="table1">
                    <thead>
                        <tr>
                            <th>Photo</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td>
                                <div class="round-image" id="uploaded_view" data-bs-toggle="modal" data-bs-target="#galleryModal-{{ $user->id }}">
                                    @if ($user->profil)
                                        <img class="w-100 active" src="{{ asset('profil/' .$user->profil) }}" data-bs-target="#Gallerycarousel" data-bs-slide-to="0">
                                    @else
                                        <img class="w-100 active" src="{{ asset('dist/assets/images/faces/1.jpg') }}" data-bs-target="#Gallerycarousel" data-bs-slide-to="0">
                                    @endif
                                </div>
                            </td>
                            <td>{{$user->name}}</td>
                            <td>{{$user->email}}</td>
                            <td>{{$user->roles->implode('name', ', ')}}</td>
                            <td>
                            <form action="/system/delete_user={{$user->id}}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn icon btn-danger"> <i class="bi bi-x"></i></button>
                            <a href="/system/edit_user={{$user->id}}" class="btn icon btn-primary"><i class="bi bi-pencil"></i></a>
                            </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                
            </div>
        </div>

    </section>
</div>

@foreach($users as $user)
<div class="modal fade" id="galleryModal-{{ $user->id }}" tabindex="-1" aria-labelledby="galleryModalTitle" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="galleryModalTitle">User Photo</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <div class="modal-body" id="body224">
            @if ($user->profil)
                 <img class="w-100 active" src="{{ asset('profil/' .$user->profil) }}" data-bs-target="#Gallerycarousel" data-bs-slide-to="0">
             @else
                 <img class="w-100 active" src="{{ asset('dist/assets/images/faces/1.jpg') }}" data-bs-target="#Gallerycarousel" data-bs-slide-to="0">
             @endif
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endforeach

@endsection
