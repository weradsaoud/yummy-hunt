<br />
<h6 class="heading-small text-muted mb-4"><?php echo e(__('Accepting Payments')); ?></h6>
<!-- Payment explanation and Mollie payments -->
<?php echo $__env->make('partials.fields',['fields'=>[
    ['required'=>false,'ftype'=>'input','placeholder'=>"Payment info",'name'=>'Payment info', 'additionalInfo'=>'ex. We accept cash on delivery and cash on pick up', 'id'=>'payment_info', 'value'=>$restorant->payment_info],
    ['required'=>false,'ftype'=>'input','placeholder'=>"Mollie Payment API key",'name'=>'Mollie Payment API key', 'additionalInfo'=>'Accept Mollie.com payment by entering the mollie api key', 'id'=>'mollie_payment_key', 'value'=>$restorant->mollie_payment_key],
    
    ['required'=>false,'ftype'=>'input','placeholder'=>"Paypal client id",'name'=>'Paypal client id', 'additionalInfo'=>'Accept paypal payment by entering the paypal client id', 'id'=>'paypal_client_id', 'value'=>$restorant->getConfig('paypal_client_id','')],
    ['required'=>false,'ftype'=>'input','placeholder'=>"Paypal secret",'name'=>'Paypal secret',  'id'=>'paypal_secret', 'value'=>$restorant->getConfig('paypal_secret','')],
    ['required'=>false,'ftype'=>'input','placeholder'=>"Paypal mode",'name'=>'Paypal mode',  'id'=>'paypal_mode', 'ftype'=>'select', 'data'=>['sandbox'=>'Development - sandbox', 'live'=>'Production - live'], 'value'=>$restorant->getConfig('paypal_mode','sandbox')],
]], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php if(config('settings.is_whatsapp_ordering_mode')): ?>
    <?php echo $__env->make('restorants.partials.waphone', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php endif; ?>



<?php /**PATH /home/laravel/web/ae.yummy-hunt.com/public_html/resources/views/restorants/partials/social_info.blade.php ENDPATH**/ ?>