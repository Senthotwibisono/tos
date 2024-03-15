@extends('partial.main')
<style>
    .box-container {
        display: flex !important;
        flex-wrap: wrap !important;
        gap: 10px !important;
        justify-content: flex-start !important;
    }

    .box-card {
        flex: 0 0 calc(5% - 10px) !important;
        box-sizing: border-box !important;
        display: flex !important;
        flex-direction: column-reverse !important;
        align-items: flex-end !important;
        margin-bottom: 10px !important;
        /* Add margin to create a gap */
    }

    .bay1-container {
        width: 100% !important;
        display: flex !important;
        justify-content: flex-start !important;
        margin-bottom: 10px !important;
        /* Add margin to create a gap */
    }

    .bay1 {
        margin: 0 0 10px 0 !important;
        text-align: center;
        /* Center the text */
        width: 100%;
        /* Adjust width to fill the container */
    }

    .card {
        width: 100% !important;
        border: 1px solid #ccc !important;
        margin: 0 0 10px 0 !important;
        box-sizing: border-box !important;
    }

    .card-body {
        padding: 5px !important;
    }

    .card-title {
        font-size: 16px !important;
    }

    .card-text {
        font-size: 10px !important;
        text-align: center !important;
    }

    .separator-line {
        border-top: 2px solid #ccc !important;
        margin: 20px 0 !important;
    }
</style>

@section('content')
<title>Ship Profile</title>
<div class="container">
    <h1>Ship Bay Profile</h1>
    <h3>Vessel Code: <?= $_GET["ves_code"] ?></h3>
    <h3>Vessel Name: <?= $_GET["ves_name"] ?></h3>
    <div class="row">
        <div class="col-3">
            <a class="btn btn-success mb-3" id="create" type="button"><i class="fa fa-plus"></i> Tambah</a>
            <a href="/profile-kapal/cetak-<?= $_GET["ves_code"] ?>" target="_blank" class="btn btn-info mb-3">Cetak Profile Kapal </a>
        </div>
    </div>
    <div class="box-container">
        @php
            $uniqueBay1 = $gridBoxData->unique('BAY1');
        @endphp
        @foreach ($uniqueBay1 as $bay1Data)
        <div class="box-card">
                <div class="card-header">
                    <input type="hidden" id="vesCode" value="{{$ves_code}}">
                    <button class="btn btn-outline-info bayProfile" data-id="{{$bay1Data->BAY1}}"><i class="fa fa-eye"></i></button>
                    <br>
                    <br>
                    <button class="btn btn-outline-success EditbayProfile" data-bay="{{$bay1Data->BAY1}}" data-kapal="{{$ves_code}}"><i class="fa fa-pencil"></i></button>
                    <br>
                    <br>
                   <form id="deleteForm" action="/planning/delete/ves-bay" method="post">
                    @csrf
                        <input type="hidden" name="id" value="{{$bay1Data->id}}">
                        <button class="btn btn-outline-danger" id="deleteButton"><i class="fa fa-trash"></i></button>
                   </form>

                </div>
        </div>
        @endforeach
    </div>
    <div class="box-container">
        @php
        $uniqueBay1 = $gridBoxData->unique('BAY1');
        @endphp

        @foreach ($uniqueBay1 as $bay1Data)
        <div class="box-card">
                @for ($i = $bay1Data->START_TIER; $i <= $bay1Data->START_TIER + $bay1Data->TIER; $i++)
                    @if ($i % 2 == 0)
                    <div class="card">
                        <div class="card-body p-2">
                            <h3 class="card-text">{{ $i }}</h3>
                        </div>
                    </div>
                    @endif
                @endfor
                <div class="bay1-container">
                    <h3 class="bay1">{{ $bay1Data->BAY1 }}</h3>
                </div>
        </div>
        @endforeach
    </div>
    <hr class="separator-line">
    <legend>UNDER DECK</legend>
    <div class="box-container">
        @php
        $uniqueBay1 = $gridBoxData->unique('BAY1');
        @endphp

        @foreach ($uniqueBay1 as $bay1Data)
        <div class="box-card">
                @for ($i = $bay1Data->START_TIER_UNDER; $i <= $bay1Data->START_TIER_UNDER + $bay1Data->TIER_UNDER; $i++)
                    @if ($i % 2 == 0)
                    <div class="card">
                        <div class="card-body p-2">
                            <h3 class="card-text">{{ $i }}</h3>
                        </div>
                    </div>
                    @endif
                @endfor
                <div class="bay1-container">
                    <h3 class="bay1">{{ $bay1Data->BAY1 }}</h3>
                </div>
        </div>
        @endforeach
    </div>
