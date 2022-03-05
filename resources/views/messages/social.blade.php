<?php
    $dnl="\n\n";
    $nl="\n";
    $tabSpace="      ";
?>
{{ __("Hi, I'd like to place an order")." 👇"}}
{{$nl}}
{{$order->restorant->name}}
{{$nl}}
@if ($order->delivery_method==1)
🛵🔜🏡 {{"*".__('Order No').": ".$order->id."*"}}
@else
✅🏫 {{"*".__('Order No').": ".$order->id."*"}} 
@endif 
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
} ?> 🔘{{ $lineprice }}
{{$nl}}
<?php
}
?>
🧾 {{__('Total: ').money($order->order_price, config('settings.cashier_currency'), config('settings.do_convertion')) }} 
------------------------------------------
@if($order->delivery_method==1)
📍 {{ __('Delivery Details') }} {{ __('Client').": ".$order->client_name }} {{ __('Address').": ".$order->whatsapp_address }} {{ __('Delivery time').": ".$order->getTimeFormatedAttribute() }}
@endif

{{ $order->restorant->getLinkAttribute()}}