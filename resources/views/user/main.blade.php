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
  width: 200px; /* Sesuaikan dengan lebar yang diinginkan */
  height: 200px; /* Sesuaikan dengan tinggi yang diinginkan */
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

<section class="section">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Profile</h5>
                    </div>
                    <div class="card-body">
                        
                    <form id="updateProfileForm" action="/update_profile_photo" method="post" enctype="multipart/form-data">
                        @csrf    
                            <div class="row gallery" id="update-userrs">
                                
                            <div class="col-6 col-sm-6 col-lg-3 mt-2 mt-md-0 mb-md-0 mb-2">
                                <a href="#">
                                    <div class="round-image" id="uploaded_view" data-bs-toggle="modal" data-bs-target="#galleryModal">
                                    @if (Auth::user()->profil)
                                        <img class="w-100 active" src="{{ asset('profil/' .Auth::user()->profil) }}" data-bs-target="#Gallerycarousel" data-bs-slide-to="0">
                                    @else
                                        <img class="w-100 active" src="{{ asset('dist/assets/images/faces/1.jpg') }}" data-bs-target="#Gallerycarousel" data-bs-slide-to="0">
                                    @endif
                                    </div>
                                    <div class="button_outer">
                                        <div class="btn_upload">
                                            <input type="file" id="upload_file" name="profil">
                                            Upload Image
                                        </div>
                                        <div class="processing_bar"></div>
                                        <div class="success_box"></div>
                                    </div>
                                </a>
                            </div>

                                

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="hidden" id="user_id" class="form-control" placeholder="{{ Auth::user()->id }}">
                                        <label for="basicInput">Nama</label>
                                        <input type="text" class="form-control" id="basicInput" name="name" value="{{ Auth::user()->name }}" placeholder="Enter email" >
                                    </div>

                                    <div class="form-group">
                                        <label for="helpInputTop">Email</label>

                                        <input type="text" class="form-control" id="helpInputTop" name="email" value="{{ Auth::user()->email }}" >
                                    </div>

                                    <div class="form-group">
                                        <label for="helperText">Password</label>
                                        <input type="password" id="helperText" class="form-control" name="password" placeholder="Wajib Di Isi Kembali"  required>

                                    </div>
                                    <button type="submit" class="btn btn-success update_profil">Change</button>
                                 </div>
                              
                            </div>
                        
                        </form>
                            
                        
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="galleryModal" tabindex="-1" aria-labelledby="galleryModalTitle" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="galleryModalTitle">Add Photo</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <div class="modal-body" id="body224">
            @if (Auth::user()->profil)
                 <img class="w-100 active" src="{{ asset('profil/' .Auth::user()->profil) }}" data-bs-target="#Gallerycarousel" data-bs-slide-to="0">
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

@endsection

@section('custom_js')
<script src="{{asset('dist/assets/extensions/filepond/filepond.js')}}"></script>
<script src="{{asset('dist/assets/extensions/toastify-js/src/toastify.js')}}"></script>
<script src="{{asset('dist/assets/js/pages/filepond.js')}}"></script>
<script src="{{ asset('vendor/components/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('select2/dist/js/select2.full.min.js') }}"></script>
<script src="{{asset('dist/assets/extensions/sweetalert2/sweetalert2.min.js')}}"></script>    
<script src="{{asset('dist/assets/js/pages/sweetalert2.js')}}"></script>

<script>
var btnUpload = $("#upload_file"),
    btnOuter = $(".button_outer");

btnUpload.on("change", function(e) {
    var ext = btnUpload.val().split('.').pop().toLowerCase();
    if ($.inArray(ext, ['gif', 'png', 'jpg', 'jpeg']) == -1) {
        $(".error_msg").text("Not an Image...");
    } else {
        $(".error_msg").text("");
        btnOuter.addClass("file_uploading");
        setTimeout(function() {
            btnOuter.addClass("file_uploaded");
        }, 3000);
        var uploadedFile = URL.createObjectURL(e.target.files[0]);
        setTimeout(function() {
            $("#uploaded_view").html('<img class="w-100 active" src="' + uploadedFile + '" data-bs-target="#Gallerycarousel" data-bs-slide-to="0">').addClass("show");
            $("#body224").html('<img class="w-100 active" src="' + uploadedFile + '" data-bs-target="#Gallerycarousel" data-bs-slide-to="0">').addClass("show");
            }, 3500);
        
    }
});

$(".file_remove").on("click", function(e) {
    $("#uploaded_view").removeClass("show");
    $("#uploaded_view").html("");
    btnOuter.removeClass("file_uploading");
    btnOuter.removeClass("file_uploaded");
});