</div>

<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Bay Profile
                </h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <div class="modal-body">
                <!-- Include the selectkapal view content here -->
                <!-- <h4>Ves. Name: <span id="vesName"></span><br> Ves. Code: <span id="vesCode"></span></h4> -->
                <form method="POST" action="/profile-kapal/stores" id="bayForm">
                    @csrf
                    <input type="hidden" value="<?= $_GET["ves_code"] ?>" name="ves_code">
                    <!-- Display Ves. Name and Ves. Code as labels -->
                    <div class="form-group">
                        <label for="bay_name">Bay Name (2 characters max):</label>
                        <input type="text" class="form-control" id="bay_name" name="bay_name" required maxlength="2">
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <fieldset>
                                <legend>Under Deck</legend>

                                <div class="form-group">
                                    <label for="start_tier_under">Start Tier:</label>
                                    <input type="number" class="form-control" id="start_tier_under" name="start_tier_under" required min="0">
                                </div>

                                <div class="form-group">
                                    <label for="start_row_under">Start Row:</label>
                                    <input type="number" class="form-control" id="start_row_under" name="start_row_under" required min="0">
                                </div>

                                <div class="form-group">
                                    <label for="max_row_under">Max Row:</label>
                                    <input type="number" class="form-control" id="max_row_under" name="max_row_under" required min="0" max="16">
                                </div>

                                <div class="form-group">
                                    <label for="max_tier_under">Max Tier:</label>
                                    <input type="number" class="form-control" id="max_tier_under" name="max_tier_under" required min="0" max="14">
                                </div>
                            </fieldset>
                        </div>

                        <div class="col-md-6">
                            <fieldset>
                                <legend>On Deck</legend>

                                <div class="form-group">
                                    <label for="start_tier">Start Tier:</label>
                                    <input type="number" class="form-control" id="start_tier" name="start_tier" required min="0" max="80">
                                </div>

                                <div class="form-group">
                                    <label for="start_row">Start Row:</label>
                                    <input type="number" class="form-control" id="start_row" name="start_row" required min="0" max="00">
                                </div>

                                <div class="form-group">
                                    <label for="max_row">Max Row:</label>
                                    <input type="number" class="form-control" id="max_row" name="max_row" required min="0" max="16">
                                </div>

                                <div class="form-group">
                                    <label for="max_tier">Max Tier:</label>
                                    <input type="number" class="form-control" id="max_tier" name="max_tier" required min="0" max="98">
                                    <input type="hidden" id="tier" name="tier" value="">
                                </div>
                                <!-- <input type="hidden" id="selectedVesCode" name="selected_ves_code" value=""> -->
                            </fieldset>
                        </div>
                    </div>
                    <button type="button" id="submitBtn" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- edit -->
