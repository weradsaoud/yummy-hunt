<br />
<h6 class="heading-small text-muted mb-4"><?php echo e(__('WhatsApp number')); ?></h6>
<!-- Payment explanation and Mollie payments -->
<?php echo $__env->make('partials.fields',['fields'=>[
    ['required'=>false,'ftype'=>'input','name'=>'Whatsapp phone', 'placeholder'=>'Whatsapp phone to receive orders on', 'id'=>'whatsapp_phone', 'value'=>$restorant->whatsapp_phone],
]], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>  <?php /**PATH /home/sites/9a/f/f4e2e25701/public_html/ae/resources/views/restorants/partials/waphone.blade.php ENDPATH**/ ?>