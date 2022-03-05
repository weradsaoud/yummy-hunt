<div class="col-sm-12 col-md-<?php echo e($col); ?> col-lg-<?php echo e($col); ?> mb-<?php echo e($col); ?> ">
  <div class="card cardWithShadow pricingCard text-center">
    <div class="card-body">
      <!-- <div class="imgHolderInCard">
        <img class="image-in-card"
          src="<?php echo e(asset('social')); ?>/img/SVG/512/rocket.svg" />
      </div> -->
      <h5 class="card-title"><?php echo e(__($plan['name'])); ?></h5>
      <p class="card-text"><?php echo e(__($plan['description'])); ?></p>
      <div class="price-block">
        <span class="price-block-currency"><?php echo e(config('settings.cashier_currency')); ?></span>
        <span class="price-block-value"><?php echo e($plan['price']); ?></span>
        <span class="price-block-period">/<?php echo e($plan['period'] == 1? __('whatsapp.month') :  __('whatsapp.year')); ?></span>
      </div>
      <div class="plan_feature_list">
        <ul class="plan_features list-unstyled align-items-center">
          <?php $__currentLoopData = explode(",",$plan['features']); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li>
              <p class="text-sm"><?php echo e(__($feature)); ?></p>
            </li>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
      </div>
      <br />
      <a href="<?php echo e(route('newrestaurant.register')); ?>" type="button" class="btn btn-outline-success">
        <?php echo e(__('whatsapp.pricing_button') . " "); ?> <?php echo e($plan['name']); ?>

      </a>
    </div>
  </div>
</div><?php /**PATH /home/sites/9a/f/f4e2e25701/public_html/resources/views/social/partials/plan.blade.php ENDPATH**/ ?>