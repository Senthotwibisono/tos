@extends ('partial.invoice.main')


@section('content')

<div class="page-heading">
  <h3><?= $title ?></h3>
  <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>

</div>

<div class="page-content">
  <section class="row">
    <div class="col-12 text-center">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">
            <?= $title ?>
          </h3>
          <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-12">
              <div class="btn-group mb-3" role="group" aria-label="Basic example">
                <a href="/do/create" type="button" class="btn btn-success">
                  Create Do Online
                </a>
                <a href="#" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#addManual">Create Manual</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">
            Table DO Online
          </h3>
          <p>Lorem Ipsum</p>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-12">
              <div class="table responsive">
                <table class="table table-responsive table-hover" id="doTable">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Do No</th>
                      <th>BL No</th>
                      <th>Container No</th>
                      <th>Customer Code</th>
                      <th>Do Expired</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <!-- <tbody>
                   @foreach($doOnline as $do)
                   <tr>
                      <td>{{$loop->iteration}}</td>
                      <td>{{$do->do_no}}</td>
                      <td>{{$do->bl_no}}</td>
                      <td>
                           @php
                            $doArray = json_decode($do->container_no, true);
                              if (is_array($doArray)) {
                                  echo implode(', ', $doArray);
                              } else {
                                echo $do->container_no; // Atau berikan pesan default jika tidak valid
                              }
                          @endphp
                      </td>
                      <td>{{$do->expired}}</td>
                      <td>
                        <div class="row mb-3">
                          <div class="col-sm-6">
                            <a href="/edit/doOnline/{{$do->id}}" class="btn btn-warning">Edit</a>
                          </div>
                          <div class="col-sm-6">
                            <form id="deleteForm-{{ $do->id }}" action="{{ route('deleteDo') }}" method="post">
                                @csrf
                                <input type="hidden" name="id" value="{{ $do->id }}">
                                <button class="btn btn-outline-danger delete-btn" type="button" data-id="{{ $do->id }}">Delete</button>
                            </form>
                          </div>
                        </div>
                      </td>
                   </tr>
                   @endforeach
                  </tbody> -->
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<!-- Modal Pilih Kapal -->
<div class="modal fade" id="addManual" tabindex="-1" aria-labelledby="selectShipModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="selectShipModalLabel">Pilih Kapal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <label for="shipSelect">Pilih ID Kapal:</label>
                <select id="shipSelect" class="form-select customSelect select2" style="width: 100 %;">
                    <option value="" selected disabled>Pilih Kapal</option>
                    @foreach($vessels as $ves)  <!-- Pastikan variable ini dikirim dari controller -->
                        <option value="{{ $ves->ves_id }}">{{ $ves->ves_name }} || {{ $ves->voy_out }}</option>
                    @endforeach
                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="confirmShipSelection">Lanjutkan</button>
            </div>
        </div>
    </div>
</div>


@endsection
@section('custom_js')
<script>
    document.getElementById('confirmShipSelection').addEventListener('click', function () {
        let selectedShipId = document.getElementById('shipSelect').value;

        if (!selectedShipId) {
            Swal.fire({
                title: 'Pilih Kapal!',
                text: 'Harap pilih ID kapal terlebih dahulu sebelum melanjutkan.',
                icon: 'warning'
            });
            return;
        }

        // Redirect ke halaman dengan parameter ID kapal
        window.location.href = "/billing/do/createManual?id_kapal=" + selectedShipId;
    });
</script>
<script>
    $(document).ready(function() {
        $('#doTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '/billing/dock-DO/data', // Adjust this route to match your route definition
                type: 'GET'
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', className: 'text-center', orderable: false, searchable: false },
                { data: 'do_no', name: 'do_no', className: 'text-center' },
                { data: 'bl_no', name: 'bl_no', className: 'text-center' },
                { data: 'container_no', name: 'container_no', className: 'text-center' },
                { data: 'customer_code', name: 'customer_code', className: 'text-center' },
                { data: 'expired', name: 'expired', className: 'text-center' },
                { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-center' }
            ],
            pageLength: 30,
            order: [[1, 'asc']]
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Use jQuery to ensure the script loads after DataTables initializes
        $(document).on('click', '.delete-btn', function (e) {
            e.preventDefault();
            
            const id = $(this).data('id');
            const form = $('#deleteForm-' + id);
            
            Swal.fire({
                title: 'Are you sure?',
                text: "This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // Submit the form if confirmed
                }
            });
        });
    });
</script>
@endsection