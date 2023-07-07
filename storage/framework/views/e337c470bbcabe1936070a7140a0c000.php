<div class="modal fade" id="cust_invoice" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
                    <input id="cust_name" type="text" class="form-control" placeholder="Customer Name" required>
                </div>
                <div class="form-group">
                    <label for="">Customer Code</label>
                    <input id="cust_Code" type="text" class="form-control" placeholder="Customer Code" required>
                </div>
                <div class="form-group">
                    <label id="cust_phone" for="">Phone Number</label>
                    <input type="tel" class="form-control" name="telphone" placeholder="0878377728" pattern="[0-9]{3} [0-9]{3} [0-9]{4}" maxlength="12" title="Ten digits code" required />
                </div>
                <div class="form-group">
                    <label id="cust_fax" for="">Fax Code</label>
                    <input type="text" class="form-control" placeholder="13610">
                </div>
                <div class="form-group">
                    <label for="">NPWP</label>
                    <input id="cust_npwp" type="text" class="form-control" placeholder="6673009219991">
                </div>
                <div class="form-group">
                    <label for="">Address</label>
                    <textarea id="cust_address" name="" class="form-control" placeholder="Enter the address here" id="" cols="10" rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <a id="btn_cancel_cust" onclick="canceladdCustomer();" type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Cancel</span>
                </a>
                <a id="btn_submit_cust" type="button" class="btn btn-primary ml-1" data-bs-dismiss="modal">
                    <i class="bx bx-check d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Submit</span>
                </a>
            </div>
        </div>
    </div>
</div><?php /**PATH E:\Fdw File Storage 1\CTOS\dev\frontend\tos-dev-local\resources\views/invoice/modal/modal.blade.php ENDPATH**/ ?>