<div class="modal fade" id="editBayModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Bay Profile
                </h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <div class="modal-body">
                <!-- Include the selectkapal view content here -->
                <!-- <h4>Ves. Name: <span id="vesName"></span><br> Ves. Code: <span id="vesCode"></span></h4> -->
                <form method="POST" action="/planning/update/ves-bay" id="editForm">
                    @csrf
                    <!-- Display Ves. Name and Ves. Code as labels -->
                    <div class="form-group">
                        <label for="bay_name">Bay Name (2 characters max):</label>
                        <input type="text" class="form-control" id="kapal_edit" name="ves_code" value="{{$ves_code}}" required maxlength="2">
                        <input type="hidden" class="form-control" id="bay_name_edit_old" name="bay_name_old" required maxlength="2">
                        <input type="text" class="form-control" id="bay_name_edit" name="bay_name" required maxlength="2">
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <fieldset>
                                <legend>Under Deck</legend>

                                <div class="form-group">
                                    <label for="start_tier_under">Start Tier:</label>
                                    <input type="number" class="form-control" id="start_tier_under_edit" name="start_tier_under" required min="0">
                                </div>

                                <div class="form-group">
                                    <label for="start_row_under">Start Row:</label>
                                    <input type="number" class="form-control" id="start_row_under_edit" name="start_row_under" required min="0">
                                </div>

                                <div class="form-group">
                                    <label for="max_row_under">Max Row:</label>
                                    <input type="number" class="form-control" id="max_row_under_edit" name="max_row_under" required min="0" max="16">
                                </div>

                                <div class="form-group">
                                    <label for="max_tier_under">Max Tier:</label>
                                    <input type="number" class="form-control" id="max_tier_under_edit" name="max_tier_under" required min="0" max="14">
                                </div>
                            </fieldset>
                        </div>

                        <div class="col-md-6">
                            <fieldset>
                                <legend>On Deck</legend>

                                <div class="form-group">
                                    <label for="start_tier">Start Tier:</label>
                                    <input type="number" class="form-control" id="start_tier_edit" name="start_tier" required min="0" max="80">
                                </div>

                                <div class="form-group">
                                    <label for="start_row">Start Row:</label>
                                    <input type="number" class="form-control" id="start_row_edit" name="start_row" required min="0" max="00">
                                </div>

                                <div class="form-group">
                                    <label for="max_row">Max Row:</label>
                                    <input type="number" class="form-control" id="max_row_edit" name="max_row" required min="0" max="16">
                                </div>

                                <div class="form-group">
                                    <label for="max_tier">Max Tier:</label>
                                    <input type="number" class="form-control" id="max_tier_edit" name="max_tier" required min="0" max="98">
                                </div>
                            </fieldset>
                        </div>
                    </div>
                    <button type="button" id="updateBtn" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade text-left w-100" id="bayModal" tabindex="-1" role="dialog" data-bs-backdrop="false" aria-labelledby="myModalLabel20" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-full" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel20">Container Planning</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <div class="modal-body">
                <!-- Display the selected bay1 data -->
                <p id="selectedBayData">Bay1 Data: <span id="selectedBay1"></span></p>
                <!-- Hidden input field to store bay1 data -->
                <input type="hidden" id="hiddenBay1" value="">
            </div>
            <div class="box-container">
                @php
                $uniqueBay1 = $gridBoxData->unique('BAY1');
                @endphp

                @foreach ($uniqueBay1 as $bay1Data)
                <div class="box-card">
                    @for ($i = $bay1Data->START_TIER; $i <= $bay1Data->START_TIER + $bay1Data->TIER; $i++)
                        @if ($i % 2 == 0)
                        <div class="card">
                            <div class="card-body p-2">
                                <h3 class="card-text">{{ $i }}</h3>
                            </div>
                        </div>
                        @endif
                        @endfor
                        <div class="bay1-container">
                            <h3 class="bay1">{{ $bay1Data->BAY1 }}</h3>
                        </div>
                </div>
                @endforeach
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Close</span>
                </button>
                <button type="button" class="btn btn-primary ml-1" data-bs-dismiss="modal">
                    <i class="bx bx-check d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Accept</span>
                </button>
            </div>
        </div>
    </div>
</div>