$(document).ready(function() {
        // Tangkap event submit form
        $('#updateProfileForm').on('submit', function(e) {
            e.preventDefault(); // Mencegah aksi default submit form

            // Membuat objek FormData untuk mengumpulkan data form
            var formData = new FormData(this);

            // Mengirim permintaan AJAX ke endpoint '/update_profile_photo'
            Swal.fire({
        title: 'Are you Sure?',
        text: "Profile Will Be Updated",
        icon: 'warning',
        showDenyButton: false,
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Confirm',
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire('Saved!', '', 'success')
            
            $.ajax({
                url: '/update_profile_photo',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    // Tampilkan indikator proses atau pesan loading
                    // di sini Anda dapat menampilkan pesan loading atau ikon loading
                },
                success: function(response) {
                    // Tangani respon sukses dari server
                    console.log(response); // Tampilkan respon pada konsol
                    $('#update-userrs').load(window.location.href + ' #update-userrs', function(){
                        var btnUpload = $("#upload_file"),
                         btnOuter = $(".button_outer");

btnUpload.on("change", function(e) {
    var ext = btnUpload.val().split('.').pop().toLowerCase();
    if ($.inArray(ext, ['gif', 'png', 'jpg', 'jpeg']) == -1) {
        $(".error_msg").text("Not an Image...");
    } else {
        $(".error_msg").text("");
        btnOuter.addClass("file_uploading");
        setTimeout(function() {
            btnOuter.addClass("file_uploaded");
        }, 3000);
        var uploadedFile = URL.createObjectURL(e.target.files[0]);
        setTimeout(function() {
            $("#uploaded_view").html('<img class="w-100 active" src="' + uploadedFile + '" data-bs-target="#Gallerycarousel" data-bs-slide-to="0">').addClass("show");
            $("#body224").html('<img class="w-100 active" src="' + uploadedFile + '" data-bs-target="#Gallerycarousel" data-bs-slide-to="0">').addClass("show");
        }, 3500);
    }
});

$(".file_remove").on("click", function(e) {
    $("#uploaded_view").removeClass("show");
    $("#uploaded_view").html("");
    btnOuter.removeClass("file_uploading");
    btnOuter.removeClass("file_uploaded");
});
                    });
                    // Tampilkan pesan sukses atau lakukan aksi sesuai kebutuhan Anda
                    $('#body224').load(window.location.href + ' #body224');
                   
                },
                error: function(xhr, status, error) {
                    // Tangani kesalahan dalam permintaan AJAX
                    console.log(xhr.responseText); // Tampilkan pesan kesalahan pada konsol
                    alert('Something went wrong. Please try again.'); // Tampilkan pesan kesalahan pada pengguna
                },
                complete: function() {
                    // Aksi yang dilakukan setelah permintaan AJAX selesai,
                    // misalnya menghilangkan indikator proses atau menampilkan pesan selesai
                }
            });
        } else if (result.isDenied) {
            Swal.fire('Changes are not saved', '', 'info')                     
        }
    });
            
        });
    });

// $(document).on('click', '.update_profil', function(e) {
//     e.preventDefault();

//     var fileInput =  document.querySelector('input[id="foto"]');
//     var profil = fileInput.files[0];
//     var id = $('#user_id').val();

//     var formData = new FormData();
//     formData.append('profil', profil);
//     formData.append('user_id', id);

//     $.ajaxSetup({
//         headers: {
//             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//         }
//     });

//     Swal.fire({
//         title: 'Are you Sure?',
//         text: "Profile Will Be Updated",
//         icon: 'warning',
//         showDenyButton: false,
//         showCancelButton: true,
//         confirmButtonColor: '#3085d6',
//         confirmButtonText: 'Confirm',
//     }).then((result) => {
//         if (result.isConfirmed) {
//             Swal.fire('Saved!', '', 'success')
            
//             $.ajax({
//                 type: 'POST',
//                 url: '/update_profile_photo',
//                 data: formData,
//                 cache: false,
//                 processData: false,
//                 contentType: false,
//                 dataType: 'json',
//                 success: function(response) {
//                     console.log(response);
//                     if (response.success) {
//                         // Berhasil diunggah
//                     } else {
//                         Swal.fire('Error', response.message, 'error');
//                     }
//                 },
//                 error: function(xhr, status, error) {
//                     // Penanganan kesalahan jika pengunggahan gagal
//                 },
//             });
//         } else if (result.isDenied) {
//             Swal.fire('Changes are not saved', '', 'info')                     
//         }
//     });
// });

</script>

@endsection
