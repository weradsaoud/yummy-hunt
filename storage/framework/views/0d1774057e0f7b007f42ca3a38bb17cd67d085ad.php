<?php $__env->startSection('admin_title'); ?>
    <?php echo e(__('Share Menu')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('title'); ?>
<title><?php echo e($name); ?></title>
<?php $__env->stopSection(); ?>
<?php if(config('settings.share_this_property')): ?>
    <?php $__env->startSection('head'); ?>
        <script type="text/javascript" src="https://platform-api.sharethis.com/js/sharethis.js#property=<?php echo e(config('settings.share_this_property')); ?>&product=sticky-share-buttons" async="async"></script>
    <?php $__env->stopSection(); ?>
<?php endif; ?>

<?php $__env->startSection('content'); ?>
    <div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
    </div>
    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                <div class="card bg-secondary shadow">
                    <div class="card-body">
                        <h2 class="text-uppercase text-center text-muted mb-4"><?php echo e(__('Apply Discount To Restaurants')); ?></h2>

                        <div class="pl-lg-4">
                           <h3 class="text-uppercase text-center text-muted mb-4"><?php echo e(auth()->user()->restorant['name']); ?></h3>

                            <div id="discount-div">

                             <div class="row mt-3">
                                <div class="col-md-6">
                                    <label class="form-control-label"  for="diss"><?php echo e(__('Choose a Discount Category')); ?> :</label>
                                    <select name="diss" class=" form-control" id="diss">
                                           <option value=""><?php echo e(__('Select')); ?></option>
                                    </select>
                                    <button type="button" id="loading-res" class="btn btn-sm btn-primary"><?php echo e(__('Loading')); ?>...</button>
                                </div>
                            </div>
                            <div class="form-group  mt-3">
                               <div class="custom-control custom-checkbox">
                                <input type="hidden" value="false" name="dinein" id="dinein-text">
                                <input value="" type="checkbox" class="custom-control-input" name="dinein" id="dinein">
                                <label class="custom-control-label" for="dinein"><?php echo e(__("Dine in")); ?></label>
                                </div>
                             </div>
                             <div class="form-group  ">
                               <div class="custom-control custom-checkbox">
                                <input type="hidden" value="false" name="delivery" id="delivery-text">
                                <input value=""  type="checkbox" class="custom-control-input" name="delivery" id="delivery">
                                <label class="custom-control-label" for="delivery"><?php echo e(__('Delivery')); ?></label>
                                </div>
                             </div>
                             <div class="form-group  ">
                                <label class="form-control-label" for="excerpt"><?php echo e(__('Validity note')); ?></label>
                                <textarea maxlength="38"  placeholder="Character Limit 38" class="form-control" name="excerpt" id="excerpt"></textarea>
                             </div>
                             <div class="row mt-3">
                                <div class="col-md-6">
                                    <label class="form-control-label"   for="opts"><?php echo e(__('Apply to Selected Item')); ?>:</label>
                                    <input type="radio"  id="opts" checked name="opts" value="0" />
                                </div>
                                 <div class="col-md-6">
                                    <label class="form-control-label"  for="opts"><?php echo e(__('Apply to All Items')); ?>:</label>
                                    <input type="radio" id="opts" name="opts" value="1" />
                                </div>
                            </div>

                            <div class="row mt-3" id="cats-div" style="display:none;">
                                <div class="col-md-6">
                                    <label class="form-control-label"  for="cats"><?php echo e(__('Choose Food category')); ?>:</label>
                                    <select name="cats" class=" form-control" id="cats">
                                           <option value="">{__('Select')}}</option>
                                    </select>
                                </div>
                            </div>
                             <div class="row mt-3" id="items-div">
                                <div class="col-md-6">
                                    <label class="form-control-label"  for="items"><?php echo e(__('Choose an item')); ?>:</label>
                                    <select name="items[]" class="js-example-basic-multiple form-control" multiple="multiple" id="items">
                                           <option value=""><?php echo e(__('Select')); ?></option>
                                    </select>
                                        <button type="button" id="loading-res-1" class="btn btn-sm btn-primary"><?php echo e(__('Loading')); ?>...</button>

                                </div>
                            </div>

                            <div class="row mt-3 ml-1">
                               <button type="button" id="submit-btn" class="btn btn-sm btn-success"><?php echo e(__('Sync Data')); ?></button>
                             <button type="button" id="loading" class="btn btn-sm btn-primary"><?php echo e(__('Loading')); ?>...</button>

                            </div>
                            </div>
                             <button type="button"  class="btn btn-lg btn-success" id="checking" ><?php echo e(__('Loading, please be patient')); ?>....</button>


                    </div>
                    </div>
                </div>
            </div>
        </div>
        <?php echo $__env->make('layouts.footers.auth', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
<script>

    $(document).ready(function() {

        let res_id;
        let diss;
        let item_id;
        let opt;
        let discounts= [];
        let cat_id;
        let items = [];
        let itemsAll = [];

        $("#loading").hide();
        $("#loading-res").show();
        $("#loading-res-1").hide();
        $("#diss").hide();
        $("#checking").show();
        $("#discount-div").hide();


    $.getJSON("http://im.dealont.com/index.php/wp-json/listar/v1/category/list", function(result){
        console.log(result)
        $("#loading-res").hide();
        $("#diss").show();
        discounts = [...result?.data];
        discounts?.data?.forEach(e => {
            $("#diss").append(`
                <option value='${e?.term_id}'>${e?.name}</option>
            `);
        })
    });
    $.getJSON("http://im.dealont.com/index.php/wp-json/listar/v1/place/list?s=<?php echo e(auth()->user()->restorant['name']); ?>", function(result){
        console.log(result?.data)
        if(result?.data?.length > 0) {
            let res = result?.data[0];
            res_id = res?.ID;
            if(res?.post_title === "<?php echo e(auth()->user()->restorant['name']); ?>") {
                $.getJSON("http://im.dealont.com/index.php/wp-json/listar/v1/place/view?id="+res?.ID, function(result2){
                      console.log(result2?.data,"1223")
                     let res2 = result2?.data;
                     if(res2?.features?.length > 0){
                         let isdinein = res2?.features?.find(e => e.term_id == "106");
                         let isdelivery = res2?.features?.find(e => e.term_id == "35");
                         if(isdinein){
                              $("#dinein").prop('checked', true);
                         }
                          if(isdelivery){
                              $("#delivery").prop('checked', true);
                         }
                     }
                        setTimeout(() => {
                             if(res2?.category){
                              $("#diss").empty();
                              diss = res2?.category?.name.split("%")[0];
                      discounts?.forEach(e => {
                          $("#diss").append(`
                           <option ${res2?.category?.term_id === e?.term_id ?"selected":"" } value='${e?.term_id}'>${e?.name}</option>
                          `)});
                         }
                        },1000)
                      if(res2?.post_excerpt){
                             console.log(res2?.post_excerpt,"1278")
                             $("#excerpt").html(res2?.post_excerpt)
                         }

                    $("#checking").hide();
                    $("#discount-div").show();

                });

            }else{
                $("#checking").show();
                $("#checking").html("No match error");
                $("#discount-div").hide();
            }

        }else{
            $("#checking").show();
            $("#checking").html("No match error");
            $("#discount-div").hide();
        }

    });


    //http://im.dealont.com/index.php/wp-json/listar/v1/place/list?s=Restaurant 3

        $("#diss").change(e => {
            console.log(discounts)
            const val = discounts?.find(e2 => e2?.term_id == e.target.value);

            diss=val?.name.split("%")[0];
            cat_id = e.target.value;
            console.log(cat_id,val,"909979u89");
        })





        $("#submit-btn").hide();
         $("#loading").show();
        $.getJSON("http://im.yummy-hunt.com/api/v2/client/items/<?php echo e(auth()->user()->restorant['id']); ?>", function(result){
       console.log(result.data)
       $("#submit-btn").show();
         $("#loading").hide();
         $("#items").empty();
       $("#items").append(`
       <option value=''><?php echo e(__('Select')); ?></option>
      `)
      items=result?.data.flat(1);
        result?.data.flat(1)?.forEach((e,i) => {
      $("#items").append(`
       <option value='${e.id}'>${e?.name}</option>
      `)

        })
        $("#cats").empty();
         $("#cats").append(`
       <option value=''><?php echo e(__('Select')); ?></option>
      `)
        result?.data?.forEach((e,i) => {
      $("#cats").append(`
       <option value='${i}'>${e[0]?.category_name}</option>
      `)

    });
    })

     $("#cats").change(e => {
         $("#submit-btn").hide();
         $("#loading").show();
          $("#loading-res-1").show();
        $.getJSON("http://im.yummy-hunt.com/api/v2/client/items/<?php echo e(auth()->user()->restorant['id']); ?>", function(result){
             console.log(result.data)
             $("#submit-btn").show();
         $("#loading").hide();
          $("#loading-res-1").hide();
          items = [...result?.data[e.target.value]];
      $("#items").empty();
       $("#items").append(`
       <option value=''><?php echo e(__('Select')); ?></option>
      `)
        result?.data[e.target.value]?.forEach((e,i) => {
        $("#items").append(`
           <option value='${e.id}'>${e?.name}</option>
        `);

        })
    });
    })

    $("#items").change(e => {
        item_id = e.target.value;
    });

   $('input[type=radio]').click(function(){
        console.log( $("#items").val(),"nknk")
        opt = this.value;
        console.log(this)
        if(opt == "1"){
            // $("#cats-div").hide();
            $("#items-div").hide();
        }else if(opt == "0"){
            //  $("#cats-div").show();
              $("#items-div").show();
        }
    })

    $("#submit-btn").click(async e => {
        e.preventDefault();
        let opt = $('input[type=radio]:checked').val();
        // Yummyhunt Discount
        //<?php echo e(auth()->user()->restorant['name']); ?>

        //https://yummyhunt.com/api/apply-discount/restaurant1/58
        console.log(`https://yummyhunt.com/api/apply-discount/<?php echo e(auth()->user()->restorant['name']); ?>/${diss}`,"1222");

        $.ajax({
            type: 'GET',
            dataType: 'jsonp',
            data: {},
            url: `https://yummyhunt.com/api/apply-discount/<?php echo e(auth()->user()->restorant['name']); ?>/${diss}`,
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR,"1234");
                if(opt == "0"){
                //   perItem(item_id,diss);
                    perSelectedItem(diss);
                }
                else if(opt == "1"){
                   bulk(res_id,diss);
                }
            },
            success: function (result) {
                console.log(result,"989");
              if(opt == "0"){
                //   perItem(item_id,diss);
                perSelectedItem(diss)
                }else if(opt == "1"){
                   bulk(res_id,diss)
                }
            }
        });




    });

    // Bulk
    function perItem(item_id,diss) {

        const api_key ='<?php echo e(auth()->user()->api_token); ?>';
         console.log(item_id,diss,api_key,"99y98j")
         $("#submit-btn").hide();
         $("#loading").show();
        $.getJSON(`http://im.yummy-hunt.com/api/v2/client/discount/${item_id}/${diss}?api_key=${api_key}`, function(result){
      console.log(result)
       listarSave();
    });
    }

    function perSelectedItem(diss) {

        const api_key ='<?php echo e(auth()->user()->api_token); ?>';
         console.log(item_id,diss,api_key,"99y98j")
         $("#submit-btn").hide();
         $("#loading").show();
         console.log( $("#items").val(),"nknk")
         let items1 = $("#items").val().join(",");

         let items2 = items?.map( e => e?.id).filter(e2 => {
             return $("#items").val().includes(e2+"") ? false: true
         }).join(',');

         console.log($("#items").val())
         console.log(`http://im.yummy-hunt.com/api/v2/client/selected-discount/${items1}/${items2}/${diss}?api_key=${api_key}`)
        $.getJSON(`http://im.yummy-hunt.com/api/v2/client/selected-discount/${items1}/${items2}/${diss}?api_key=${api_key}`, function(result){
     console.log(result)
       listarSave();
    });
    }



    function listarSave(){
        const dinein =   $("#dinein").prop('checked')?'106':'0';
        const delivery =   $("#delivery").prop('checked')?'35':'0';
        const excerpt = $("#excerpt").val();
        console.log($("#excerpt").val(),"909j9")
        console.log(`http://im.dealont.com/e-change.php?id=${res_id}&cat=${cat_id}&ft2=${delivery}&ft1=${dinein}&exc=${$("#excerpt").val()}`)
        $.ajax({
            type: 'GET',
            dataType: 'jsonp',
            data: {},
            url: `http://im.dealont.com/e-change.php?id=${res_id}&cat=${cat_id}&ft2=${delivery}&ft1=${dinein}&exc=${$("#excerpt").val()}`,
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR,"1234");
                  $("#submit-btn").show();
                     $("#loading").hide();
                      $("#submit-btn").html("Synced Successfully ");
                     setTimeout(()=>{
                           $("#submit-btn").html("Sync Data");
                     },2000)
            },
            success: function (result) {
                console.log(result,"989");
             if(result ===1){

                    $("#submit-btn").show();
                     $("#loading").hide();
                      $("#submit-btn").html("synced Successfully ");
                     setTimeout(()=>{
                           $("#submit-btn").html("Sync Data");
                     },2000)
                  }else{
                      $("#submit-btn").show();
                     $("#loading").hide();
                       $("#submit-btn").html("Listar Server Error. Please Try Again Later");
                     setTimeout(()=>{
                           $("#submit-btn").html("Sync Data");
                     },2000)
                  }
            }
        });



        //
    //      $.getJSON(`http://im.dealont.com/e-change.php?id=${res_id}&cat=${cat_id}&ft2=${delivery}&ft1=${dinein}&exc=${excerpt}`, function(result){
    //   console.log(result)
    //   if(result ===1){

    //     $("#submit-btn").show();
    //      $("#loading").hide();
    //       $("#submit-btn").html("Discount Successfully Applied");
    //      setTimeout(()=>{
    //           $("#submit-btn").html("Sync Data");
    //      },2000)
    //   }else{
    //       $("#submit-btn").show();
    //      $("#loading").hide();
    //       $("#submit-btn").html("Listar Server Error. Please Try Again Later");
    //      setTimeout(()=>{
    //           $("#submit-btn").html("Sync Data");
    //      },2000)
    //   }
    // });
    }

    // Bulk
    function bulk(rest_id,diss) {
          const api_key ='<?php echo e(auth()->user()->api_token); ?>';
         console.log(item_id,diss,api_key,"99y98j");
          $("#submit-btn").hide();
         $("#loading").show();
         console.log(`http://im.yummy-hunt.com/api/v2/client/discount-all/<?php echo e(auth()->user()->restorant['id']); ?>/${diss}?api_key=${api_key}`)

        $.getJSON(`http://im.yummy-hunt.com/api/v2/client/discount-all/<?php echo e(auth()->user()->restorant['id']); ?>/${diss}?api_key=${api_key}`, function(result){
      console.log(result)
      listarSave();
    });
    }
});

    </script>
    <?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', ['title' => __('Share Menu')], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/laravel/web/im.yummy-hunt.com/public_html/resources/views/restorants/applydiscount.blade.php ENDPATH**/ ?>