<?php $__env->startSection('content'); ?>
    <section class="section-profile-cover section-shaped my--1 d-none d-md-none d-lg-block d-lx-block">
        <!-- Circles background -->
        <img class="bg-image " src="<?php echo e(config('global.restorant_details_cover_image')); ?>" style="width: 100%;">
        <!-- SVG separator -->
        <div class="separator separator-bottom separator-skew">

        </div>
    </section>
    <section class="section bg-secondary">

      <div class="container">


        <?php if (isset($component)) { $__componentOriginalf6d1e1ab7a8df2de5d0bdc28df8775f180a35b0c = $component; } ?>
<?php $component = $__env->getContainer()->make(Mckenziearts\Notify\NotifyComponent::class, []); ?>
<?php $component->withName('notify-messages'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes([]); ?>
<?php if (isset($__componentOriginalf6d1e1ab7a8df2de5d0bdc28df8775f180a35b0c)): ?>
<?php $component = $__componentOriginalf6d1e1ab7a8df2de5d0bdc28df8775f180a35b0c; ?>
<?php unset($__componentOriginalf6d1e1ab7a8df2de5d0bdc28df8775f180a35b0c); ?>
<?php endif; ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>

          <div class="row">

            <!-- Left part -->
            <div class="col-md-8 offset-md-2">

              <!-- List of items -->
              <?php echo $__env->make('cart.combined', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
              <!--<?php echo $__env->make('cart.comment', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>-->

              <!-- Restaurant -->
              <!--<?php echo $__env->make('cart.restaurant', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>-->
            </div>
            </div>

    </div>
    <?php echo $__env->make('clients.modals', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
  </section>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('js'); ?>

  <script async defer src= "https://maps.googleapis.com/maps/api/js?key=<?php echo config('settings.google_maps_api_key'); ?>&callback=initAddressMap"></script>
  <!-- Stripe -->
  <script src="https://js.stripe.com/v3/"></script>
  <script>
    "use strict";
    var RESTORANT = <?php echo json_encode($restorant) ?>;
    var STRIPE_KEY="<?php echo e(config('settings.stripe_key')); ?>";
    var ENABLE_STRIPE="<?php echo e(config('settings.enable_stripe')); ?>";
    var initialOrderType = 'delivery';
    if(RESTORANT.can_deliver == 1 && RESTORANT.can_pickup == 0){
        initialOrderType = 'delivery';
    }else if(RESTORANT.can_deliver == 0 && RESTORANT.can_pickup == 1){
        initialOrderType = 'pickup';
    }
  </script>
  <script src="<?php echo e(asset('custom')); ?>/js/checkout.js"></script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.front', ['class' => ''], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/laravel/web/im.yummy-hunt.com/public_html/resources/views/cart.blade.php ENDPATH**/ ?>