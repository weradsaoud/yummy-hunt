<div class="card card-profile shadow" id="addressBox">
    <div class="px-4">
      <div class="mt-5">
        <h3><?php echo e(__('Delivery Info')); ?><span class="font-weight-light"></span></h3>
      </div>
      <div class="card-content border-top">
        <br />
        <?php echo $__env->make('partials.fields',['fields'=>[
          ['ftype'=>'input','name'=>"",'id'=>"addressID",'placeholder'=>"Your delivery address here ...",'required'=>true]
        ]], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
      </div>
      <br />
      <br />
    </div>
</div>
<?php /**PATH /home/laravel/web/im.yummy-hunt.com/public_html/resources/views/cart/newaddress.blade.php ENDPATH**/ ?>