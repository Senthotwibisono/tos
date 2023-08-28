<?php $__env->startSection('custom_styles'); ?>
<style>
    #outer-dropzone {
  height: 140px;
}

#inner-dropzone {
  height: 80px;
}

.dropzone {
  background-color: #bfe4ff;
  border: dashed 4px transparent;
  border-radius: 4px;
  margin: 10px auto 30px;
  padding: 10px;
  width: 80%;
  transition: background-color 0.3s;
}

.drop-active {
  border-color: #aaa;
}

.drop-target {
  background-color: #29e;
  border-color: #fff;
  border-style: solid;
}

.drag-drop {
  display: inline-block;
  min-width: 40px;
  padding: 2em 0.5em;
  margin: 1rem 0 0 1rem;

  color: #fff;
  background-color: #29e;
  border: solid 2px #fff;

  touch-action: none;
  transform: translate(0px, 0px);

  transition: background-color 0.3s;
}

.drag-drop.can-drop {
  color: #000;
  background-color: #4e4;
}
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="page-heading">
        
<div class="container">
    <div class="card mt-5">
        <div class="card-header">
        <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Ship Planning</h3>
                    <p class="text-subtitle text-muted"></p>
                </div>           
        </div>
        <hr>
        <div class="card-body">
        

    <!-- Konten lainnya -->
    <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns" id="table1">
                    <thead>
                        <tr>
                            <th>Ves. Name</th>
                            <th>Ves. Code</th>
                            <th>Voy Out</th>
                            <th>Agent</th>
                            <th>Liner</th>
                            <th>Dep. Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php $__currentLoopData = $vessel_voyage; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $voyage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($voyage->ves_name); ?></td>
                            <td><?php echo e($voyage->ves_code); ?></td>                            
                            <td><?php echo e($voyage->voy_out); ?></td>
                            <td><?php echo e($voyage->agent); ?></td>
                            <td><?php echo e($voyage->liner); ?></td>
                            <td><?php echo e($voyage->etd_date); ?></td>
                            <td>
                            

                        

                            <a href="javascript:void(0)"class="btn icon icon-left btn-outline-info edit-modal" data-id="<?php echo e($voyage->ves_id); ?>"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit">
                              <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                              <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                            </svg> Start Planning</a>
                           
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade text-left w-100" id="success" tabindex="-1" role="dialog" aria-labelledby="myModalLabel16" aria-hidden="true">
         <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" role="document">
             <div class="modal-content">
                 <div class="modal-header bg-info">
                     <h4 class="modal-title" id="myModalLabel16">Plan</h4>
                     <button type="button" class="close" data-bs-dismiss="modal"
                         aria-label="Close">
                         <i data-feather="x"></i>
                     </button>
                 </div>
                 <div class="modal-body">
                    <div class="col-md-4 border-right">
                        <div class="row" style="border-right: 2px solid blue ;">
                          <h5>Container Fill</h5>
                          <div id="container-list">
                    <!-- Data container yang sesuai akan ditampilkan di sini -->
                </div>

                         

                        </div>
                      </div>
                      <div class="col-8">
                      <div id="no-drop" class="drag-drop"> #no-drop </div>

<div id="yes-drop" class="drag-drop"> #yes-drop </div>

<div id="outer-dropzone" class="dropzone">
  #outer-dropzone
  <div id="inner-dropzone" class="dropzone">#inner-dropzone</div>
 </div>
                      </div>
                 </div>
                 <div class="modal-footer">
                     <button type="button" class="btn btn-light-secondary"
                         data-bs-dismiss="modal">
                         <i class="bx bx-x d-block d-sm-none"></i>
                         <span class="d-none d-sm-block">Close</span>
                     </button>
                     <button type="button" class="btn btn-primary ml-1"
                         data-bs-dismiss="modal">
                         <i class="bx bx-check d-block d-sm-none"></i>
                         <span class="d-none d-sm-block">Accept</span>
                     </button>
                 </div>
             </div>
         </div>
     </div>
 </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('custom_js'); ?>