@endsection
@section('custom_js')
<script>
    $('#create').click(function() {
        // Show the first modal
        $("#createModal").modal("show");
    });

    // Attach click event listener to bay1-container class
    $('.bay1-container').click(function() {
        // Get the bay1 value associated with the clicked grid box
        var bay1 = $(this).find('.bay1').text();

        // Set the hidden input field value to the clicked bay1 value
        $('#hiddenBay1').val(bay1);

        // Show the second modal
        $("#bayModal").modal("show");

        // Set the text of selectedBay1 span with the value of bay1
        $('#selectedBay1').text(bay1);
    });
    $('#submitBtn').click(function() {
        // Get the values of all form fields
        var bayName = $('#bay_name').val();
        var startTierUnder = $('#start_tier_under').val();
        var startRowUnder = $('#start_row_under').val();
        var maxRowUnder = $('#max_row_under').val();
        var maxTierUnder = $('#max_tier_under').val();
        var startTierOnDeck = $('#start_tier').val();
        var startRowOnDeck = $('#start_row').val();
        var maxRowOnDeck = $('#max_row').val();
        var maxTierOnDeck = $('#max_tier').val();

        // Check if any of the fields are empty
        if (
            bayName.trim() === '' ||
            startTierUnder.trim() === '' ||
            startRowUnder.trim() === '' ||
            maxRowUnder.trim() === '' ||
            maxTierUnder.trim() === '' ||
            startTierOnDeck.trim() === '' ||
            startRowOnDeck.trim() === '' ||
            maxRowOnDeck.trim() === '' ||
            maxTierOnDeck.trim() === ''
        ) {
            // Display an error message
            Swal.fire({
                title: 'Validation Error',
                text: 'All form fields must be filled out.',
                icon: 'error',
                confirmButtonText: 'OK',
            });
            return; // Prevent form submission
        }

        // Parse input values as integers
        var startTierUnder = parseInt(startTierUnder);
        var maxTierUnder = parseInt(maxTierUnder);
        var maxRowUnder = parseInt(maxRowUnder);
        var startRowUnder = parseInt(startRowUnder);
        var startTierOnDeck = parseInt(startTierOnDeck);
        var maxTierOnDeck = parseInt(maxTierOnDeck);
        var maxRowOnDeck = parseInt(maxRowOnDeck);
        var startRowOnDeck = parseInt(startRowOnDeck);

        var isValid = true;
        var errorMessages = [];

        // Check max values for Under Deck
        if (maxTierUnder > 14 || maxRowUnder > 16) {
            isValid = false;
            errorMessages.push('Max Tier or Max Row for Under Deck exceeds the allowed limits (Max Tier: 14, Max Row: 16).');
        }

        // Check max values for On Deck
        if (maxTierOnDeck > 98 || maxRowOnDeck > 16) {
            isValid = false;
            errorMessages.push('Max Tier or Max Row for On Deck exceeds the allowed limits (Max Tier: 98, Max Row: 16).');
        }

        // Check start values for Under Deck
        if (startTierUnder > maxTierUnder || startRowUnder > maxRowUnder) {
            isValid = false;
            errorMessages.push('Start Tier or Start Row for Under Deck should not exceed the corresponding Max Tier or Max Row.');
        }

        // Check start values for On Deck
        if (startTierOnDeck > maxTierOnDeck || startRowOnDeck > maxRowOnDeck) {
            isValid = false;
            errorMessages.push('Start Tier or Start Row for On Deck should not exceed the corresponding Max Tier or Max Row.');
        }
        /*  if (startTierOnDeck < 80) {
             isValid = false;
             errorMessages.push('Start Tier On Deck Cannot Below 80');
         } */

        if (!isValid) {
            // If there are validation errors, display them in a single alert
            Swal.fire({
                title: 'Validation Error',
                html: errorMessages.join('<br>'),
                icon: 'error',
                confirmButtonText: 'OK',
            });
        } else {
            // Calculate the TIER based on the selected values
            var tierCount = maxTierOnDeck - startTierOnDeck + 1;
            var tierCountUnder = maxTierUnder - startTierUnder + 1;

            // Set the calculated TIER to the hidden input field
            $('#max_tier').val(tierCount);
            $('#max_tier_under').val(tierCountUnder);

            // Submit the form to update the database
            document.getElementById('bayForm').submit();
        }
    });
</script>

<script>
    $(document).ready(function () {
        $('.bayProfile').click(function () {
            let bay = $(this).data('id');
            let ves = $('#vesCode').val();

      

            // Now you can use startDate and endDate in your logic or navigation
            window.open("{{URL::to('/profile-kapal/get/bay-')}}"+ves+'-'+bay,"preview barcode","width=1500,height=1000,menubar=no,status=no,scrollbars=yes"); 

        });

    });
</script>

