@extends ('partial.invoice.main')


@section('content')

<div class="page-heading">
  <h3><?= $title ?></h3>
  <!-- <p>Input </p> -->

</div>
<div class="page-content">
    <div class="card">
        <div class="card-header">
            <form id="updateMain" action="{{route('invoice-master-osUpdate')}}" method="post">
                @csrf <!-- Include CSRF token for security if not already included -->
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" name="name" value="{{ $orderService->name }}">
                            <input type="hidden" class="form-control" name="id" value="{{ $orderService->id }}">
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="form-group">
                            <label for="ie">Import/Export</label>
                            <select name="ie" class="form-select js-example-basic-single">
                                <option value="I" {{ $orderService->ie == 'I' ? 'selected' : '' }}>Import</option>
                                <option value="E" {{ $orderService->ie == 'E' ? 'selected' : '' }}>Export</option>
                                <option value="X" {{ $orderService->ie == 'X' ? 'selected' : '' }}>Extend</option>
                                <option value="P" {{ $orderService->ie == 'P' ? 'selected' : '' }}>Plugging</option>
                                <option value="R" {{ $orderService->ie == 'R' ? 'selected' : '' }}>Others</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="form-group">
                            <label for="ie">Order</label>
                            <select name="order" class="form-select js-example-basic-multiple">
                                <option value="SP2" {{ $orderService->order == 'SP2' ? 'selected' : '' }}>SP2</option>
                                <option value="SPPS" {{ $orderService->order == 'SPPS' ? 'selected' : '' }}>SPPS</option>
                                <option value="SPPSD" {{ $orderService->order == 'SPPSD' ? 'selected' : '' }}>SPPS (Stuffing Dalam)</option>
                                <option value="MTI" {{ $orderService->order == 'MTI' ? 'selected' : '' }}>MT Kapal Icon</option>
                                <option value="MTK" {{ $orderService->order == 'MTK' ? 'selected' : '' }}>MT Kapal Luar</option>
                                <option value="P" {{ $orderService->order == 'P' ? 'selected' : '' }}>Palka</option>
                                <option value="N" {{ $orderService->order == 'N' ? 'selected' : '' }}>None</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col sm-3">
                        <div class="form-group">
                            <label for="ie">Return</label>
                            <select name="return_yn" class="form-select js-example-basic-multiple">
                                <option value="Y" {{ $orderService->return_yn == 'Y' ? 'selected' : '' }}>Yes</option>
                                <option value="N" {{ $orderService->return_yn == 'N' ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                    </div>
                    <div class="col sm-3">
                        <div class="form-group">
                            <label for="ie">Depo Return</label>
                            <select name="depo_return" class="form-select js-example-basic-multiple">
                                <option value="N" {{ $orderService->depo_return == 'N' ? 'selected' : '' }}>No Return</option>
                                <option value="IKS" {{ $orderService->depo_return == 'IKS' ? 'selected' : '' }}>IKS</option>
                                <option value="MKB" {{ $orderService->depo_return == 'MKB' ? 'selected' : '' }}>MKB</option>
                            </select>
                        </div>
                    </div>
                    <div class="col sm-3">
                        <div class="col-2">
                            <div class="form-group">
                                <label for="action">Action</label>
                                <div>
                                    <button class="btn btn-outline-success" id="updateButton" type="button">Update</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-body">
            <div class="row">
                @if($orderService->ie == 'X')
                <div class="col-12">
                    <div class="card" style="border: 1px solid #ccc; border-radius: 8px; padding: 20px;">
                        <div class="card-header">
                            <h4>Extend</h4>
                            <input type="hidden" name="type" value="XTD">
                            <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns" id="table1">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Kode</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orderServiceXTD as $detail)
                                    <tr>
                                        <td>{{ $detail->master_item_name }}</td>
                                        <td>{{ $detail->kode }}</td>
                                        <td>
                                            <form class="deleteForm" action="/invoice/master/osDetailBuang={{$detail->id}}" method="post">
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="id" value="{{ $detail->id }}">
                                                <button class="btn btn-outline-danger delete-btn" type="button"  onclick="confirmDelete(event)">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <form id="formDSK" action="{{ route('invoice-master-osDetailDSK')}}" method="post">
                            @csrf
                            <div class="card-body">
                                <input type="hidden" class="form-control" name="os_name" value="{{ $orderService->name }}">
                                <input type="hidden" class="form-control" name="os_id" value="{{ $orderService->id }}">
                                <input type="hidden" name="type" value="XTD">
                                <select name="master_item_id[]"  class="js-example-basic-multiple form-control" style="height: 350px;" multiple="multiple">
                                  @foreach($items as $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                  @endforeach
                                </select>
                            </div>
                            <div class="card-footer">
                                <button class="btn btn-outline-success" id="updateDSK" type="button">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
                @else
                <div class="col-6">
                    <div class="card" style="border: 1px solid #ccc; border-radius: 8px; padding: 20px;">
                        <div class="card-header">
                            @if($orderService->ie == 'I')
                            <h4>DSK</h4>
                            <input type="hidden" name="type" value="DSK">
                            <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns" id="table1">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Kode</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orderServiceDSK as $detail)
                                    <tr>
                                        <td>{{ $detail->master_item_name }}</td>
                                        <td>{{ $detail->kode }}</td>
                                        <td>
                                            <form class="deleteForm" action="/invoice/master/osDetailBuang={{$detail->id}}" method="post">
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="id" value="{{ $detail->id }}">
                                                <button class="btn btn-outline-danger delete-btn" type="button"  onclick="confirmDelete(event)">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @else
                            <h4>OSK</h4>
                            <input type="hidden" name="type" value="OSK">
                            <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns" id="table1">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Kode</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orderServiceOSK as $detail)
                                    <tr>
                                        <td>{{$detail->master_item_name}}</td>
                                        <td>{{$detail->kode}}</td>
                                        <td>
                                            <form class="deleteForm" action="/invoice/master/osDetailBuang={{$detail->id}}" method="post">
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="id" value="{{ $detail->id }}">
                                                <button class="btn btn-outline-danger delete-btn" type="button"  onclick="confirmDelete(event)">Delete</button>
                                            </form>
                                        </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @endif
                        </div>
                        <form id="formDSK" action="{{ route('invoice-master-osDetailDSK')}}" method="post">
                        @csrf
                        <div class="card-body">
                            <input type="hidden" class="form-control" name="os_name" value="{{ $orderService->name }}">
                            <input type="hidden" class="form-control" name="os_id" value="{{ $orderService->id }}">
                            @if($orderService->ie == 'I')
                            <input type="hidden" name="type" value="DSK">
                            @else
                            <input type="hidden" name="type" value="OSK">
                            @endif
                            <select name="master_item_id[]"  class="js-example-basic-multiple form-control" style="height: 350px;" multiple="multiple">
                              @foreach($items as $item)
                                <option value="{{$item->id}}">{{$item->name}}</option>
                              @endforeach
                            </select>
                        </div>
                        <div class="card-footer">
                            <button class="btn btn-outline-success" id="updateDSK" type="button">Update</button>
                        </div>
                        </form>
                    </div>
                </div>
                <!-- DS/OS -->
                <div class="col-6">
                    <div class="card" style="border: 1px solid #ccc; border-radius: 8px; padding: 20px;">
                        <div class="card-header">
                            @if($orderService->ie == 'I')
                            <h4>DS</h4>
                            <input type="hidden" name="type" value="DS">
                            <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns" id="table2">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Kode</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orderServiceDS as $detail)
                                    <tr>
                                        <td>{{ $detail->master_item_name }}</td>
                                        <td>{{ $detail->kode }}</td>
                                        <td>
                                            <form class="deleteForm" action="/invoice/master/osDetailBuang={{$detail->id}}" method="post">
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="id" value="{{ $detail->id }}">
                                                <button class="btn btn-outline-danger delete-btn" type="button"  onclick="confirmDelete(event)">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @else
                            <h4>OS</h4>
                            <input type="hidden" name="type" value="OS">
                            <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns" id="table2">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Kode</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orderServiceOS as $detail)
                                    <tr>
                                        <td>{{$detail->master_item_name}}</td>
                                        <td>{{$detail->kode}}</td>
                                        <td>
                                            <form class="deleteForm" action="/invoice/master/osDetailBuang={{$detail->id}}" method="post">
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="id" value="{{ $detail->id }}">
                                                <button class="btn btn-outline-danger delete-btn" type="button"  onclick="confirmDelete(event)">Delete</button>
                                            </form>
                                        </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @endif
                        </div>
                        <form id="formDS" action="{{ route('invoice-master-osDetailDS')}}" method="post">
                        @csrf
                        <div class="card-body">
                            <input type="hidden" class="form-control" name="os_name" value="{{ $orderService->name }}">
                            <input type="hidden" class="form-control" name="os_id" value="{{ $orderService->id }}">
                            @if($orderService->ie == 'I')
                            <input type="hidden" name="type" value="DS">
                            @else
                            <input type="hidden" name="type" value="OS">
                            @endif
                            <select name="master_item_id[]"  class="js-example-basic-multiple form-control" style="height: 350px;" multiple="multiple">
                              @foreach($items as $item)
                                <option value="{{$item->id}}">{{$item->name}}</option>
                              @endforeach
                            </select>
                        </div>
                        <div class="card-footer">
                            <button class="btn btn-outline-success" id="updateDS" type="button">Update</button>
                        </div>
                        </form>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection

@section('custom_js')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Attach event listener to the update button
        document.getElementById('updateButton').addEventListener('click', function (e) {
            e.preventDefault(); // Prevent the default form submission

            // Show SweetAlert confirmation dialog
            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to update this record?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, update it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit the form programmatically if confirmed
                    document.getElementById('updateMain').submit();
                }
            });
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Attach event listener to the update button
        document.getElementById('updateDSK').addEventListener('click', function (e) {
            e.preventDefault(); // Prevent the default form submission

            // Show SweetAlert confirmation dialog
            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to update this record?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, update it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit the form programmatically if confirmed
                    document.getElementById('formDSK').submit();
                }
            });
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Attach event listener to the update button
        document.getElementById('updateDS').addEventListener('click', function (e) {
            e.preventDefault(); // Prevent the default form submission

            // Show SweetAlert confirmation dialog
            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to update this record?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, update it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit the form programmatically if confirmed
                    document.getElementById('formDS').submit();
                }
            });
        });
    });
</script>

<script>
    function confirmDelete(event) {
        event.preventDefault();
        Swal.fire({
            title: 'Confirmation',
            text: 'Are you sure you want to delete this item?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Delete',
            cancelButtonText: 'Cancel',
        }).then((result) => {
            if (result.isConfirmed) {
                event.target.closest('.deleteForm').submit();
            }
        });
    }
</script>
@endsection