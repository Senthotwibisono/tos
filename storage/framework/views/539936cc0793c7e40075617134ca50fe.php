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
                                  <td></td>                         
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
                                  <td></td>                         
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
</div><?php /**PATH C:\xampp\htdocs\tos\resources\views/invoice/bc/table/import.blade.php ENDPATH**/ ?>