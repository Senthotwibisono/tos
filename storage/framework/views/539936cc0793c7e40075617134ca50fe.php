<div class="col-12 border">
    <div class="card-header">
       Dokumen Import Bea Cukai
    </div>
    <div class="card-body">
                <div class="list-group list-group-horizontal-sm mb-1 text-center" role="tablist">
                    <a class="list-group-item list-group-item-action active" id="list-pib-list" data-bs-toggle="list" href="#pib" role="tab">SPPB-PIB 2.O</a>
                    <a class="list-group-item list-group-item-action" id="list-bc-list" data-bs-toggle="list" href="#bc" role="tab">SPPB-BC 2.3</a>
                    <a class="list-group-item list-group-item-action" id="list-pea-list" data-bs-toggle="list" href="#pea" role="tab">Dok Pabean Import</a>
                </div>
        <div class="tab-content text-justify" id="load_ini">   
            <div class="tab-pane fade show active" id="pib" role="tabpanel" aria-labelledby="list-pib-list">
                <div class="col-12 border">
                    <div class="card-header">
                    <?php echo $__env->make('invoice.bc.button.import', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>   
                    </div>
                    <div class="card-body">
                        <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns" id="table1">
                          <thead>
                              <tr>
                                  <th>No</th>
                                  <th>No SPPB</th>
                                  <th>Tgl SPPB</th>
                                  <th>Importir</th>
                                  <th>NPWP</th>
                                  <th>Nama Angkut</th>
                                  <th>Jumlah Container</th>
                                  <th>Detail</th>
                              </tr>
                          </thead>
                          <tbody>
                            <?php $__currentLoopData = $sppb; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pib): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                              <tr>
                                  <td><?php echo e($loop->iteration); ?></td>                            
                                  <td><?php echo e($pib->NO_SPPB); ?></td>
                                  <td><?php echo e($pib->TGL_SPPB); ?></td>
                                  <td><?php echo e($pib->NAMA_IMP); ?></td>
                                  <td><?php echo e($pib->NPWP_IMP); ?></td>
                                  <td><?php echo e($pib->NM_ANGKUT); ?></td>
                                  <td><?php echo e($container_SPPB[$pib->CAR]); ?></td>
                                  <td>
                                        <a href="javascript:void(0)"class="btn icon icon-left btn-outline-info detail-cont" data-id="<?php echo e($pib->CAR); ?>"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                        </svg> Detail</a>
                                </td>                         
                              </tr>
                           <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                          </tbody>
                        </table>
                    </div>

                </div>
            </div>
            <div class="tab-pane fade" id="bc" role="tabpanel" aria-labelledby="list-bc-list">
                <div class="col-12 border">
                    <div class="card-header">
                    <?php echo $__env->make('invoice.bc.button.import', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>   
                    </div>
                    <div class="card-body">
                        <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns" id="table1bc">
                          <thead>
                              <tr>
                                  <th>No</th>
                                  <th>No SPPB</th>
                                  <th>Tgl SPPB</th>
                                  <th>Importir</th>
                                  <th>NPWP</th>
                                  <th>Nama Angkut</th>
                                  <th>Jumlah Container</th>
                                  <th>Detail</th>
                              </tr>
                          </thead>
                          <tbody>
                            <?php $__currentLoopData = $sppb_bc; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                              <tr>
                                  <td><?php echo e($loop->iteration); ?></td>                            
                                  <td><?php echo e($bc->NO_SPPB); ?></td>
                                  <td><?php echo e($bc->TGL_SPPB); ?></td>
                                  <td><?php echo e($bc->NAMA_IMP); ?></td>
                                  <td><?php echo e($bc->NPWP_IMP); ?></td>
                                  <td><?php echo e($bc->NM_ANGKUT); ?></td>
                                  <td><?php echo e($bc->JML_CONT); ?></td>
                                  <td>
                                  <a href="javascript:void(0)"class="btn icon icon-left btn-outline-info detail-cont" data-id="<?php echo e($bc->CAR); ?>"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                        </svg> Detail</a>
                                  </td>
                                                           
                              </tr>
                           <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                          </tbody>
                        </table>
                    </div>

                </div>
            </div>
            <div class="tab-pane fade" id="pea" role="tabpanel" aria-labelledby="list-pea-list">
                <div class="col-12 border">
                    <div class="card-header">
                    <?php echo $__env->make('invoice.bc.button.peaBN', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>   
                    </div>
                    <div class="card-body">
                        <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns" id="table1pea">
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
                            <?php $__currentLoopData = $pabean_import; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bean): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                              <tr>
                                  <td><?php echo e($loop->iteration); ?></td>                            
                                  <td><?php echo e($bean->KD_DOK_INOUT); ?></td>
                                  <td><?php echo e($bean->TGL_DOK_INOUT); ?></td>
                                  <td><?php echo e($bean->NAMA_IMP); ?></td>
                                  <td><?php echo e($bean->NPWP_PPJK); ?></td>
                                  <td><?php echo e($bean->NM_ANGKUT); ?></td>
                                  <td><?php echo e($bean->JML_CONT); ?></td>
                                  <td>
                                  <a href="javascript:void(0)"class="btn icon icon-left btn-outline-info detail-cont" data-id="<?php echo e($bean->CAR); ?>"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                        </svg> Detail</a>
                                  </td>                         
                              </tr>
                           <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                          </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>          
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\tos\resources\views/invoice/bc/table/import.blade.php ENDPATH**/ ?>