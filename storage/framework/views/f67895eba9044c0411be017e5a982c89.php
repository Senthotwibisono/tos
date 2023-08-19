<div class="col-12 border">
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
                            <td></td>                         
                        </tr>
                     <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                  </table>
                            </div>
                            </div><?php /**PATH C:\xampp\htdocs\tos\resources\views/invoice/bc/table/dok-lain.blade.php ENDPATH**/ ?>