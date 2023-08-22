<!-- <div class="col-12 border">
  <div class="card-header">
        Dokumen Export Beacukai
      </div>
  <div class="card-body">
        <div class="list-group list-group-horizontal-sm mb-1 text-center" role="tablist">
          <a class="list-group-item list-group-item-action active" id="list-exp-list" data-bs-toggle="list" href="#exp" role="tab">Export</a>
          <a class="list-group-item list-group-item-action" id="list-peaExp-list" data-bs-toggle="list" href="#peaExp" role="tab">Dok Pabean Export</a>
        </div>
      <div class="tab-content text-justify" id="load_ini">   
                  <div class="tab-pane fade show active" id="exp" role="tabpanel" aria-labelledby="list-exp-list">
                    <div class="col-12 border">
                        <div class="card-header">
                            <button class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#info">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                            </svg> Download NPE DOK
                          </button>   
                        </div>
                    <div class="card-body">
                        <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns" id="table2">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>No Dok NPE</th>
                            <th>Tgl Daftar</th>
                            <th>Kode Kantor</th>
                            <th>Nama Eksportir</th>
                            <th>NPWP</th>
                            <th>Jumlah Container</th>
                        </tr>
                    </thead>
                    <tbody>
                      <?php $__currentLoopData = $details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $np): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($loop->iteration); ?></td>                            
                            <td><?php echo e($np->NONPE); ?></td>
                            <td><?php echo e($np->TGL_DAFTAR); ?></td>
                            <td><?php echo e($np->KD_KANTOR); ?></td>
                            <td><?php echo e($np->NAMA_EKS); ?></td>
                            <td><?php echo e($np->NPWP_EKS); ?></td>
                            <td><?php echo e($container[$np->NONPE]); ?></td>
                           
                            <td></td>                         
                        </tr>
                     <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                  </table>
            </div>
          
          </div>
        </div>
        <div class="tab-pane fade" id="peaExp" role="tabpanel" aria-labelledby="list-peaExp-list">
                    <div class="col-12 border">
                        <div class="card-header">
                        <?php echo $__env->make('invoice.bc.button.peaBNexp', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>   
                        </div>
                    <div class="card-body">
                        <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns" id="table2Pea">
                        <thead>
                              <tr>
                                  <th>No</th>
                                  <th>No Pabean</th>
                                  <th>Tgl Dokumen</th>
                                  <th>Importir</th>
                                  <th>NPWP</th>
                                  <th>Nama Angkut</th>
                                  <th>Jumlah Container</th>
                                  <th>Detail</th>
                              </tr>
                          </thead>
                          <tbody>
                            <?php $__currentLoopData = $pabean_EXP; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bean): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                              <tr>
                                  <td><?php echo e($loop->iteration); ?></td>                            
                                  <td><?php echo e($bean->KD_DOK_INOUT); ?></td>
                                  <td><?php echo e($bean->TGL_DOK_INOUT); ?></td>
                                  <td><?php echo e($bean->NAMA_IMP); ?></td>
                                  <td><?php echo e($bean->NPWP_PPJK); ?></td>
                                  <td><?php echo e($bean->NM_ANGKUT); ?></td>
                                  <td><?php echo e($bean->JML_CONT); ?></td>
                                  <td></td>                         
                              </tr>
                           <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                          </tbody>
                  </table>
            </div>
          </div>
        </div>
      </div>
    </div> -->


    <div class="col-12 border">
    <div class="card-header">
    Dokumen Export Beacukai
    </div>
    <div class="card-body">
                <div class="list-group list-group-horizontal-sm mb-1 text-center" role="tablist">
                    <a class="list-group-item list-group-item-action active" id="list-exp-list" data-bs-toggle="list" href="#exp" role="tab">Export</a>
                    <a class="list-group-item list-group-item-action" id="list-peaExp-list" data-bs-toggle="list" href="#peaExp" role="tab">Dok Pabean Export</a>
                </div>
        <div class="tab-content text-justify" id="load_ini">   
            <div class="tab-pane fade show active" id="exp" role="tabpanel" aria-labelledby="list-exp-list">
                <div class="col-12 border">
                    <div class="card-header">
                    <button class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#info">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                            </svg> Download NPE DOK
                          </button>      
                    </div>
                    <div class="card-body">
                    <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns" id="table2">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>No Dok NPE</th>
                            <th>Tgl Daftar</th>
                            <th>Kode Kantor</th>
                            <th>Nama Eksportir</th>
                            <th>NPWP</th>
                            <th>Jumlah Container</th>
                        </tr>
                    </thead>
                    <tbody>
                      <?php $__currentLoopData = $details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $np): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($loop->iteration); ?></td>                            
                            <td><?php echo e($np->NONPE); ?></td>
                            <td><?php echo e($np->TGL_DAFTAR); ?></td>
                            <td><?php echo e($np->KD_KANTOR); ?></td>
                            <td><?php echo e($np->NAMA_EKS); ?></td>
                            <td><?php echo e($np->NPWP_EKS); ?></td>
                            <td><?php echo e($container[$np->NONPE]); ?></td>
                           
                            <td></td>                         
                        </tr>
                     <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                  </table>
                    </div>

                </div>
            </div>
            <div class="tab-pane fade" id="peaExp" role="tabpanel" aria-labelledby="list-peaExp-list">
                <div class="col-12 border">
                    <div class="card-header">
                    <?php echo $__env->make('invoice.bc.button.peaBNexp', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>  
                    </div>
                    <div class="card-body">
                    <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns" id="table2Pea">
                        <thead>
                              <tr>
                                  <th>No</th>
                                  <th>No Pabean</th>
                                  <th>Tgl Dokumen</th>
                                  <th>Importir</th>
                                  <th>NPWP</th>
                                  <th>Nama Angkut</th>
                                  <th>Jumlah Container</th>
                                  <th>Detail</th>
                              </tr>
                          </thead>
                          <tbody>
                            <?php $__currentLoopData = $pabean_EXP; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bean): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                              <tr>
                                  <td><?php echo e($loop->iteration); ?></td>                            
                                  <td><?php echo e($bean->KD_DOK_INOUT); ?></td>
                                  <td><?php echo e($bean->TGL_DOK_INOUT); ?></td>
                                  <td><?php echo e($bean->NAMA_IMP); ?></td>
                                  <td><?php echo e($bean->NPWP_PPJK); ?></td>
                                  <td><?php echo e($bean->NM_ANGKUT); ?></td>
                                  <td><?php echo e($bean->JML_CONT); ?></td>
                                  <td></td>                         
                              </tr>
                           <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                          </tbody>
                  </table>
                    </div>

                </div>
            </div>
           
        </div>          
    </div>
</div><?php /**PATH C:\xampp\htdocs\tos\resources\views/invoice/bc/table/export.blade.php ENDPATH**/ ?>