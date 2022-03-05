<div class="card card-profile shadow mt--300 ">
    <div class="px-4">
      <div class="mt-5">
        <h3><?php echo e(__('Items')); ?><span class="font-weight-light"></span></h3>
      </div>
        <!-- List of items -->
        <div  id="cartList" class="border-top">
            <br />
            <div  v-for="item in items" class="items col-xs-12 col-sm-12 col-md-12 col-lg-12 clearfix">
                <div class="info-block block-info clearfix" v-cloak>
                    <div class="square-box pull-left">
                    <figure>
                        <img :src="item.attributes.image" :data-src="item.attributes.image"  class="productImage" width="100" height="105" alt="">
                    </figure>
                    </div>
                    <h6 class="product-item_title">{{ item.name }}</h6>
                    <p class="product-item_quantity">{{ item.quantity }} x {{ item.attributes.friendly_price }}</p>
                    <div class="row">
                        <button type="button" v-on:click="decQuantity(item.id)" :value="item.id" class="btn btn-outline-primary btn-icon btn-sm page-link btn-cart-radius">
                            <span class="btn-inner--icon btn-cart-icon"><i class="fa fa-minus"></i></span>
                        </button>
                        <button type="button" v-on:click="incQuantity(item.id)" :value="item.id" class="btn btn-outline-primary btn-icon btn-sm page-link btn-cart-radius">
                            <span class="btn-inner--icon btn-cart-icon"><i class="fa fa-plus"></i></span>
                        </button>
                        <button type="button" v-on:click="remove(item.id)"  :value="item.id" class="btn btn-outline-primary btn-icon btn-sm page-link btn-cart-radius">
                            <span class="btn-inner--icon btn-cart-icon"><i class="fa fa-trash"></i></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End List of items -->
    </div>
  

                <form id="order-form" role="form" method="post" action="<?php echo e(route('order.store')); ?>" autocomplete="off" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                  <div class="px-4">
                      <div class="mt-2">
                        <h3><?php echo e(__('Notes')); ?><span class="font-weight-light"></span></h3>
                      </div>
                      <div class="card-content border-top">
                        <br />
                        <div class="form-group<?php echo e($errors->has('comment') ? ' has-danger' : ''); ?>">
                            <textarea name="comment" id="comment" class="form-control<?php echo e($errors->has('comment') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__( 'Your comment here' )); ?> ..."></textarea>
                            <?php if($errors->has('comment')): ?>
                                <span class="invalid-feedback" role="alert">
                                    <strong><?php echo e($errors->first('comment')); ?></strong>
                                </span>
                            <?php endif; ?>
                        </div>
                      </div>
                    </div>
                    
                  
                
                <br />
                <?php if(!config('settings.social_mode')): ?>

                    <?php if(config('app.isft')&&count($timeSlots)>0): ?>
                    <!-- FOOD TIGER -->
                        <!-- Delivery method -->
                        <?php if($restorant->can_pickup == 1): ?>
                            <?php if($restorant->can_deliver == 1): ?>
                              <?php echo $__env->make('cart.delivery', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            <?php endif; ?>
                        <?php endif; ?>

                        <!-- Delivery time slot -->
                        <?php echo $__env->make('cart.time', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                        <!-- Delivery address -->
                        <div id='addressBox'>
                            <?php echo $__env->make('cart.address', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        </div>

                        <!-- Comment -->
                        <?php echo $__env->make('cart.comment', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                    <?php else: ?>

                      <!-- QRSAAS -->
                      
                      <!-- DINE IN OR TAKEAWAY -->
                      <?php if(config('settings.enable_pickup')): ?>
                          <?php echo $__env->make('cart.localorder.dineiintakeaway', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                          <!-- Takeaway time slot -->
                          <div class="takeaway_picker" style="display: none">
                              <?php echo $__env->make('cart.time', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                          </div>
                      <?php endif; ?>

                      <!-- LOCAL ORDERING -->
                      <?php echo $__env->make('cart.localorder.table', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


                      <!-- Local Order Phone -->
                      <?php echo $__env->make('cart.localorder.phone', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                      <!-- Comment -->
                      <?php echo $__env->make('cart.comment', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        

                    <?php endif; ?>
                <?php else: ?>
                    <!-- Social MODE -->

                    <?php if(count($timeSlots)>0): ?>
                        <!-- Delivery method -->
                        <!--<?php echo $__env->make('cart.delivery', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>-->

                        <!-- Delivery time slot -->
                        <!--<?php echo $__env->make('cart.time', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>-->

                        <!-- Client Info -->
                        <!--<?php echo $__env->make('cart.client', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>-->
                 

                        <!-- Delivery adress -->
                        <!--<?php echo $__env->make('cart.newaddress', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>-->

                        <!-- Comment -->
                        <!--<?php echo $__env->make('cart.comment', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>-->
                    <?php endif; ?>
                <?php endif; ?>
                <?php if(count($timeSlots)>0): ?>
                <!-- Payment -->
              
    <div class="px-4">
      <div class="mt-1">
        <h3><?php echo e(__('Checkout')); ?><span class="font-weight-light"></span></h3>
      </div>
      <div  class="border-top">
        <!-- Price overview -->
        <div id="totalPrices" v-cloak>
            <div class="card card-stats mb-1 mb-xl-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <span v-if="totalPrice==0"><?php echo e(__('Cart is empty')); ?>!</span>

                            <span v-if="totalPrice"><strong><?php echo e(__('Subtotal')); ?>:</strong></span>
                            <span v-if="totalPrice" class="ammount"><strong>{{ totalPriceFormat }}</strong></span>
                            <?php if(config('app.isft')): ?>
                                <span v-if="totalPrice&&delivery"><br /><strong><?php echo e(__('Delivery')); ?>:</strong></span>
                                <span v-if="totalPrice&&delivery" class="ammount"><strong>{{ deliveryPriceFormated }}</strong></span><br />
                            <?php endif; ?>
                            <br />
                            <span v-if="totalPrice"><strong><?php echo e(__('TOTAL')); ?>:</strong></span>
                            <span v-if="totalPrice" class="ammount"><strong>{{ withDeliveryFormat   }}</strong></span>
                            <input v-if="totalPrice" type="hidden" id="tootalPricewithDeliveryRaw" :value="withDelivery" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End price overview -->

        <!-- Payment  Methods -->
        <div class="cards">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <!-- Errors on Stripe -->
                        <?php if(session('error')): ?>
                            <div role="alert" class="alert alert-danger"><?php echo e(session('error')); ?></div>
                        <?php endif; ?>

                        <?php if(!config('settings.is_whatsapp_ordering_mode')): ?>
                        <!-- COD -->
                        <?php if(!config('settings.hide_cod')): ?>
                            <div class="custom-control custom-radio mb-3">
                                <input name="paymentType" class="custom-control-input" id="cashOnDelivery" type="radio" value="cod" <?php echo e(config('settings.default_payment')=="cod"?"checked":""); ?>>
                                <label class="custom-control-label" for="cashOnDelivery"><span class="delTime"><?php echo e(config('app.isqrsaas')?__('Cash / Card Terminal'): __('Cash on delivery')); ?></span> <span class="picTime"><?php echo e(__('Cash on pickup')); ?></span></label>
                            </div>
                        <?php endif; ?>

                        <?php if($enablePayments): ?>

                            <!-- STIPE CART -->
                            <?php if(config('settings.stripe_key')&&config('settings.enable_stripe')): ?>
                                <div class="custom-control custom-radio mb-3">
                                    <input name="paymentType" class="custom-control-input" id="paymentStripe" type="radio" value="stripe" <?php echo e(config('settings.default_payment')=="stripe"?"checked":""); ?>>
                                    <label class="custom-control-label" for="paymentStripe"><?php echo e(__('Pay with card')); ?></label>
                                </div>
                            <?php endif; ?>

                            <!-- PayPal -->
                            <?php if(config('settings.paypal_secret')&&config('settings.enable_paypal')): ?>
                                <div class="custom-control custom-radio mb-3">
                                    <input name="paymentType" class="custom-control-input" id="paymentPayPal" type="radio" value="paypal" <?php echo e(config('settings.default_payment')=="paypal"?"checked":""); ?>>
                                    <label class="custom-control-label" for="paymentPayPal"><?php echo e(__('Pay with PayPal')); ?></label>
                                </div>
                            <?php endif; ?>

                            <!-- PAYFAST -->
                            <?php if(config('settings.enable_paystack')): ?>
                                <div class="custom-control custom-radio mb-3">
                                    <input name="paymentType" class="custom-control-input" id="paymentPaystack" type="radio" value="paystack" <?php echo e(config('settings.default_payment')=="paystack"?"checked":""); ?>>
                                    <label class="custom-control-label" for="paymentPaystack"><?php echo e(__('Pay with Paystack')); ?></label>
                                </div>
                            <?php endif; ?>

                            <!-- Mollie -->
                            <?php if(config('settings.enable_mollie')): ?>
                                <div class="custom-control custom-radio mb-3">
                                    <input name="paymentType" class="custom-control-input" id="paymentMollie" type="radio" value="mollie" <?php echo e(config('settings.default_payment')=="mollie"?"checked":""); ?>>
                                    <label class="custom-control-label" for="paymentMollie"><?php echo e(__('Pay with Mollie')); ?></label>
                                </div>
                            <?php endif; ?>

                        <?php endif; ?>

                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- END Payment -->

        <!-- Payment Actions -->
        <?php if(!config('settings.social_mode')): ?>

            <!-- COD -->
            <?php echo $__env->make('cart.payments.cod', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

            <!-- PayPal -->
            <?php if(config('settings.enable_paypal')): ?>
                <?php echo $__env->make('cart.payments.paypal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php endif; ?>

            <!-- Paystack -->
            <?php if(config('settings.enable_paystack')): ?>
                <?php echo $__env->make('cart.payments.paystack', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php endif; ?>

            <!-- Mollie -->
            <?php if(config('settings.enable_mollie')): ?>
                <?php echo $__env->make('cart.payments.mollie', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php endif; ?>

           

            <!-- Stripe -->
            <?php echo $__env->make('cart.payments.stripe', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

            

        <?php elseif(config('settings.is_whatsapp_ordering_mode')): ?>
            <?php echo $__env->make('cart.payments.whatsapp', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php elseif(config('settings.is_facebook_ordering_mode')): ?>
            <?php echo $__env->make('cart.payments.facebook', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endif; ?>
        <!-- END Payment Actions -->
 </form>
        <br/><br/>
        <div class="text-center">
            <div class="custom-control custom-checkbox mb-3">
                <input class="custom-control-input" id="privacypolicy" type="checkbox">
                <label class="custom-control-label" for="privacypolicy"><?php echo e(__('I agree to the Terms and Conditions and Privacy Policy')); ?></label>
            </div>
        </div>

      </div>
      <br />
      <br />
    </div>

  <?php if(config('settings.is_demo') && config('settings.enable_stripe')): ?>
    <?php echo $__env->make('cart.democards', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
  <?php endif; ?>

                <!--
                  <br/>
                  <?php echo $__env->make('cart.coupons', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                -->
            <?php else: ?>
                <!-- Closed restaurant -->
               
    <div class="px-4">
      <div class="mt-2">
        <h3><?php echo e(__('Checkout')); ?><span class="font-weight-light"></span></h3>
      </div>
      <div  class="border-top">
        <br />
        <div class="alert alert-danger" role="alert">
           <?php echo e(__('Order can not be placed since restaurant will be / is closed.')); ?>

        </div>
      </div>
      <br />
      <br />
    </div>

  <?php if(config('settings.is_demo') && config('settings.enable_stripe')): ?>
    <?php echo $__env->make('cart.democards', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>    
  <?php endif; ?>

            <?php endif; ?>

            </form>

</div>
<br />
<?php /**PATH /home/sites/9a/f/f4e2e25701/public_html/ae/resources/views/cart/combined.blade.php ENDPATH**/ ?>