<script>
     $(function() {
   $.ajaxSetup({
      headers: {
         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
   });

    $(document).on('click', '.EditbayProfile', function() {
        let bay = $(this).data('bay');
        let kapal = $(this).data('kapal');
        $.ajax({
            type: 'GET',
            url: '/planning/edit/ves-bay',
            cache: false,
            data: {bay: bay, kapal: kapal},
            dataType: 'json',
            success: function(response) {
                console.log(response);
                $('#editBayModal').modal('show');
                $('#editBayModal #bay_name_edit_old').val(response.data.BAY1);
                $('#editBayModal #bay_name_edit').val(response.data.BAY1);
                // Ondeck
                $('#editBayModal #start_tier_edit').val(response.data.START_TIER);
                $('#editBayModal #max_tier_edit').val(response.data.TIER + response.data.START_TIER - 1);
                $('#editBayModal #start_row_edit').val(response.data.START_ROW);
                $('#editBayModal #max_row_edit').val(parseInt(response.data.START_ROW, 10) + parseInt(response.data.MAX_ROW, 10));

                // Under
                $('#editBayModal #start_tier_under_edit').val(response.data.START_TIER_UNDER);
                $('#editBayModal #max_tier_under_edit').val(response.data.TIER_UNDER + response.data.START_TIER_UNDER - 1);
                $('#editBayModal #start_row_under_edit').val(response.data.START_ROW_UNDER);
                $('#editBayModal #max_row_under_edit').val(parseInt(response.data.START_ROW_UNDER, 10) + parseInt(response.data.MAX_ROW_UNDER, 10));
                // $('#edit-opr #name').val(response.data.name);
                // $('#edit-opr #idOpr').val(response.data.id);


       
            },
            error: function(data) {
            console.log('error:', data);
         }
        });
    });
});

</script>

<script>
     $('#updateBtn').click(function() {
        // Get the values of all form fields
        var bayName = $('#bay_name_edit').val();
        var startTierUnder = $('#start_tier_under_edit').val();
        var startRowUnder = $('#start_row_under_edit').val();
        var maxRowUnder = $('#max_row_under_edit').val();
        var maxTierUnder = $('#max_tier_under_edit').val();
        var startTierOnDeck = $('#start_tier_edit').val();
        var startRowOnDeck = $('#start_row_edit').val();
        var maxRowOnDeck = $('#max_row_edit').val();
        var maxTierOnDeck = $('#max_tier_edit').val();

        // Check if any of the fields are empty
        if (
            bayName.trim() === '' ||
            startTierUnder.trim() === '' ||
            startRowUnder.trim() === '' ||
            maxRowUnder.trim() === '' ||
            maxTierUnder.trim() === '' ||
            startTierOnDeck.trim() === '' ||
            startRowOnDeck.trim() === '' ||
            maxRowOnDeck.trim() === '' ||
            maxTierOnDeck.trim() === ''
        ) {
            // Display an error message
            Swal.fire({
                title: 'Validation Error',
                text: 'All form fields must be filled out.',
                icon: 'error',
                confirmButtonText: 'OK',
            });
            return; // Prevent form submission
        }

        // Parse input values as integers
        var startTierUnder = parseInt(startTierUnder);
        var maxTierUnder = parseInt(maxTierUnder);
        var maxRowUnder = parseInt(maxRowUnder);
        var startRowUnder = parseInt(startRowUnder);
        var startTierOnDeck = parseInt(startTierOnDeck);
        var maxTierOnDeck = parseInt(maxTierOnDeck);
        var maxRowOnDeck = parseInt(maxRowOnDeck);
        var startRowOnDeck = parseInt(startRowOnDeck);

        var isValid = true;
        var errorMessages = [];

        // Check max values for Under Deck
        if (maxTierUnder > 14 || maxRowUnder > 16) {
            isValid = false;
            errorMessages.push('Max Tier or Max Row for Under Deck exceeds the allowed limits (Max Tier: 14, Max Row: 16).');
        }

        // Check max values for On Deck
        if (maxTierOnDeck > 98 || maxRowOnDeck > 16) {
            isValid = false;
            errorMessages.push('Max Tier or Max Row for On Deck exceeds the allowed limits (Max Tier: 98, Max Row: 16).');
        }

        // Check start values for Under Deck
        if (startTierUnder > maxTierUnder || startRowUnder > maxRowUnder) {
            isValid = false;
            errorMessages.push('Start Tier or Start Row for Under Deck should not exceed the corresponding Max Tier or Max Row.');
        }

        // Check start values for On Deck
        if (startTierOnDeck > maxTierOnDeck || startRowOnDeck > maxRowOnDeck) {
            isValid = false;
            errorMessages.push('Start Tier or Start Row for On Deck should not exceed the corresponding Max Tier or Max Row.');
        }
        /*  if (startTierOnDeck < 80) {
             isValid = false;
             errorMessages.push('Start Tier On Deck Cannot Below 80');
         } */

        if (!isValid) {
            // If there are validation errors, display them in a single alert
            Swal.fire({
                title: 'Validation Error',
                html: errorMessages.join('<br>'),
                icon: 'error',
                confirmButtonText: 'OK',
            });
        } else {
            // Calculate the TIER based on the selected values
            var tierCount = maxTierOnDeck - startTierOnDeck + 1;
            var tierCountUnder = maxTierUnder - startTierUnder + 1;

            // Set the calculated TIER to the hidden input field
            $('#max_tier_edit').val(tierCount);
            $('#max_tier_under_edit').val(tierCountUnder);

            // Submit the form to update the database
            document.getElementById('editForm').submit();
        }
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('deleteButton').addEventListener('click', function (event) {
            event.preventDefault(); // Prevent form submission

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Lanjutkan dengan mengirimkan formulir penghapusan
                    document.getElementById('deleteForm').submit();
                }
            });
        });
    });
</script>
@endsection