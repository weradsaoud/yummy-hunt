<?php $__env->startSection('content'); ?>
    <div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
    </div>

    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col">
                <div class="card shadow">
                    <div class="card-header border-0">
                        
                    </div>

                    <div class="col-12">
                        <?php echo $__env->make('partials.flash', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    </div>
                    <?php if(count($countries)): ?>
                    <div class="table-responsive">
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col"><?php echo e(__('Country')); ?></th>
                                    <th scope="col"><?php echo e(__('Header Text Restaurant')); ?></th>
                                    <th scope="col"><?php echo e(__('Header Text Restaurant (Arabic)')); ?></th>
                                    <th scope="col"><?php echo e(__('Header Text Salon')); ?></th>
                                     <th scope="col"><?php echo e(__('Header Text Salonr (Arabic)')); ?></th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($country->name); ?> </td>
                                     <td>
                                       <form action="<?php echo e(route('settings.add_header_text_r')); ?>" method="post">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('post'); ?>
                                        <input type="hidden" value="<?php echo e($country->id); ?>" name="id" />
                                        <input type="hidden" value="en" name="lang" />
                                         <input type="hidden" value="rest" name="type" />
                                         <textarea class="form-control" name="h_text"><?php echo e($country->header_text); ?></textarea>
                                          <button type="submit" class="btn mt-1" >
                                            <?php echo e(__('Save')); ?>

                                         </button>
                                         </form>
                                     </td>
                                      <td>
                                        <form action="<?php echo e(route('settings.add_header_text_r')); ?>" method="post">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('post'); ?>
                                        <input type="hidden" value="<?php echo e($country->id); ?>" name="id" />
                                        <input type="hidden" value="ar" name="lang" />
                                          <input type="hidden" value="rest" name="type" />
                                         <textarea class="form-control" name="h_text"><?php echo e($country->header_text_ar); ?></textarea>
                                          <button type="submit" class="btn mt-1" >
                                            <?php echo e(__('Save')); ?>

                                         </button>
                                         </form>
                                     </td>
                                      <td>
                                     <form action="<?php echo e(route('settings.add_header_text_r')); ?>" method="post">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('post'); ?>
                                        <input type="hidden" value="<?php echo e($country->id); ?>" name="id" />
                                        <input type="hidden" value="en" name="lang" />
                                          <input type="hidden" value="salon" name="type" />
                                         <textarea class="form-control" name="h_text"><?php echo e($country->header_text_salon); ?></textarea>
                                          <button type="submit" class="btn mt-1" >
                                            <?php echo e(__('Save')); ?>

                                         </button>
                                         </form>
                                     </td>
                                      <td>
                                      <form action="<?php echo e(route('settings.add_header_text_r')); ?>" method="post">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('post'); ?>
                                        <input type="hidden" value="<?php echo e($country->id); ?>" name="id" />
                                        <input type="hidden" value="ar" name="lang" />
                                        <input type="hidden" value="salon" name="type" />
                                         <textarea class="form-control" name="h_text"><?php echo e($country->header_text_salon_ar); ?></textarea>
                                          <button type="submit" class="btn mt-1" >
                                            <?php echo e(__('Save')); ?>

                                         </button>
                                         </form>
                                     </td>


                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    <?php endif; ?>
                    <div class="card-footer py-4">
                        <?php if(count($countries)): ?>
                            <nav class="d-flex justify-content-end" aria-label="...">
                                <?php echo e($countries->links()); ?>

                            </nav>
                        <?php else: ?>
                            <h4><?php echo e(__('You don`t have any pages')); ?> ...</h4>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <?php echo $__env->make('layouts.footers.auth', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', ['title' => __('Manage Header Text')], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/laravel/web/ae.yummy-hunt.com/public_html/resources/views/tables/manageheader.blade.php ENDPATH**/ ?>