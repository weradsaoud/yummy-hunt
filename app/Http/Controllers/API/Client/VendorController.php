<?php

namespace App\Http\Controllers\API\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\City;
use App\Restorant as Restaurant;
use App\Address;
use App\Country;
use App\Review;
use App\Token;
use App\Items as Item;

class VendorController extends Controller
{
    public function getCities()
    {
        $cities = City::where('id', '>', 1)->get();

        if ($cities) {
            return response()->json([
                'data' => $cities,
                'status' => true,
                'errMsg' => '',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errMsg' => 'Cities not found!',
            ]);
        }
    }

    public function getVendors($city_id = 'none')
    {
        if ($city_id == 'none') {
            $restaurants = Restaurant::where(['active'=>1])->get();
        } else {
            $restaurants = Restaurant::where(['active'=>1])->where(['city_id'=>$city_id])->get();
        }

        if ($restaurants) {
            return response()->json([
                'data' => $restaurants,
                'status' => true,
                'errMsg' => '',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errMsg' => 'Vendor not found!',
            ]);
        }
    }

    public function getVendorItems($id)
    {
        $restorant = Restaurant::where(['id' => $id, 'active' => 1])->with(['categories.items.variants.extras'])->first();
        $items = [];
        if ($restorant) {
            if ($restorant->categories) {
                foreach ($restorant->categories as $key => $category) {
                    $theItemsInCategory = $category->items;
                    $catBox = [];
                    foreach ($theItemsInCategory as $key => $item) {
                        $itemObj = $item->toArray();
                        $itemObj['category_name'] = $category->name;
                        $itemObj['extras'] = $item->extras->toArray();
                        $itemObj['options'] = $item->options->toArray();
                        $itemObj['variants'] = $item->variants->toArray();
                        array_push($catBox, $itemObj);
                    }
                    array_push($items, $catBox);
                }

                return response()->json([
                    'data' => $items,
                    'status' => true,
                    'errMsg' => '',
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'errMsg' => 'Vendor categories not found!',
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'errMsg' => 'Vendor not found!',
            ]);
        }
    }

    public function getVendorHours($restorantID)
    {

        //Create all the time slots
        //The restaurant
        $restaurant = Restaurant::findOrFail($restorantID);
        //dd($restaurant->hours);

        $timeSlots = $restaurant->hours ? $this->getTimieSlots($restaurant->hours->toArray()) : [];

        //Modified time slots for app
        $timeSlotsForApp = [];
        foreach ($timeSlots as $key => $timeSlotsTitle) {
            array_push($timeSlotsForApp, ['id'=>$key, 'title'=>$timeSlotsTitle]);
        }

        //Working hours
        $ourDateOfWeek = date('N') - 1;

        $format = 'G:i';
        if (config('settings.time_format') == 'AM/PM') {
            $format = 'g:i A';
        }

        //dd($ourDateOfWeek);
        //dd($restaurant->hours[$ourDateOfWeek.'_from']);

        $openingTime = date($format, strtotime($restaurant->hours[$ourDateOfWeek.'_from']));
        $closingTime = date($format, strtotime($restaurant->hours[$ourDateOfWeek.'_to']));

        $params = [
            'restorant' => $restaurant,
            'timeSlots' => $timeSlotsForApp,
            'openingTime' => $restaurant->hours && $restaurant->hours[$ourDateOfWeek.'_from'] ? $openingTime : null,
            'closingTime' => $restaurant->hours && $restaurant->hours[$ourDateOfWeek.'_to'] ? $closingTime : null,
         ];

        if ($restaurant) {
            return response()->json([
                'data' => $params,
                'status' => true,
                'errMsg' => '',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errMsg' => 'Restaurant not found!',
            ]);
        }
    }

    public function getDeliveryFee($restaurant_id, $address_id)
    {
        $restaurant = Restaurant::findOrFail($restaurant_id);
        $addresss = Address::findOrFail($address_id);
        $addresses = $this->getAccessibleAddresses($restaurant, [$addresss]);
        //dd($addresses[$address_id]);
        return response()->json([
            'fee' => $addresses[$address_id]->cost_total,
            'inRadius' => $addresses[$address_id]->inRadius,
            'address'=>$addresses[$address_id],
            'status' => true,
            'errMsg' => '',
        ]);
    }
     public function setDiscount(Request $request,$item_id,$discount)
    {
         $item = Item::findOrFail($item_id);
        if(@$request->api_key !== "cjDtmn6nP1mV7o7r" && @$request->api_key !==  $item->category->restorant->user->api_token){
             return response()->json([
                    'status' => false,
                    'errMsg' => 'UnAuthenticated',
                ]);
        }

        if($item){

        $item->discount_percent = @$discount ?  @$discount:0.0 ;
        if(!$item->original_price)
        $item->original_price = $item->price;
        $item->price = number_format(($item->original_price - (($item->original_price * $item->discount_percent ) /100 )),2);
        $item->save();
        //dd($addresses[$address_id]);
        return response()->json([
            'item'=>$item,
            'status' => true,
            'errMsg' => '',
        ]);
        } else{
            return response()->json([
            'status' => false,
            'errMsg' => 'Item Not Found',
        ]);
        }
    }

    public function setSelectedDiscount(Request $request,$item_ids,$notDiscount,$discount){
        $ids  = explode(',', $item_ids);
        $ids2  = explode(',', $notDiscount);
         $item = Item::findOrFail($ids[0]);
        if(@$request->api_key !== "cjDtmn6nP1mV7o7r" && @$request->api_key !==  $item->category->restorant->user->api_token){
             return response()->json([
                    'status' => false,
                    'errMsg' => 'UnAuthenticated',
                ]);
        }

        if($item){
            $items1 = Item::whereIn('id',$ids)->get();
           foreach($items1 as $item2){
               $item1 = Item::find($item2->id);
                $item1->discount_percent = @$discount ?  @$discount:0.0 ;
                if(!$item1->original_price)
                $item1->original_price = $item1->price;
                $item1->price = number_format(($item1->original_price - (($item1->original_price * $item1->discount_percent ) /100 )),2);
                $item1->save();
           }
            $items2 = Item::whereIn('id',$ids2)->get();
           foreach($items2 as $item3){
               $item4 = Item::find($item3->id);
                $item4->discount_percent = 0.0 ;
                if($item4->original_price)
                $item4->price = $item4->original_price;
                $item4->original_price = null;
                $item4->save();
           }
        //Item::whereNotIn('id',$ids)->update(['discount_percent' => 0.0]);
        //dd($addresses[$address_id]);
        return response()->json([
            'item'=>$item,
            'status' => true,
            'errMsg' => '',
        ]);
        } else{
            return response()->json([
            'status' => false,
            'errMsg' => 'Item Not Found',
        ]);
        }
    }

    public function setDiscountByIndex(Request $request,$id,$cat_id,$index,$discount)
    {
        //dd($addresses[$address_id]);
         $restorant = Restaurant::where(['id' => $id, 'active' => 1])->with(['categories.items.variants.extras'])->first();

        if(@$request->api_key !==  $restorant->user->api_token && @$request->api_key !==  $restorant->user->api_token){

                 return response()->json([
                    'status' => false,
                    'errMsg' => 'UnAuthenticated',
                    'auth_id' => $restorant->user->api_token
                ]);

        }
        // else if(@$request->api_key !== "cjDtmn6nP1mV7o7r"){
        //      return response()->json([
        //             'status' => false,
        //             'errMsg' => 'UnAuthenticated',
        //             'api_key' =>$restorant->user->api_token
        //         ]);
        // }
       $items = [];
        if ($restorant) {
            if ($restorant->categories) {
                foreach ($restorant->categories as $key => $category) {
                    $theItemsInCategory = $category->items;
                    if($category->id == $cat_id){

                        $item2 = Item::findOrFail($theItemsInCategory[$index]->id);
                        $itemObj = $item2->toArray();
                        if($item2){
                        $item2->discount_percent = @$discount ?  @$discount:0.0 ;
                        if(!$item2->original_price)
                        $item2->original_price = $item2->price;
                        $item2->price = number_format(($item2->original_price - (($item2->original_price * $item2->discount_percent ) /100 )),2);
                        $item2->save();
                         array_push($items, $item2);
                        }
                    }
                }


                return response()->json([
                    'data' => $items,
                    'status' => true,
                    'errMsg' => '',
                ]);

            } else {
                return response()->json([
                    'status' => false,
                    'errMsg' => 'Vendor categories not found!',
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'errMsg' => 'Vendor not found!',
            ]);
        }
    }

     public function setAllDiscount(Request $request,$id,$discount)
    {

        //dd($addresses[$address_id]);
         $restorant = Restaurant::with('user')->where(['id' => $id, 'active' => 1])->with(['categories.items.variants.extras'])->first();


        if(@$request->api_key != "cjDtmn6nP1mV7o7r" && @$request->api_key !==  $restorant->user->api_token){
             return response()->json([
                    'status' => false,
                    'errMsg' => 'UnAuthenticated',
                    'api_key' =>$restorant->user->api_token,
                     'req_id' => @$request->api_key,
                      'result' => @$request->api_key ===  $restorant->user->api_token
                ]);
        }
        // else if(){

        //          return response()->json([
        //             'status' => false,
        //             'errMsg' => 'UnAuthenticated',
        //             'auth_id' => $restorant->user->api_token,
        //             'req_id' => @$request->api_key,
        //             'result' => @$request->api_key ===  $restorant->user->api_token
        //         ]);

        // }
       $items = [];
        if ($restorant) {
            if ($restorant->categories) {
                foreach ($restorant->categories as $key => $category) {
                    $theItemsInCategory = $category->items;
                    $catBox = [];
                    foreach ($theItemsInCategory as $key => $item) {
                        $itemObj = $item->toArray();
                        $item2 = Item::findOrFail($item->id);
                        if($item2){
                        $item2->discount_percent = @$discount ?  @$discount:0.0 ;
                        if(!$item2->original_price)
                        $item2->original_price = $item2->price;
                        $item2->price = number_format(($item->original_price - (($item2->original_price * $item2->discount_percent ) /100 )),2);
                        $item2->save();
                        }
                        $itemObj['category_name'] = $category->name;
                        $itemObj['extras'] = $item->extras->toArray();
                        $itemObj['options'] = $item->options->toArray();
                        $itemObj['variants'] = $item->variants->toArray();
                        array_push($catBox, $itemObj);
                    }
                    array_push($items, $catBox);
                }

                return response()->json([
                    'data' => $items,
                    'status' => true,
                    'errMsg' => '',
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'errMsg' => 'Vendor categories not found!',
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'errMsg' => 'Vendor not found!',
            ]);
        }
    }

    public function getCountries(){
        $countries = [
            'UAE',
            'Malaysia',
            'USA',
            'UK',
            'Turkey'
            ];

        $domains = [
            'uae' => 'ae.dealont.com',
            'malaysia' => 'Malaysia',
            'usa' => 'dealont.com',
            'uk' => 'dealont.com',
            'turkey' => 'dealont.com',
            ];
        return response()->json(['countries' => $countries,'domains' => $domains]);
    }

     public function getCountriesFromDB(){


        $domains = Country::where("active","Y")->get();
        return response()->json(['domains' => $domains]);
    }


     public function getTokens(Request $request){
        // return 1;
        //return json_encode($request->city);
        $tokens = Token::where("city","=",$request->city)->where("active","Y")->get();



            // $args = [
            //     'timeout' => 45,
            //     'redirection' => 5,
            //     'httpversion' => '1.1',
            //     'method' => 'POST',
            //     'body' => json_encode([
            //         'registration_ids' => $tokens2,
            //         'notification' => $notification,
            //         'data' => [],
            //         'priority' => 'high'
            //     ]),
            //     'sslverify' => false,
            //     'headers' => [
            //         'Content-Type' => 'application/json',
            //         'Authorization' => 'key=AAAAt8_eU48:APA91bH2ANlzUQ_bFyuL0YJDJ0AiYEmCFZ7ijrLidYxf9GTBmuYWC11yBkMMr_w3Vu53qO3Uwa2PRUlYMRcPSpzBNCkT74T5ZTPyU8kH_KcCFP9DFwqHUsuoUJXrsQUpUNEjWEDMZBEp',
            //     ],
            //     'cookies' => []
            // ];

        // return $tokens;
        return json_encode($tokens);
    }
    public function sendNotifications(Request $request){
        if($request->domain){
            $tokens = Token::where("city","=",$request->city)->where("domain","=",$request->domain)->where("active","Y")->get('push_token');
        }else{
            $tokens = Token::where("city","=",$request->city)->where("active","Y")->get('push_token');
        }

        //   $tokens2 = [];
        foreach($tokens as $token){
            $tokens2[] = $token->push_token;
        }

          $notification = [
                'title' => $request->title,
                'body' =>$request->body,
                'sound' => 'default',
                'badge' => '1',
            ];

            $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'key=AAAAt8_eU48:APA91bH2ANlzUQ_bFyuL0YJDJ0AiYEmCFZ7ijrLidYxf9GTBmuYWC11yBkMMr_w3Vu53qO3Uwa2PRUlYMRcPSpzBNCkT74T5ZTPyU8kH_KcCFP9DFwqHUsuoUJXrsQUpUNEjWEDMZBEp',
        ];



        $endpoint = "https://fcm.googleapis.com/fcm/send?key=AAAAt8_eU48:APA91bH2ANlzUQ_bFyuL0YJDJ0AiYEmCFZ7ijrLidYxf9GTBmuYWC11yBkMMr_w3Vu53qO3Uwa2PRUlYMRcPSpzBNCkT74T5ZTPyU8kH_KcCFP9DFwqHUsuoUJXrsQUpUNEjWEDMZBEp";
        $client = new \GuzzleHttp\Client([
                'headers' => $headers
            ]);

        $response = $client->request('POST', $endpoint, [
                    'body' => json_encode([
                        'registration_ids' => $tokens2,
                        'notification' => $notification,
                        'priority' => 'high'])
                    ]
                    );

        // url will be: http://my.domain.com/test.php?key1=5&key2=ABC;

        $statusCode = $response->getStatusCode();
        $content = $response->getBody();

        return json_encode($content);


    }

    public function addToken(Request $request){
        if($request->has('device_info')){
        $oldToken = Token::where('push_token',$request->push_token)->where('city',$request->city)->where('domain',$request->domain)->get();
        if(count($oldToken) > 0){
             $token = Token::find($oldToken[0]['id']);
        }else{
             $token = new Token;
        }

        $token->listar_user_id = $request->listar_user_id;
        $token->name = $request->name;
        $token->city = $request->city;
        $token->country = $request->country;
        $token->full_address = $request->full_address;
        $token->device_info = $request->device_info;
        $token->push_token = $request->push_token;
        $token->longitute = $request->longitute;
        $token->latitude = $request->latitude;
        $token->domain = $request->domain;
        //$token->longitute = $request->longitute;
        //$token->longitute = $request->longitute;
        $token->save();
        return 1;
        }else{
            return 0;
        }
    }

        public function getCitiesFromDomain(Request $request){
             $tokens = Token::where("domain","=",$request->domain)->groupBy('city')->get();
            return json_encode($tokens);
        }

     public function getReviews(Request $request){
        $oldReview = Review::where("active","Y")->where("restaurant_name",@$request->restaurant_name)->where("user_name",@$request->user_name)->orderBy('id','desc')->get();

        $reviews = Review::where("active","Y")->where("restaurant_name",$request->restaurant_name)->where("user_name","!=",@$request->user_name)->orderBy('id','desc')->get();
        if(count($oldReview) > 0){
            return response()->json(['reviews' => $reviews,'myReview' => $oldReview[0],'already' => true]);
        }else{
        return response()->json(['reviews' => $reviews,'already' => false]);
        }
    }
    public function postReview(Request $request) {
        $review =new Review;
        $review->restaurant_name = $request->restaurant_name;
        $review->user_name = $request->user_name;
        $review->comment = $request->comment;
        $review->rating = $request->rating;
        $review->save();
        return response()->json(['review' => $review,'success' => true]);

    }
}
