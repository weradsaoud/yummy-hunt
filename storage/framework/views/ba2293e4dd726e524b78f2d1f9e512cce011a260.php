<?php
    $dnl="\n\n";
    $nl="\n";
    $tabSpace="      ";
?>
<?php echo e(__("Hi, I'd like to place an order")." ๐"); ?>

<?php echo e($nl); ?>

<?php echo e($order->restorant->name); ?>

<?php echo e($nl); ?>

<?php if($order->delivery_method==1): ?>
๐ต๐๐ก <?php echo e("*".__('Order No').": ".$order->id."*"); ?>

<?php else: ?>
โ๐ซ <?php echo e("*".__('Order No').": ".$order->id."*"); ?> 
<?php endif; ?> 
-------------------------------------
Items:
<?php
foreach ($order->items()->get() as $key => $item)
{ $lineprice = $item->pivot->qty.' X '.$item->name." - ".money($item->pivot->qty * $item->pivot->variant_price, config('settings.cashier_currency'), true);
if(strlen($item->pivot->variant_name)>3)
{ $lineprice .=$nl.$tabSpace.__('Variant:')." ".$item->pivot->variant_name;
} 
if(strlen($item->pivot->extras)>3){
foreach (json_decode($item->pivot->extras) as $key => $extra) {
$lineprice .=$nl.$tabSpace.$extra; 
} 
} ?> ๐<?php echo e($lineprice); ?>

<?php echo e($nl); ?>

<?php
}
?>
๐งพ <?php echo e(__('Total: ').money($order->order_price, config('settings.cashier_currency'), config('settings.do_convertion'))); ?> 
------------------------------------------
<?php if($order->delivery_method==1): ?>
๐ <?php echo e(__('Delivery Details')); ?> <?php echo e(__('Client').": ".$order->client_name); ?> <?php echo e(__('Address').": ".$order->whatsapp_address); ?> <?php echo e(__('Delivery time').": ".$order->getTimeFormatedAttribute()); ?>

<?php endif; ?>

<?php echo e($order->restorant->getLinkAttribute()); ?><?php /**PATH /home/sites/9a/f/f4e2e25701/public_html/resources/views/messages/social.blade.php ENDPATH**/ ?>