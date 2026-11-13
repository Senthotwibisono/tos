@extends(Auth::user()->hasRole('customer') ? 'partial.customer.main' : 'partial.invoice.main')
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
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">
            <?= $title ?>
          </h3>
        </div>
        <form action="/billing/do/postManual" method="post" enctype="multipart/form-data" id="updateForm"> 
            @csrf
            <div class="card-body">
              <div class="row mb-3">
                  <div class="col-sm-12">
                      <div class="form-group">
                          <label for="do_pdf">Upload Delivery Order (PDF)</label>
                          <input
                              type="file"
                              class="form-control"
                              id="do_pdf"
                              name="do_pdf"
                              accept=".pdf,application/pdf"
                          >
                          <small class="text-muted">
                              Pilih PDF DO untuk mengisi nomor DO, BL, expired,
                              dan container secara otomatis.
                          </small>
                      </div>
                  </div>
              </div>
              <div class="row mb-3">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="">Do Number</label>
                        <input type="text" class="form-control" name="do_no" required>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="">BL Number</label>
                        <input type="text" class="form-control" name="bl_no" required>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="">Expired</label>
                        <input type="date" class="form-control" name="expired" required>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="">Customer</label>
                        <select name="customer" id="" class="js-example-basic-single select2 form-select" style="width: 100%;">
                            <option disabled selected value>Pilih Satu!</option>
                            @foreach($customers as $customer)
                                <option value="{{$customer->code}}">{{$customer->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="">Container</label>
                        <select name="container[]" id="container" class="js-example-basic-multiple select2 form-select" multiple="multiple" style="width: 100%; height: 250%;">
                            @foreach($items as $item)
                                <option value="{{$item->container_no}}">{{$item->container_no}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
              </div>
              <hr>
            </div>
            <div class="card-footer">
                <div class="row mb-3">
                    <div class="col-sm-3">
                        <button type="button" id="updateButton" class="btn btn-success">Submit</button>
                        @if(Auth::user()->hasRole('customer'))
                        <a href="/customer-import/doOnline/index" class="btn btn-secondary">Back</a>
                        @else
                        <a href="/billing/dock-DO" class="btn btn-secondary">Back</a>
                        @endif
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Attach event listener to the update button
        document.getElementById('updateButton').addEventListener('click', function (e) {
            e.preventDefault(); // Prevent the default form submission

            let doNo = document.querySelector('[name="do_no"]').value.trim();
            let blNo = document.querySelector('[name="bl_no"]').value.trim();
            let expired = document.querySelector('[name="expired"]').value.trim();
            let customer = document.querySelector('[name="customer"]').value;
            let container = document.querySelector('[name="container[]"]'); // Select multiple container

            let errors = [];

            // Cek jika input kosong
            if (doNo === '') errors.push('DO Number');
            if (blNo === '') errors.push('BL Number');
            if (expired === '') errors.push('Expired Date');
            if (customer === '' || customer === 'Pilih Satu!') errors.push('Customer');
            if (!container.selectedOptions.length) errors.push('Container');

            // Jika ada field yang kosong, tampilkan peringatan
            if (errors.length > 0) {
                Swal.fire({
                    title: 'Incomplete Data!',
                    text: `Please fill out: ${errors.join(', ')}`,
                    icon: 'error',
                });
                return;
            }

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
                    // Submit the form programmatically if confirmed
                        Swal.fire({
                        title: 'Processing...',
                        text: 'Please wait while we update the container',
                        icon: 'info',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                            willOpen: () => {
                                Swal.showLoading();
                            }
                        });
                    document.getElementById('updateForm').submit();
                }
            });
        });
    });
</script>


