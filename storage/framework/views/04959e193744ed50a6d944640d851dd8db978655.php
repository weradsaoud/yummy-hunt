<?php if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('owner') || auth()->user()->hasRole('driver')): ?>
<?php
$lastStatusAlisas=$order->status->pluck('alias')->last();
?>
<div class="card-footer py-4">
    <h6 class="heading-small text-muted mb-4"><?php echo e(__('Actions')); ?></h6   >
    <nav class="justify-content-end" aria-label="...">
    <?php if(auth()->user()->hasRole('admin')): ?>
        <script>
            function setSelectedOrderId(id){
                $("#form-assing-driver").attr("action", "/updatestatus/assigned_to_driver/"+id);
            }
        </script>
        <?php if($lastStatusAlisas == "just_created"): ?>
            <a href="<?php echo e(url('updatestatus/accepted_by_admin/'.$order->id)); ?>" class="btn btn-primary"><?php echo e(__('Accept')); ?></a>
            <a href="<?php echo e(url('updatestatus/rejected_by_admin/'.$order->id)); ?>" class="btn btn-danger"><?php echo e(__('Reject')); ?></a>
        
        <?php elseif($lastStatusAlisas == "accepted_by_restaurant"&&$order->delivery_method.""!="2"): ?>
            <button type="button" class="btn btn-primary" onClick=(setSelectedOrderId(<?php echo e($order->id); ?>))  data-toggle="modal" data-target="#modal-asign-driver"><?php echo e(__('Assign to driver')); ?></button>
        <?php elseif($lastStatusAlisas == "rejected_by_driver"&&$order->delivery_method.""!="2"): ?>
            <button type="button" class="btn btn-primary" onClick=(setSelectedOrderId(<?php echo e($order->id); ?>))  data-toggle="modal" data-target="#modal-asign-driver"><?php echo e(__('Assign to driver')); ?></button>
        <?php elseif($lastStatusAlisas == "prepared"&&$order->delivery_method.""!="2"&&$order->driver==null): ?>
            <button type="button" class="btn btn-primary" onClick=(setSelectedOrderId(<?php echo e($order->id); ?>))  data-toggle="modal" data-target="#modal-asign-driver"><?php echo e(__('Assign to driver')); ?></button>
        <?php else: ?>
            <p><?php echo e(__('No actions for you right now!')); ?></p>
       <?php endif; ?>
    <?php endif; ?>
    <?php if(auth()->user()->hasRole('owner')): ?>
        <?php if(config('app.isft')): ?>
            <?php if($lastStatusAlisas == "accepted_by_admin"): ?>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-time-to-prepare"><?php echo e(__('Accept')); ?></button>
                <a href="<?php echo e(url('updatestatus/rejected_by_restaurant/'.$order->id)); ?>" class="btn btn-danger"><?php echo e(__('Reject')); ?></a>
            <?php elseif($lastStatusAlisas == "assigned_to_driver"||$lastStatusAlisas == "accepted_by_restaurant"||$lastStatusAlisas == "accepted_by_driver"||$lastStatusAlisas == "rejected_by_restaurant"): ?>
                <a href="<?php echo e(url('updatestatus/prepared/'.$order->id)); ?>" class="btn btn-primary"><?php echo e(__('Prepared')); ?></a>
            <?php elseif($lastStatusAlisas == "accepted_by_restaurant"): ?>
                <a href="<?php echo e(url('updatestatus/prepared/'.$order->id)); ?>" class="btn btn-primary"><?php echo e(__('Prepared')); ?></a>
            <?php elseif(config('app.allow_self_deliver')&&$lastStatusAlisas == "prepared"): ?>
                <a href="<?php echo e(url('updatestatus/delivered/'.$order->id)); ?>" class="btn btn-primary"><?php echo e(__('Delivered')); ?></a>
            <?php elseif($lastStatusAlisas == "prepared"&&$order->delivery_method.""=="2"): ?>
                <a href="<?php echo e(url('updatestatus/delivered/'.$order->id)); ?>" class="btn btn-primary"><?php echo e(__('Delivered')); ?></a>
            <?php else: ?>
                <p><?php echo e(__('No actions for you right now!')); ?></p>
            <?php endif; ?>
        <?php else: ?>
        
            <?php if($lastStatusAlisas == "just_created"): ?>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-time-to-prepare"><?php echo e(__('Accept')); ?></button>
                <a href="<?php echo e(url('updatestatus/rejected_by_restaurant/'.$order->id)); ?>" class="btn btn-danger"><?php echo e(__('Reject')); ?></a>
            <?php elseif($lastStatusAlisas == "accepted_by_restaurant"): ?>
                <a href="<?php echo e(url('updatestatus/prepared/'.$order->id)); ?>" class="btn btn-primary"><?php echo e(__('Prepared')); ?></a>
            <?php elseif($lastStatusAlisas == "prepared"): ?>
                <a href="<?php echo e(url('updatestatus/delivered/'.$order->id)); ?>" class="btn btn-primary"><?php echo e(__('Delivered')); ?></a>
            <?php elseif($lastStatusAlisas == "delivered"): ?>
                <a href="<?php echo e(url('updatestatus/closed/'.$order->id)); ?>" class="btn btn-danger"><?php echo e(__('Close')); ?></a>
            <?php elseif($lastStatusAlisas == "updated"): ?>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-time-to-prepare"><?php echo e(__('Accept')); ?></button>
                <a href="<?php echo e(url('updatestatus/rejected_by_restaurant/'.$order->id)); ?>" class="btn btn-danger"><?php echo e(__('Reject')); ?></a>
            <?php else: ?>
                <p><?php echo e(__('No actions for you right now!')); ?></p>
            <?php endif; ?>

        <?php endif; ?>

    <?php endif; ?>
    <?php if(auth()->user()->hasRole('driver')): ?>
        <?php if($lastStatusAlisas == "prepared"): ?>
            <a href="<?php echo e(url('updatestatus/picked_up/'.$order->id)); ?>" class="btn btn-primary"><?php echo e(__('Picked Up')); ?></a>
        <?php elseif($lastStatusAlisas == "picked_up"): ?>
            <a href="<?php echo e(url('updatestatus/delivered/'.$order->id)); ?>" class="btn btn-primary"><?php echo e(__('Delivered')); ?></a>
        <?php else: ?>
            <p><?php echo e(__('No actions for you right now!')); ?></p>
        <?php endif; ?>
    <?php endif; ?>
    </nav>
</div>
<?php endif; ?>
<?php /**PATH /home/laravel/web/ae.yummy-hunt.com/public_html/resources/views/orders/partials/actions/buttons.blade.php ENDPATH**/ ?>