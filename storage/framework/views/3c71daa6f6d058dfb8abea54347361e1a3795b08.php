<?php
    $dnl="\n\n";
    $nl="\n";
    $tabSpace="      ";
?>
<?php echo e(__("Hi, I'd like to place an order")." 👇"); ?>

<?php echo e($nl); ?>

<?php echo e($order->restorant->name); ?>

<?php echo e($nl); ?>

<?php if($order->delivery_method==1): ?>
🛵🔜🏡 <?php echo e("*".__('Order No').": ".$order->id."*"); ?>

<?php else: ?>
✅🏫 <?php echo e("*".__('Order No').": ".$order->id."*"); ?> 
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
} ?> 🔘<?php echo e($lineprice); ?>

<?php echo e($nl); ?>

<?php
}
?>
🧾 <?php echo e(__('Total: ').money($order->order_price, config('settings.cashier_currency'), config('settings.do_convertion'))); ?> 
------------------------------------------
<?php if($order->delivery_method==1): ?>
📍 <?php echo e(__('Delivery Details')); ?> <?php echo e(__('Client').": ".$order->client_name); ?> <?php echo e(__('Address').": ".$order->whatsapp_address); ?> <?php echo e(__('Delivery time').": ".$order->getTimeFormatedAttribute()); ?>

<?php endif; ?>

<?php echo e($order->restorant->getLinkAttribute()); ?><?php /**PATH C:\xampp\htdocs\syr.yummy-hunt.com\resources\views/messages/social.blade.php ENDPATH**/ ?>