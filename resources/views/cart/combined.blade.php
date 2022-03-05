<div class="card card-profile shadow mt--300 ">
    <div class="px-4">
      <div class="mt-5">
        <h3>{{ __('Items') }}<span class="font-weight-light"></span></h3>
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
                    <h6 class="product-item_title">@{{ item.name }}</h6>
                    <p class="product-item_quantity">@{{ item.quantity }} x @{{ item.attributes.friendly_price }}</p>
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


                <form id="order-form" role="form" method="post" action="{{route('order.store')}}" autocomplete="off" enctype="multipart/form-data">
                @csrf
                  <div class="px-4 hidden">
                      <div class="mt-2">
                        <h3>{{ __('Notes') }}<span class="font-weight-light"></span></h3>
                      </div>
                      <div class="card-content border-top">
                        <br />
                        <div class="form-group{{ $errors->has('comment') ? ' has-danger' : '' }}">
                            <textarea name="comment" id="comment" class="form-control{{ $errors->has('comment') ? ' is-invalid' : '' }}" placeholder="{{ __( 'Your comment here' ) }} ..."></textarea>
                            @if ($errors->has('comment'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('comment') }}</strong>
                                </span>
                            @endif
                        </div>
                      </div>
                    </div>



                <br />
                @if(!config('settings.social_mode'))

                    @if (config('app.isft')&&count($timeSlots)>0)
                    <!-- FOOD TIGER -->
                        <!-- Delivery method -->
                        @if($restorant->can_pickup == 1)
                            @if($restorant->can_deliver == 1)
                              @include('cart.delivery')
                            @endif
                        @endif

                        <!-- Delivery time slot -->
                        @include('cart.time')

                        <!-- Delivery address -->
                        <div id='addressBox'>
                            @include('cart.address')
                        </div>

                        <!-- Comment -->
                        @include('cart.comment')

                    @else

                      <!-- QRSAAS -->

                      <!-- DINE IN OR TAKEAWAY -->
                      @if (config('settings.enable_pickup'))
                          @include('cart.localorder.dineiintakeaway')
                          <!-- Takeaway time slot -->
                          <div class="takeaway_picker" style="display: none">
                              @include('cart.time')
                          </div>
                      @endif

                      <!-- LOCAL ORDERING -->
                      @include('cart.localorder.table')


                      <!-- Local Order Phone -->
                      @include('cart.localorder.phone')

                      <!-- Comment -->
                      @include('cart.comment')


                    @endif
                @else
                    <!-- Social MODE -->

                    @if(count($timeSlots)>0)
                        <!-- Delivery method -->
                        <!--@include('cart.delivery')-->

                        <!-- Delivery time slot -->
                        <!--@include('cart.time')-->

                        <!-- Client Info -->
                        <!--@include('cart.client')-->


                        <!-- Delivery adress -->
                        <!--@include('cart.newaddress')-->

                        <!-- Comment -->
                        <!--@include('cart.comment')-->
                    @endif
                @endif
                @if (count($timeSlots)>0)
                <!-- Payment -->

    <div class="px-4">
      <div class="mt-1">
        <h3>{{ __('Checkout') }}<span class="font-weight-light"></span></h3>
      </div>
      <div  class="border-top">
        <!-- Price overview -->
        <div id="totalPrices" v-cloak>
            <div class="card card-stats mb-1 mb-xl-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <span v-if="totalPrice==0">{{ __('Cart is empty') }}!</span>

                            <span v-if="totalPrice"><strong>{{ __('Subtotal') }}:</strong></span>
                            <span v-if="totalPrice" class="ammount"><strong>@{{ totalPriceFormat }}</strong></span>
                            @if(config('app.isft'))
                                <span v-if="totalPrice&&delivery"><br /><strong>{{ __('Delivery') }}:</strong></span>
                                <span v-if="totalPrice&&delivery" class="ammount"><strong>@{{ deliveryPriceFormated }}</strong></span><br />
                            @endif
                            <br />
                            <span v-if="totalPrice"><strong>{{ __('TOTAL') }}:</strong></span>
                            <span v-if="totalPrice" class="ammount"><strong>@{{ withDeliveryFormat   }}</strong></span>
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
                        @if (session('error'))
                            <div role="alert" class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        @if(!config('settings.is_whatsapp_ordering_mode'))
                        <!-- COD -->
                        @if (!config('settings.hide_cod'))
                            <div class="custom-control custom-radio mb-3">
                                <input name="paymentType" class="custom-control-input" id="cashOnDelivery" type="radio" value="cod" {{ config('settings.default_payment')=="cod"?"checked":""}}>
                                <label class="custom-control-label" for="cashOnDelivery"><span class="delTime">{{ config('app.isqrsaas')?__('Cash / Card Terminal'): __('Cash on delivery') }}</span> <span class="picTime">{{ __('Cash on pickup') }}</span></label>
                            </div>
                        @endif

                        @if($enablePayments)

                            <!-- STIPE CART -->
                            @if (config('settings.stripe_key')&&config('settings.enable_stripe'))
                                <div class="custom-control custom-radio mb-3">
                                    <input name="paymentType" class="custom-control-input" id="paymentStripe" type="radio" value="stripe" {{ config('settings.default_payment')=="stripe"?"checked":""}}>
                                    <label class="custom-control-label" for="paymentStripe">{{ __('Pay with card') }}</label>
                                </div>
                            @endif

                            <!-- PayPal -->
                            @if (config('settings.paypal_secret')&&config('settings.enable_paypal'))
                                <div class="custom-control custom-radio mb-3">
                                    <input name="paymentType" class="custom-control-input" id="paymentPayPal" type="radio" value="paypal" {{ config('settings.default_payment')=="paypal"?"checked":""}}>
                                    <label class="custom-control-label" for="paymentPayPal">{{ __('Pay with PayPal') }}</label>
                                </div>
                            @endif

                            <!-- PAYFAST -->
                            @if(config('settings.enable_paystack'))
                                <div class="custom-control custom-radio mb-3">
                                    <input name="paymentType" class="custom-control-input" id="paymentPaystack" type="radio" value="paystack" {{ config('settings.default_payment')=="paystack"?"checked":""}}>
                                    <label class="custom-control-label" for="paymentPaystack">{{ __('Pay with Paystack') }}</label>
                                </div>
                            @endif

                            <!-- Mollie -->
                            @if(config('settings.enable_mollie'))
                                <div class="custom-control custom-radio mb-3">
                                    <input name="paymentType" class="custom-control-input" id="paymentMollie" type="radio" value="mollie" {{ config('settings.default_payment')=="mollie"?"checked":""}}>
                                    <label class="custom-control-label" for="paymentMollie">{{ __('Pay with Mollie') }}</label>
                                </div>
                            @endif

                        @endif

                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- END Payment -->

        <!-- Payment Actions -->
        @if(!config('settings.social_mode'))

            <!-- COD -->
            @include('cart.payments.cod')

            <!-- PayPal -->
            @if(config('settings.enable_paypal'))
                @include('cart.payments.paypal')
            @endif

            <!-- Paystack -->
            @if(config('settings.enable_paystack'))
                @include('cart.payments.paystack')
            @endif

            <!-- Mollie -->
            @if(config('settings.enable_mollie'))
                @include('cart.payments.mollie')
            @endif



            <!-- Stripe -->
            @include('cart.payments.stripe')



        @elseif(config('settings.is_whatsapp_ordering_mode'))
            @include('cart.payments.whatsapp')
        @elseif(config('settings.is_facebook_ordering_mode'))
            @include('cart.payments.facebook')
        @endif
        <!-- END Payment Actions -->
 </form>
        <br/><br/>
        <div class="text-center">
            <div class="custom-control custom-checkbox mb-3">
                <input class="custom-control-input" id="privacypolicy" checked type="checkbox">
                <label class="custom-control-label" for="privacypolicy">{{ __('I agree to the Terms and Conditions and Privacy Policy') }}</label>
            </div>
        </div>

      </div>
      <br />
      <br />
    </div>

  @if(config('settings.is_demo') && config('settings.enable_stripe'))
    @include('cart.democards')
  @endif

                <!--
                  <br/>
                  @include('cart.coupons')
                -->
            @else
                <!-- Closed restaurant -->

    <div class="px-4">
      <div class="mt-2">
        <h3>{{ __('Checkout') }}<span class="font-weight-light"></span></h3>
      </div>
      <div  class="border-top">
        <br />
        <div class="alert alert-danger" role="alert">
           {{ __('Order can not be placed since restaurant will be / is closed.')}}
        </div>
      </div>
      <br />
      <br />
    </div>

  @if(config('settings.is_demo') && config('settings.enable_stripe'))
    @include('cart.democards')
  @endif

            @endif

            </form>

</div>
<br />
