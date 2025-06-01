<div class="modal fade" id="payModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable modal-lg"role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Payment Form</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"> <i data-feather="x"></i></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="">Poforma No</label>
                            <input type="text" name="proforma_no" id="proforma_no_edit" class="form-control" readonly>
                            <input type="hidden" name="id" id="id_edit" class="form-control">
                            <input type="hidden" name="type" id="type_edit" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="">Type</label>
                            <input type="text" name="inv_type" id="inv_type_edit" class="form-control" readonly>
                        </div>
                        <div class="form-group">
                            <label for="">Grand Total</label>
                            <input type="text" name="grand_total" id="grand_total_edit" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="col-6 d-none" id="anotherForm">
                        <b style="color: red;">Anda juga memiliki tagihan lain dengan tiper berbeda</b>
                        <div class="form-group">
                            <label for="">Proforma</label>
                            <input type="text" class="form-control" id="anotherProforma">
                            <label for="">Type</label>
                            <input type="text" class="form-control" id="anotherType">
                            <label for="">Grand Total</label>
                            <input type="text" class="form-control" id="anotherGrandTotal">
                            <label for="">Ceklis untuk membayar tagihan lainnya</label>
                            <input class="form-check-input" type="checkbox" name="couple" id="couple" checked>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="" class="btn btn-warning ml-1" onClick="piutang()"> <i class="bx bx-check d-block d-sm-none"></i> <span class="d-none d-sm-block">Piutanng</span> </button>
                <button type="button" id="" class="btn btn-success ml-1" onClick="lunas()"> <i class="bx bx-check d-block d-sm-none"></i> <span class="d-none d-sm-block">Lunas</span> </button>
                <button type="button" id="" class="btn btn-info ml-1" onClick="createVA()"> <i class="bx bx-check d-block d-sm-none" disabled></i> <span class="d-none d-sm-block">Virtual Account</span> </button>
            </div>
        </div>
    </div>
</div>