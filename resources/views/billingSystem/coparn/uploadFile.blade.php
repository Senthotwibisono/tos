@extends ('partial.invoice.main')


@section('content')

<div class="page-heading">
  <h3><?= $title ?></h3>
  <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
</div>

<div class="page-content">

  <section class="row">
    <form action="" method="POST" enctype="multipart/form-data" id="uploadCoparnFile">
      @CSRF
      <div class="row">
        <div class="col-6">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Shipment Information</h3>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-6">
                  <div class="form-group">
                    <label>Vessel Name</label>
                    <select name="ves_id" id="vesselCoparn" class="js-example-basic-multiple form-control" style="height: 150%;">
                      <option value="" disabled selected>Pilih Salah Satu</option>
                      @foreach ($vessel as $data)
                        <option value="{{$data->ves_id}}">{{$data->ves_name}}--{{$data->voy_out}}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="col-6">
                  <div class="form-group">
                    <label>Voyage</label>
                    <input type="text" id="voyage" name="voyage" class="form-control" placeholder="Voyage.." readonly>
                  </div>
                </div>

                <div class="col-6">
                  <div class="form-group">
                    <label>Vessel Code</label>
                    <input type="text" id="vesselcode" name="vesselcode" class="form-control" placeholder="Vessel Code.." readonly>
                  </div>
                </div>
                <div class="col-6">
                  <div class="form-group">
                    <label>Vessel Name</label>
                    <input type="text" id="vesName" name="vesName" class="form-control" placeholder="Vessel ID.." readonly>
                  </div>
                </div>

                <div class="col-6">
                  <div class="form-group">
                    <label>Closing Time</label>
                    <input name="closingtime" id="closing" required type="date-time" class="form-control flatpickr-range mb-3" placeholder="09/05/2023" id="closingtime" readonly>
                  </div>
                </div>

                <div class="col-6">
                  <div class="form-group">
                    <label>Arival Date</label>
                    <input name="arrival" id="arrival" required type="date-time" class="form-control flatpickr-range mb-3" placeholder="09/05/2023" id="arrival" readonly>
                  </div>
                </div>

                <div class="col-6">
                  <div class="form-group">
                    <label>Departure Date</label>
                    <input name="departure" id="departure" required type="date-time" class="form-control flatpickr-range mb-3" placeholder="09/05/2023" id="departure" readonly>
                    <!-- <input type="hidden" name="ves_id" id="ves_id" required value=""> -->
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-6">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Upload Copart Document Online</h3>
              <p>Please Upload your file by drag n drop or click the area</p>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-12">
                  <input type="file" name="fileCoparn" id="storecoparn" class="dropify" data-height="270">
                </div>
              </div>
              <div class="row mt-3">
                <div class="col-2">
                  <button type="button" id="submitFile" class="btn btn-primary text-white">Submit</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </form>
  </section>

</div>

@endsection

@section('custom_js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script>
$(function(){
    $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
      $(function(){
        $('#vesselCoparn'). on('change', function(){
            showLoading();
              let id = $('#vesselCoparn').val();

              $.ajax({
                  type: 'get',
                  url: "{{ route('getVesselData')}}",
                  data : {id : id
                          },
                  cache: false,
                  
                  success: function(response) {
                      console.log(response);
                      if (response.success) {
                        hideLoading();
                          Swal.fire({
                              icon: 'success',
                              title: 'Saved!',
                              timer: 2000, // Waktu tampilan SweetAlert (ms)
                              showConfirmButton: false
                          }).then(() => {
                              $('#voyage').val(response.data.voy_out);
                              $('#vesselcode').val(response.data.ves_code);
                              $('#vesName').val(response.data.ves_name);
                              $('#closing').val(response.data.clossing_date);
                              $('#arrival').val(response.data.eta_date);
                              $('#departure').val(response.data.etd_date);
                              $('#containerSelector').empty();
                          });
                      } else {
                        hideLoading();
                          Swal.fire({
                              icon: 'error',
                              title: 'Error',
                              text: response.message
                          }).then(() => {
                              window.location.reload();
                          });
                      }
                  },
                  error: function(data) {
                      console.log('error:', data);
                      hideLoading();
                      Swal.fire({
                          icon: 'error',
                          title: 'Oops...',
                          text: 'Terjadi kesalahan saat memuat data. Silakan coba lagi nanti.',
                      });
                  }
              })
          })
      })
  });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Attach event listener to the update button
        document.getElementById('submitFile').addEventListener('click', function (e) {
            e.preventDefault(); // Prevent the default form submission

            // Show SweetAlert confirmation dialog
            Swal.fire({
                title: 'Are you sure?',
                text: "",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, update it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    showLoading();
                    postFileCoparn();
                }
            });
        });
    });
</script>

<script>
    async function readExcelFile(file, fileExt) {
        return new Promise((resolve, reject) => {
            const reader = new FileReader();
            reader.onload = function (e) {
                let data;
                try {
                    if (fileExt === 'csv') {
                        const text = e.target.result;
                        data = text.split('\n').map(row => row.split(','));
                    } else {
                        const binary = new Uint8Array(e.target.result);
                        const workbook = XLSX.read(binary, { type: 'array' });
                        const sheetName = workbook.SheetNames[0];
                        const worksheet = workbook.Sheets[sheetName];
                        data = XLSX.utils.sheet_to_json(worksheet, { header: 1 });
                    }

                    const cleanedData = data
                        .filter(row => row.length > 0)
                        .slice(1)
                        .map(row => row.map(cell => typeof cell === 'string' ? cell.trim() : cell));

                    resolve(cleanedData);
                } catch (err) {
                    reject(err);
                }
            };

            if (fileExt === 'csv') {
                reader.readAsText(file);
            } else {
                reader.readAsArrayBuffer(file);
            }
        });
    }

    async function postFileCoparn() {
        const ves_id = document.getElementById('vesselCoparn').value;
        const fileInput = document.getElementById('storecoparn');
        const file = fileInput.files[0];

        if (!file) {
            hideLoading();
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Silakan pilih file terlebih dahulu.', 
            });
            return;
        }
        if (!ves_id) {
            hideLoading();
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Silakan pilih Kapal terlebih dahulu.', 
            });
            return;
        }
        const fileName = file.name;
        const fileExt = fileName.split('.').pop().toLowerCase();
        console.log(fileExt);
        const allowedExtensions = ['xlsx', 'xls', 'csv'];
        console.log('Full filename:', file.name);
        console.log('Extension detected:', `"${fileExt}"`);

        if (!allowedExtensions.includes(fileExt)) {
          hideLoading();
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Format file tidak didukung. Gunakan .xls, .xlsx, atau .csv', 
            });
            return;
        }
        let dataResult;
        try {
            dataResult = await readExcelFile(file, fileExt);
            console.log(dataResult);
        } catch (error) {
          hideLoading();
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: error, 
            });
            return;
        }
        const formData = {
            ves_id: ves_id,
            dataResult: dataResult
        };
        const response = await fetch('{{route('customer.coparn.storeFile')}}',{
            method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify(formData)
        })
        console.log(response);
        hideLoading();
        try {
            if (response.ok) {
                var result = await response.json();
                if (result.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Suksesss!',
                        text: result.message,
                    }).then (() => {
                        location.href = '/billing/coparn';
                    });
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Something wrong in : ',
                        text: result.message,
                    });
                }
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.statusText, 
                });
            }
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: error, 
            });
        }
    }
</script>
@endsection