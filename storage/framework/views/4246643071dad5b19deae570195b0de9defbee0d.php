<?php
$badgeTypes=['badge-primary','badge-primary','badge-success','badge-success','badge-default','badge-warning','badge-success','badge-info','badge-danger','badge-success','badge-success','badge-success','badge-success'];
?>
<?php if($order->status->count()>0): ?>
    <span class="badge <?php echo e($badgeTypes[$order->status->pluck('id')->last()]); ?> badge-pill"><?php echo e(__($order->status->pluck('name')->last())); ?></span>
<?php endif; ?>  <?php /**PATH /home/sites/9a/f/f4e2e25701/public_html/ae/resources/views/orders/partials/laststatus.blade.php ENDPATH**/ ?>