<div class="modal fade" id="contLuar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns" id="tableDetailLuar">
            <thead>
                <tr>
                    <th>R.O No</th>
                    <th>Container No</th>
                    <th>Deail</th>                            
            </thead>
            <tbody>
            </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<!-- Detail -->
<div class="modal fade text-left w-100" id="ContainerStuffingLuar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel16" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="modal-title" id="myModalLabel16">Detail Container</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-md-12 border-right">
                    <div class="row" style="border-right: 2px solid blue;">
                        <h5>Container Fill</h5>
                        <div id="container-list">
                        <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns" id="tableContainerLuar">
                    <thead>
                        <tr>
                            <th>Container No</th>
                            <th>Iso Code</th>
                            <th>Vessel</th>
                            <th>Voy</th>                            
                            <th>Size</th>                            
                            <th>Type</th>                            
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Close</span>
                </button>
            </div>
        </div>
    </div>
</div>