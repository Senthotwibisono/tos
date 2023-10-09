@extends('partial.main')

@section('content')
<div class="container">
    <h1>Grid View</h1>
    <h3>Vessel Code : <?= $_GET["ves_code"] ?></h3>
    <h3>Vessel Name : <?= $_GET["ves_name"] ?></h3>
    <a class="btn btn-success mb-3" id="create" type="button"><i class="fa fa-plus"></i> Tambah</a>
    <table class="display table">
        <thead>
            <tr>
                <th>VES_CODE</th>
                <th>BAY1</th>
                <th>SIZE1</th>
                <th>BAY2</th>
                <th>SIZE2</th>
                <th>JOINSLOT</th>
                <th>WEIGHT_BALANCE_ON</th>
                <th>WEIGHT_BALANCE_UNDER</th>
                <th>START_ROW</th>
                <th>START_ROW_UNDER</th>
                <th>TIER</th>
                <th>TIER_UNDER</th>
                <th>MAX_ROW</th>
                <th>MAX_ROW_UNDER</th>
                <th>START_TIER</th>
                <th>START_TIER_UNDER</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($gridBoxData as $box)
            <tr>
                <td>{{ $box->VES_CODE }}</td>
                <td>{{ $box->BAY1 }}</td>
                <td>{{ $box->SIZE1 }}</td>
                <td>{{ $box->BAY2 }}</td>
                <td>{{ $box->SIZE2 }}</td>
                <td>{{ $box->JOINSLOT }}</td>
                <td>{{ $box->WEIGHT_BALANCE_ON }}</td>
                <td>{{ $box->WEIGHT_BALANCE_UNDER }}</td>
                <td>{{ $box->START_ROW }}</td>
                <td>{{ $box->START_ROW_UNDER }}</td>
                <td>{{ $box->TIER }}</td>
                <td>{{ $box->TIER_UNDER }}</td>
                <td>{{ $box->MAX_ROW }}</td>
                <td>{{ $box->MAX_ROW_UNDER }}</td>
                <td>{{ $box->START_TIER }}</td>
                <td>{{ $box->START_TIER_UNDER }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
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
                                </div>
                                <input type="hidden" id="selectedVesCode" name="selected_ves_code" value="">
                            </fieldset>
                        </div>
                    </div>

                    <!-- ... (other form fields) ... -->

                    <button type="button" id="submitBtn" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('custom_js')
<script>
    $('#create').click(function() {
        $("#createModal").modal("show");
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

            // Rest of your validation and form submission code
            var maxTierUnder = parseInt(maxTierUnder);
            var maxRowUnder = parseInt(maxRowUnder);
            var maxTierOnDeck = parseInt(maxTierOnDeck);
            var maxRowOnDeck = parseInt(maxRowOnDeck);
            var startTierUnder = parseInt(startTierUnder);
            var startRowUnder = parseInt(startRowUnder);
            var startTierOnDeck = parseInt(startTierOnDeck);
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

            if (!isValid) {
                // If there are validation errors, display them in a single alert
                Swal.fire({
                    title: 'Validation Error',
                    html: errorMessages.join('<br>'),
                    icon: 'error',
                    confirmButtonText: 'OK',
                });
            } else {
                // If the form is valid, submit it
                document.getElementById('bayForm').submit();
            }
        });
    });
</script>
@endsection