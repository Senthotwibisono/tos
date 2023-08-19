<div class="modal fade text-left" id="info" tabindex="-1" role="dialog" aria-labelledby="myModalLabel130" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title white" id="myModalLabel130">Info Modal</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i data-feather="x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-3">
                            <label for="first-name-vertical">Pilih Dok</label>
                        </div>
                        <div class="col-7">
                        <select class="form-control" id="kode" name="kode" disabled>
                                        <option value="NPE">NPE</option>
                        </select>
                        </div>
                       </div>
                       <br>
                       <div class="row">
                        <div class="col-3">
                            <label for="first-name-vertical">No Dokumen</label>
                        </div>
                        <div class="col-7">
                            <input type="text" id="noNPE" name="NO_PE">
                        </div>
                       </div>
                       <br>
                       <div class="row">
                        <div class="col-3">
                            <label for="first-name-vertical">Kode Kantor</label>
                        </div>
                        <div class="col-7">
                            <input type="text" id="kntr" name="KD_KANTOR">
                        </div>
                       </div>
                       <br>
                       <div class="row">
                        <div class="col-3">
                            <label for="first-name-vertical">NPWP</label>
                        </div>
                        <div class="col-7">
                            <input type="text" id="npwpNPE" name="NPWP_NPE">
                        </div>
                    </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal"> <i class="bx bx-x d-block d-sm-none"></i> <span class="d-none d-sm-block">Close</span> 
                        </button>
                    <button type="button" class="btn btn-info ml-1 downloadNPE" data-bs-dismiss="modal"> <i class="bx bx-check d-block d-sm-none"></i> <span class="d-none d-sm-block">Download</span>
                     </button>
                </div>
            </div>
        </div>
    </div>
</div><?php /**PATH C:\xampp\htdocs\tos\resources\views/invoice/bc/modal-npe.blade.php ENDPATH**/ ?>