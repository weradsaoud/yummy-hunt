<?php

namespace App\Http\Controllers\API\Dealont;

use App\Categories;
use App\Http\Controllers\Controller;
use App\Restorant;
use App\Items;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function get_Restaurant_Categories_items(Request $request)
    {
        $fields = $request->validate([
            'restaurant_id' => 'required|integer'
        ]);
        //make sure this restaurant exists in DB
        $restaurant = Restorant::where('id', $fields['restaurant_id'])->first();
        if ($restaurant) {
            $restaurantCategories = $restaurant->categories; //TODO pagination if needed
            //$restaurantCategories_names = $restaurantCategories->pluck('name');
            $categories_items = [];
            foreach ($restaurantCategories as $category) {
                // get category items
                $items = $category->aitems;
                $items_projected = $items->map(function ($item, $key) {
                    return [
                        'item_id' => $item->id,
                        'item_name' => $item->name ? $item->name : 'unknown name',
                        'item_image' => $item->getLogomAttribute(),
                        'item_price' => $item->price ? $item->getItempriceAttribute($item->price) : -1000,
                        'item_original_price' => $item->original_price ? $item->getItemoriginalpriceAttribute($item->original_price) : -1000,
                        'item_discount_percent' => $item->discount_percent ? $item->discount_percent : -1000
                    ];
                });
                $category_items = [
                    'category_id' => $category->id,
                    'category_name' => $category->name,
                    'category_items' => $items_projected
                ];
                array_push($categories_items, $category_items);
            }
            $response = [
                'success' => true,
                'data' => $categories_items
            ];
            return response($response, 200);
        } else {
            $response = [
                'success' => false,
                'code' => 10,
                'msg' => 'Restaurant does not exist.'
            ];
            return response($response, 500);
        }
    }

    public function get_item_details(Request $request)
    {
        $fields = $request->validate([
            'item_id' => 'required|integer'
        ]);
        //check the existance of the item.
        $item = Items::where('id', $fields['item_id'])->first();
        if ($item) {
            if ($item->available) {
                $item_options = $item->options->map(function ($option) {
                    return [
                        'option_id' => $option->id,
                        'option_name' => $option->name,
                        'option_options' => $option->options,
                    ];
                });
                $item_extras = $item->extras->map(function ($extra) {
                    return [
                        'extra_id' => $extra->id,
                        'extra_name' => $extra->name,
                        'extra_price' => $extra->getExtrapriceAttribute()
                    ];
                });
                $response = [
                    'success' => true,
                    'data' => [
                        'item_id' => $item->id,
                        'item_name' => $item->name ? $item->name : 'unknown name',
                        'item_image' => $item->getIconAttribute(),
                        'item_price' => $item->price ? $item->getItempriceAttribute($item->price) : -1000,
                        'item_original_price' => $item->original_price ? $item->getItemoriginalpriceAttribute($item->original_price) : -1000,
                        'item_discount_percent' => $item->discount_percent ? $item->discount_percent : -1000,
                        'item_options' => $item_options,
                        'item_extras' => $item_extras
                    ]
                ];
                return response($response, 200);
            } else {
                $response = [
                    'success' => false,
                    'code' => 30,
                    'msg' => 'Item is not available.'
                ];
                return response($response, 500);
            }
        } else {
            $response = [
                'success' => false,
                'code' => 20,
                'msg' => 'Item does not exist.'
            ];
            return response($response, 500);
        }
    }

    public function post_order(Request $request)
    {
        $fields = $request->validate([
            "client_id" => "integer",
            "restaurant_id" => "required|integer",
            "order_price" => "required|integer",
            "lat" => "string",
            "lng" => "string",
            "client_phone" => "string",
            "client_whatsup_address" => "string",
            "items" => "required|array|min:1",
            "items.*.item_id" => "required|integer",
            "items.*.qty" => "required|integer",
            "items.*.variant_price" => "required|integer",
            "items.*.extras" => "array",
            "items.*.extras.*" => "string"
        ]);
    }
}
