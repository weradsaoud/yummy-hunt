<?php if(strlen(config('settings.recaptcha_site_key'))>2): ?>
    <?php $__env->startSection('head'); ?>
    <?php echo htmlScriptTagJsApi([]); ?>

    <?php $__env->stopSection(); ?>
<?php endif; ?>

 

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('users.partials.header', [
        'title' => "",
    ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

       
    <div class="container-fluid mt--7">
        
        <div class="row">
 <div class="dropdown  ml-4 mb-4">
                <a href="#" class="btn btn-neutral dropdown-toggle " data-toggle="dropdown" id="navbarDropdownMenuLink2">
                    <?php echo e($currentLanguage); ?>

                </a>
                <ul class="dropdown-menu" aria-labelledby="">
                    <li>
                        <a class="dropdown-item" href="?lang=en">
                          English
                        </a>
                    </li>
                     <li>
                        <a class="dropdown-item" href="?lang=ar">
                            Arabic
                        </a>
                    </li>
                </ul>
        </div>
            </div>
            <div class="col-xl-8 offset-xl-2">
                <div class="card bg-secondary shadow">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <h3 class="col-12 mb-0"><?php echo e(__('Register your restaurant')); ?></h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <form  id="registerform" method="post" action="<?php echo e(route('newrestaurant.store')); ?>" autocomplete="off">
                            <?php echo csrf_field(); ?>

                            <h6 class="heading-small text-muted mb-4"><?php echo e(__('Restaurant information')); ?></h6>
                         <?php if($errors->has('RecaptchError')): ?>
                                       <div class="alert alert-success alert-dismissible fade show" role="alert">
                                  <?php echo e($errors->first('RecaptchError')); ?>

                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                    <?php endif; ?>
                            <?php if(session('status')): ?>
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <?php echo e(session('status')); ?>

                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            <?php endif; ?>
                            <div class="pl-lg-4">
                                <div class="form-group<?php echo e($errors->has('name') ? ' has-danger' : ''); ?>">
                                    <label class="form-control-label" for="name"><?php echo e(__('Restaurant Name')); ?></label>
                                    <input type="text" name="name" id="name" class="form-control form-control-alternative<?php echo e($errors->has('name') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Restaurant Name here')); ?> ..." value="<?php echo e(isset($_GET["name"])?$_GET['name']:""); ?>" required autofocus>
                                    <?php if($errors->has('name')): ?>
                                        <span class="invalid-feedback" role="alert">
                                            <strong><?php echo e($errors->first('name')); ?></strong>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <hr class="my-4" />
                            <h6 class="heading-small text-muted mb-4"><?php echo e(__('Owner information')); ?></h6>
                            <div class="pl-lg-4">
                                <div class="form-group<?php echo e($errors->has('name_owner') ? ' has-danger' : ''); ?>">
                                    <label class="form-control-label" for="name_owner"><?php echo e(__('Owner Name')); ?></label>
                                    <input type="text" name="name_owner" id="name_owner" class="form-control form-control-alternative<?php echo e($errors->has('name_owner') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Owner Name here')); ?> ..." value="<?php echo e(isset($_GET["name"])?$_GET['name']:""); ?>" required autofocus>

                                    <?php if($errors->has('name_owner')): ?>
                                        <span class="invalid-feedback" role="alert">
                                            <strong><?php echo e($errors->first('name_owner')); ?></strong>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <div class="form-group<?php echo e($errors->has('email_owner') ? ' has-danger' : ''); ?>">
                                    <label class="form-control-label" for="email_owner"><?php echo e(__('Owner Email')); ?></label>
                                    <input type="email" name="email_owner" id="email_owner" class="form-control form-control-alternative<?php echo e($errors->has('email_owner') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Owner Email here')); ?> ..." value="<?php echo e(isset($_GET["email"])?$_GET['email']:""); ?>" required autofocus>

                                    <?php if($errors->has('email_owner')): ?>
                                        <span class="invalid-feedback" role="alert">
                                            <strong><?php echo e($errors->first('email_owner')); ?></strong>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <div class="form-group<?php echo e($errors->has('phone_owner') ? ' has-danger' : ''); ?>">
                                    <label class="form-control-label" for="phone_owner"><?php echo e(__('Owner Phone')); ?></label>
                                    <input type="text" name="phone_owner" id="phone_owner" class="form-control form-control-alternative<?php echo e($errors->has('phone_owner') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Owner Phone here')); ?> ..." value="<?php echo e(isset($_GET["phone"])?$_GET['phone']:""); ?>" required autofocus>

                                    <?php if($errors->has('phone_owner')): ?>
                                        <span class="invalid-feedback" role="alert">
                                            <strong><?php echo e($errors->first('phone_owner')); ?></strong>
                                        </span>
                                    <?php endif; ?>
                                </div>
                               <div class="form-check">
                                  <input class="form-check-input" type="checkbox"  id="tandd">
                                  <label class="form-check-label" for="tandd">
                                    <a href="https://dealont.com/privacy-policy/" style="color:blue;"><?php echo e(__('Accept Terms and Conditions')); ?></a>
                                  </label>
                                </div>
      

                                <div class="text-center">
                                    <?php if(strlen(config('settings.recaptcha_site_key'))>2): ?>
                                        <?php if($errors->has('g-recaptcha-response')): ?>
                                        <span class="invalid-feedback" role="alert">
                                            <strong><?php echo e($errors->first('g-recaptcha-response')); ?></strong>
                                        </span>
                                        <?php endif; ?>

                                        <?php echo htmlFormButton(__('Save'), ['id'=>'thesubmitbtn','class' => 'btn btn-success mt-4']); ?>

                                    <?php else: ?>
                                        <button type="button" data-sitekey="6Lf61aYcAAAAAKqrSMe_ex_OmMyeZonzy8Yxaprm" data-callback='onSubmit' id="thesubmitbtn" class="btn btn-success mt-4 g-recaptcha"><?php echo e(__('Save')); ?></button>
                                        <button type="submit" style="display:none" id="fSubmit"><?php echo e(__('Save')); ?></button>

                                    <?php endif; ?>


                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <br/>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
     <script>
       function onSubmit(token) {
         $("input[name='g_recaptcha_response']").remove();
        $('#registerform').prepend('<input type="hidden" name="g_recaptcha_response" value="' + token + '">');
        //  document.getElementById("registerform").submit();
         console.log(document.getElementById('tandd').checked);
         if(document.getElementById('tandd').checked)
         $("#fSubmit").click();
         else
         alert("<?php echo e(__('Please Accept Terms and Conditions')); ?>")
        // alert("Hi")
       }
      
     </script>

<?php if(isset($_GET['name'])&&$errors->isEmpty()): ?>
<script>
    "use strict";
    document.getElementById("thesubmitbtn").click();
</script>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.front', ['title' => __('User Profile')], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/sites/9a/f/f4e2e25701/public_html/ae/resources/views/restorants/register.blade.php ENDPATH**/ ?>