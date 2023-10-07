@extends('partial.main')

@section('content')
<div class="page-heading">
    <title>Ship Profile</title>
    <div class="container">
        <div class="card mt-5">
            <div class="card-header">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Ship Profile</h3>
                    <p class="text-subtitle text-muted"></p>
                </div>
            </div>
            <hr>
            <div class="card-body">
                <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns" id="table1">
                    <thead>
                        <tr>
                            <th>Ves. Name</th>
                            <th>Ves. Code</th>
                            <th>Liner. Name</th>
                            <th>Agent</th>
                            <th>Liner</th>
                            <th>User Id</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($vessel_master as $vmaster)
                        <tr>
                            <td>{{$vmaster->ves_name}}</td>
                            <td>{{$vmaster->ves_code}}</td>
                            <td>{{$vmaster->liner_name}}</td>
                            <td>{{$vmaster->agent}}</td>
                            <td>{{$vmaster->liner}}</td>
                            <td>{{$vmaster->user_id}}</td>
                            <td>
                                <!-- Add an action button here -->
                                <button type="button" class="btn btn-primary select-kapal-btn" data-bs-toggle="modal" data-bs-target="#exampleModalCenter" data-ves-name="{{$vmaster->ves_name}}" data-ves-code="{{$vmaster->ves_code}}">
                                    Select
                                </button>
                                <input type="hidden" id="vesCodeInput" name="ves_code" value="">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- Modal for Select Kapal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
                <h4>Ves. Name: <span id="vesName"></span><br> Ves. Code: <span id="vesCode"></span></h4>
                <form method="POST" action="{{ route('profile-kapal.store', ['ves_code' => '__VES_CODE__']) }}" id="bayForm">
                    @csrf

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

                    <button type="button" id="submitBtn" class="btn btn-primary" onclick="validateForm()">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('custom_js')
<script>
    $(document).ready(function() {
        // Handle the click event for the select-kapal-btn
        $('.select-kapal-btn').click(function() {
            // Get the data attributes from the clicked button
            var vesName = $(this).data('ves-name');
            var vesCode = $(this).data('ves-code');

            // Populate the modal with the selected values
            $('#vesName').text(vesName);
            $('#vesCode').text(vesCode);

            // Set the vesCode value in the hidden input field
            $('#selectedVesCode').val(vesCode); // Update the hidden input

            // Update the form action with the ves_code
            var form = document.getElementById('bayForm');
            var action = "{{ route('profile-kapal.store', ['ves_code' => '__VES_CODE__']) }}";
            action = action.replace('__VES_CODE__', vesCode);
            form.action = action;

            // Display Ves. Name and Ves. Code as labels
            $('#vesNameLabel').text(vesName);
            $('#vesCodeLabel').text(vesCode);
        });

        // Add an event listener to the submit button
        $('#submitBtn').click(function() {
            // Call the submitForm function when the button is clicked
            submitForm();
        });

        // Add a listener for form submission completion
        $('#bayForm').on('submit', function(e) {
            e.preventDefault(); // Prevent the default form submission

            // ... (Your form validation code) komat kamit

            // After a successful form submission, redirect to the grid view
            var selectedVesCode = $('#selectedVesCode').val();
            window.location.href = "{{ route('grid-box.index', ['ves_code' => '__VES_CODE__']) }}"
                .replace('__VES_CODE__', selectedVesCode);
        });
    });
    // Function to validate the form
    function submitForm() {
        var maxTierUnder = parseInt($('#max_tier_under').val());
        var maxRowUnder = parseInt($('#max_row_under').val());
        var maxTierOnDeck = parseInt($('#max_tier').val());
        var maxRowOnDeck = parseInt($('#max_row').val());
        var startTierUnder = parseInt($('#start_tier_under').val());
        var startRowUnder = parseInt($('#start_row_under').val());
        var startTierOnDeck = parseInt($('#start_tier').val());
        var startRowOnDeck = parseInt($('#start_row').val());

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
    }
</script>
@endsection