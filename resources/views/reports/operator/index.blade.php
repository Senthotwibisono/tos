@extends('partial.main')
@section('custom_styles')
<link rel="stylesheet" href="{{asset('dist/assets/extensions/flatpickr/flatpickr.min.css')}}">
<style>
</style>
@endsection

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Operator Reports</h3>
                    <p class="text-subtitle text-muted"></p>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-3">
                    <p>Pilih Tanggal</p> 
                </div>
                <div class="col-3">
                    <p>Pilih Role</p> 
                </div>
                <div class="col-3">
                    <p>Pilih Operator</p> 
                </div>
            </div>
            <div class="row">
                <div class="col-3">
                    <input type="date" class="form-control flatpickr-range mb-3" id="time" placeholder="Select date..">
                </div>
                <div class="col-3">
                    <select class="choices form-control multiple-remove" data-placeholder="Pilih Role!!" multiple="multiple" id="kategori">
                            <option value="cc">Disch/Load</option>
                            <option value="yard">Yard (Placement Container)</option>
                            <option value="truck">Supir</option>
                    </select>
                </div>
                <div class="col-3">
                    <select class="form-control multiple-remove" multiple="multiple" id="alat">
                   
                    <option  value="all">All</option>
                    </select>
                </div>
                <div class="col-3">
                    <button class="btn icon btn-info search"><i class="bi bi-search"></i></button>
                </div>
            </div>
            
        </div>
        <div class="card-body">
            <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns" id="table1">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Name</th>
                    <th>Role</th>
                    <th>Alat</th>
                    <th>Container</th>
                    <th>Activity</th>
                    <th>Time</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach($actOP as $act)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$act->operator_name}}</td>
                        <td>{{$act->opRole->role}}</td>
                        <td>{{$act->alat_name}}</td>
                        <td>{{$act->container_no}}</td>
                        <td>{{$act->activity}}</td>
                        <td>{{$act->created_at}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection

@section('custom_js')
<script src="{{asset('dist/assets/extensions/flatpickr/flatpickr.min.js')}}"></script>
<script src="{{asset('dist/assets/js/pages/date-picker.js')}}"></script>

<script>
    $(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(function() {
        const selectCategory = new Choices(document.querySelector('#alat'), {
            // Opsi dan pengaturan Choices.js sesuai kebutuhan
        });
        $("#alat").addClass('form-control multiple-remove').attr('multiple', 'multiple');
        $("#kategori").change(function() {
            let name = $('#kategori').val();

            $.ajax({
                type: 'POST',
                url: '/get-op',
                data: {
                    name: name
                },
                cache: false,

                success: function(msg) {
                    let res = msg;
                    var len = res.length;
                    var choicesArray = []; // Array untuk menyimpan pilihan-pilihan baru
                    for (let i = 0; i < len; i++) {
                        let id = res[i].value;
                        let nama = res[i].text;
                        choicesArray.push({ value: id, label: nama }); // Tambahkan pilihan baru ke dalam array
                    }
                    selectCategory.clearChoices(); // Hapus pilihan-pilihan saat ini
                    selectCategory.setChoices(choicesArray, 'value', 'label', true); // Atur pilihan-pilihan baru
                },
                error: function(data) {
                    console.log('error:', data)
                },
            });
        });
    });
});
</script>

<script>
     $(document).on('click', '.search', function(e) {
        e.preventDefault();
        var created_at = $('#time').val();
        var id = $('#alat').val();
        var data = {
          'created_at' : $('#time').val(),
          'id': $('#alat').val(),
        }
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
        Swal.fire({
          title: 'Yakin Membuat Laporan?',
          text: "",
          icon: 'warning',
          showDenyButton: false,
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          confirmButtonText: 'Confirm',
        }).then((result) => {
          /* Read more about isConfirmed, isDenied below */
          if (result.isConfirmed) {

            $.ajax({
              type: 'POST',
              url: '/get-data-operator',
              data: data,
              cache: false,
              dataType: 'json',
              success: function(response) {
                console.log(response);
                if (response.success) {
                  Swal.fire('Saved!', '', 'success')
                  .then(() => {
                            
                    var url = '/laporan-operator?created_at=' + created_at + '&id=' + id;
    window.open(url, '_blank');
                        });
                } else {
                  Swal.fire('Error', response.message, 'error');
                }
              },
              error: function(response) {
                var errors = response.responseJSON.errors;
                if (errors) {
                  var errorMessage = '';
                  $.each(errors, function(key, value) {
                    errorMessage += value[0] + '<br>';
                  });
                  Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    html: errorMessage,
                  });
                } else {
                  console.log('error:', response);
                }
              },
            });

          } else if (result.isDenied) {
            Swal.fire('Changes are not saved', '', 'info')
          }


        })

      });
</script>

@endsection
