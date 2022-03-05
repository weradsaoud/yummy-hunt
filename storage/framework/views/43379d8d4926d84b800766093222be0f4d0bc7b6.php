<div class="card card-profile shadow mt--300">
    <div class="px-4">
      <div class="mt-5">
        <h3><?php echo e(__('Checkout')); ?><span class="font-weight-light"></span></h3>
      </div>
      <div  class="border-top">
        <br />
        <div class="alert alert-danger" role="alert">
           <?php echo e(__('Order can not be placed since restaurant will be / is closed.')); ?>

        </div>
      </div>
      <br />
      <br />
    </div>
  </div>
  <br />

  <?php if(config('settings.is_demo') && config('settings.enable_stripe')): ?>
    <?php echo $__env->make('cart.democards', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>    
  <?php endif; ?>
<?php /**PATH /home/sites/9a/f/f4e2e25701/public_html/resources/views/cart/closed.blade.php ENDPATH**/ ?>