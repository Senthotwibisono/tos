<!-- SPPB Modal -->
<div class="modal fade text-left w-100" id="lain" tabindex="-1" role="dialog" aria-labelledby="myModalLabel16" aria-hidden="true">
         <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-m" role="document">
             <div class="modal-content">
                 <div class="modal-header bg-warning">
                     <h4 class="modal-title" id="myModalLabel16">Request Doc BC</h4>
                     <button type="button" class="close" data-bs-dismiss="modal"
                         aria-label="Close">
                         <i data-feather="x"></i>
                     </button>
                 </div>
                 
                 <div class="modal-body">
                   <div class="row">
                    <div class="col-3">
                        <label for="first-name-vertical">Pilih Dok</label>
                    </div>
                    <div class="col-7">
                    <select class="form-control" id="kodePab" name="kode_dok">
                                   @foreach($dok_lain as $dok)
                                   <option value="{{$dok->kode}}">{{$dok->name}}</option>
                                   @endforeach
                    </select>
                    </div>
                   </div>
                   <br>
                   <div class="row">
                    <div class="col-3">
                        <label for="first-name-vertical">No Dokumen</label>
                    </div>
                    <div class="col-7">
                        <input type="text" id="no_pabean" name="no_dok">
                    </div>
                   </div>
                   <br>
                   <div class="row">
                    <div class="col-3">
                        <label for="first-name-vertical">Tanggal Doc</label>
                    </div>
                    <div class="col-7">
                        <input type="text" id="tanggal_pabean" name="tgl_dok">
                    </div>
                   </div>

                 <div class="modal-footer">
                     <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                         <i class="bx bx-x d-block d-sm-none"></i><span class="d-none d-sm-block">Close</span>
                     </button>
                     <button type="button" class="btn btn-primary ml-1 download-peaBN" data-bs-dismiss="modal"><i class="bx bx-check d-block d-sm-none"></i><span class="d-none d-sm-block">Download</span>
                     </button>
                 </div>
             </div>
         </div>
     </div>
 </div>

 