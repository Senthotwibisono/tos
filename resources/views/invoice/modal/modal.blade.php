<div class="modal fade" id="cust_invoice" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cust_invoice">
                    Add Customer Data
                </h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="">Customer Name</label>
                    <input type="text" class="form-control" placeholder="Customer Name" required>
                </div>
                <div class="form-group">
                    <label for="">Phone Number</label>
                    <input type="tel" class="form-control" name="telphone" placeholder="0878377728" pattern="[0-9]{3} [0-9]{3} [0-9]{4}" maxlength="12" title="Ten digits code" required />
                </div>
                <div class="form-group">
                    <label for="">Fax Code</label>
                    <input type="text" class="form-control" placeholder="13610">
                </div>
                <div class="form-group">
                    <label for="">NPWP</label>
                    <input type="text" class="form-control" placeholder="6673009219991">
                </div>
                <div class="form-group">
                    <label for="">Address</label>
                    <textarea name="" class="form-control" placeholder="Enter the address here" id="" cols="10" rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Cancel</span>
                </button>
                <button type="button" class="btn btn-primary ml-1" data-bs-dismiss="modal">
                    <i class="bx bx-check d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Submit</span>
                </button>
            </div>
        </div>
    </div>
</div>