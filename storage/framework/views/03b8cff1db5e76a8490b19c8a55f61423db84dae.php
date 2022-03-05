<?php $__env->startSection('content'); ?>
   <section class="section">
    <div class="container">
        <br /><br />
        <div class="alert alert-danger" role="alert">
            Install is ok. But looks like you are running the site under subdomain. 
        </div>
        <p>
            When you run the site in subdomain, you need to declare that subdomain in the .env file<br /><br />
        <pre>IGNORE_SUBDOMAINS="www,<?php echo e($subdomain); ?>"</pre><br /><br />
        <a href="https://mobidonia.gitbook.io/mresto/docs/environment-configuration#7-subdomain" type="button" class="btn btn-success">Read more in the docs</a>
        </p>
    </div>
   </section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.front', ['class' => ''], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/sites/9a/f/f4e2e25701/public_html/ae/resources/views/restorants/alertdomain.blade.php ENDPATH**/ ?>