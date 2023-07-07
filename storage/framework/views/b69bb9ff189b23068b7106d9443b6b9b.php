<div class="dropdown">
                                <a href="#" id="topbarUserDropdown" class="user-dropdown d-flex align-items-center dropend dropdown-toggle " data-bs-toggle="dropdown" aria-expanded="false">
                                   
                                    <?php if(Auth::user()->profil): ?>
                                            <div class="avatar avatar-md2 ">
                                        <img class="round-image-3" src="<?php echo e(asset('profil/' .Auth::user()->profil)); ?>" alt="Avatar">
                                        </div>
                                    <?php else: ?>
                                    <div class="avatar avatar-md2" >
                                        <img src="<?php echo e(asset('dist/assets/images/faces/1.jpg')); ?>" alt="Avatar">
                                    </div>
                                    <?php endif; ?>
                                    <div class="text">
                                        <h6 class="user-dropdown-name"><?php echo e(Auth::user()->name); ?></h6>
                                        <p class="user-dropdown-status text-sm text-muted"><?php echo e(Auth::user()->email); ?></p>
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end shadow-lg" aria-labelledby="topbarUserDropdown">
                                  
                                  <li><a class="dropdown-item" href="/profile">Settings</a></li>
                                  <li><hr class="dropdown-divider"></li>
                                  <li><a class="dropdown-item" href="<?php echo e(route('logout')); ?>"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();"><i
                                                class="icon-mid bi bi-box-arrow-left me-2"></i> 
                                        <?php echo e(__('Logout')); ?>

                                    </a>

                                    <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
                                        <?php echo csrf_field(); ?>
                                    </form></li>
                                </ul>
                            </div>
<?php /**PATH E:\Fdw File Storage 1\CTOS\dev\frontend\tos-dev-local\resources\views/partial/spps/authheader.blade.php ENDPATH**/ ?>