<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('updateForm');
    const upload = document.getElementById('do_pdf');
    const submitButton = document.getElementById('updateButton');
    const containerSelect = document.getElementById('container');

    // Baca PDF dan isi form secara otomatis.
    upload.addEventListener('change', async function () {
        const file = this.files[0];

        if (!file) return;

        if (file.type !== 'application/pdf') {
            Swal.fire('File tidak valid', 'Pilih file PDF.', 'error');
            this.value = '';
            return;
        }

        const formData = new FormData();
        formData.append('do_pdf', file);

        Swal.fire({
            title: 'Membaca PDF...',
            text: 'Mohon tunggu.',
            allowOutsideClick: false,
            showConfirmButton: false,
            didOpen: () => Swal.showLoading()
        });

        try {
            const response = await fetch(
                @json(route('billing.do.parse-pdf')),
                {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': @json(csrf_token()),
                        'Accept': 'application/json'
                    },
                    body: formData
                }
            );

            const data = await response.json();

            if (!response.ok) {
                throw new Error(
                    data.message || 'PDF gagal diproses.'
                );
            }

            // Isi field utama.
            form.querySelector('[name="do_no"]').value =
                data.do_no || '';

            form.querySelector('[name="bl_no"]').value =
                data.bl_no || '';

            if (data.expired) {
                form.querySelector('[name="expired"]').value =
                    data.expired;
            }

            // Tambahkan container yang belum ada.
            // Container yang ada di PDF akan dipilih semua.
            const containers = data.containers || [];
            const selectedValues = [];

            containers.forEach(function (containerNo) {
                containerNo = containerNo.trim().toUpperCase();

                if (!containerNo) return;

                let option = Array.from(
                    containerSelect.options
                ).find(function (item) {
                    return item.value.toUpperCase() === containerNo;
                });

                if (!option) {
                    option = new Option(
                        containerNo,
                        containerNo,
                        false,
                        false
                    );

                    containerSelect.add(option);
                }

                selectedValues.push(containerNo);
            });

            // Pilih semua container dari PDF.
            Array.from(containerSelect.options).forEach(
                function (option) {
                    option.selected = selectedValues.includes(
                        option.value.toUpperCase()
                    );
                }
            );

            // Perbarui tampilan Select2.
            if (window.jQuery) {
                window.jQuery(containerSelect).trigger('change');
            } else {
                containerSelect.dispatchEvent(
                    new Event('change', { bubbles: true })
                );
            }

            Swal.fire({
                title: 'PDF berhasil dibaca',
                html:
                    '<p>DO: <b>' + escapeHtml(data.do_no) + '</b></p>' +
                    '<p>BL: <b>' + escapeHtml(data.bl_no) + '</b></p>' +
                    '<p>Container ditemukan: <b>' +
                    containers.length + '</b></p>' +
                    (!data.expired
                        ? '<p class="text-danger">Tanggal Batas Storage tidak ditemukan. Isi expired secara manual.</p>'
                        : ''),
                icon: data.expired ? 'success' : 'warning'
            });

        } catch (error) {
            Swal.fire(
                'Gagal membaca PDF',
                error.message || 'Terjadi kesalahan.',
                'error'
            );
        }
    });

    // Escape teks sebelum dimasukkan ke HTML SweetAlert.
    function escapeHtml(value) {
        return String(value ?? '').replace(/[&<>"']/g, function (char) {
            return {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            }[char];
        });
    }

    // Validasi dan konfirmasi sebelum mengirim form.
    submitButton.addEventListener('click', function (e) {
        e.preventDefault();

        if (!form.reportValidity()) return;

        const errors = [];
        const doNo = form.querySelector('[name="do_no"]').value.trim();
        const blNo = form.querySelector('[name="bl_no"]').value.trim();
        const expired = form.querySelector('[name="expired"]').value;
        const customer = form.querySelector('[name="customer"]').value;
        const containers = Array.from(containerSelect.selectedOptions);

        if (!doNo) errors.push('DO Number');
        if (!blNo) errors.push('BL Number');
        if (!expired) errors.push('Expired Date');
        if (!customer) errors.push('Customer');
        if (!containers.length) errors.push('Container');

        if (errors.length) {
            Swal.fire({
                title: 'Data belum lengkap!',
                text: 'Mohon lengkapi: ' + errors.join(', '),
                icon: 'error'
            });
            return;
        }

        Swal.fire({
            title: 'Apakah kamu yakin?',
            text: 'Data DO akan dikirim untuk diproses.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, submit!',
            cancelButtonText: 'Batal'
        }).then(function (result) {
            if (!result.isConfirmed) return;

            submitButton.disabled = true;

            Swal.fire({
                title: 'Memproses...',
                text: 'Mohon tunggu.',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => Swal.showLoading()
            });

            // Tetap mengirim ke endpoint form yang lama.
            form.submit();
        });
    });
});
</script>
@endsection