<script src="<?php echo e(asset('vendor/components/jquery/jquery.min.js')); ?>"></script>
<script src="<?php echo e(asset('select2/dist/js/select2.full.min.js')); ?>"></script>
<script src="<?php echo e(asset('dist/assets/extensions/sweetalert2/sweetalert2.min.js')); ?>"></script>    
<script src="<?php echo e(asset('dist/assets/js/pages/sweetalert2.js')); ?>"></script>
<script type="module">
  import 'https://cdn.interactjs.io/v1.9.20/auto-start/index.js'
  import 'https://cdn.interactjs.io/v1.9.20/actions/drag/index.js'
  import 'https://cdn.interactjs.io/v1.9.20/actions/resize/index.js'
  import 'https://cdn.interactjs.io/v1.9.20/modifiers/index.js'
  import 'https://cdn.interactjs.io/v1.9.20/dev-tools/index.js'
  import interact from 'https://cdn.interactjs.io/v1.9.20/interactjs/index.js'

  interact('.item').draggable({
    onmove(event) {
      console.log(event.pageX, event.pageY)
    },
  })
</script>

<script>
// In your Javascript (external .js resource or <script> tag)
// $(document).ready(function() {
//     $('.vesid').select2();
// });

$(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });


        $(document).on('click', '.edit-modal', function() {
    let id = $(this).data('id');
    $.ajax({
      type: 'GET',
      url: '/planning/plan-ves-' + id,
      cache: false,
      data: {
        ves_id: id
      },
      dataType: 'json',

      success: function(response) {

        console.log(response);
        $('#success').modal('show');
        $('#success #container-list').empty();

// Looping untuk menampilkan semua data container
$.each(response.data, function(index, container) {
    let container_no = container.container_no;
    let container_key = container.container_key;
    // Menambahkan data container ke dalam modal
    $(' #success #container-list').append('<div>' + container_no + ' - ' + container_key + '</div>');
});

      },
      error: function(data) {
        console.log('error:', data)


      }



    });

  });


        $(document).ready(function() {
            $('#vesid').on('change', function() {
                let id = $(this).val();
                $.ajax({
                    type: 'POST',
                    url: '/review-get-ves-disch',
                    data: { ves_id : id },
                    success: function(response) {
                       
                            $('#name').val(response.name);
                            $('#code').val(response.code);
                            
                        },
                    error: function(data) {
                        console.log('error:', data);
                    },
                });
            });
    });
});

interact('.dropzone').dropzone({
  // only accept elements matching this CSS selector
  accept: '#yes-drop',
  // Require a 75% element overlap for a drop to be possible
  overlap: 0.75,

  // listen for drop related events:

  ondropactivate: function (event) {
    // add active dropzone feedback
    event.target.classList.add('drop-active')
  },
  ondragenter: function (event) {
    var draggableElement = event.relatedTarget
    var dropzoneElement = event.target

    // feedback the possibility of a drop
    dropzoneElement.classList.add('drop-target')
    draggableElement.classList.add('can-drop')
    draggableElement.textContent = 'Dragged in'
  },
  ondragleave: function (event) {
    // remove the drop feedback style
    event.target.classList.remove('drop-target')
    event.relatedTarget.classList.remove('can-drop')
    event.relatedTarget.textContent = 'Dragged out'
  },
  ondrop: function (event) {
    event.relatedTarget.textContent = 'Dropped'
  },
  ondropdeactivate: function (event) {
    // remove active dropzone feedback
    event.target.classList.remove('drop-active')
    event.target.classList.remove('drop-target')
  }
})

interact('.drag-drop')
  .draggable({
    inertia: true,
    modifiers: [
      interact.modifiers.restrictRect({
        restriction: 'parent',
        endOnly: true
      })
    ],
    autoScroll: true,
    // dragMoveListener from the dragging demo above
    listeners: { move: dragMoveListener }
  })

</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('partial.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\tos\resources\views/planning/ship/main.blade.php ENDPATH**/ ?>