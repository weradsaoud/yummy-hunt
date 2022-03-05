<?php

namespace App\Http\Controllers\API\Dealont;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AppUser;
use App\Restorant;

class WishController extends Controller
{
    public function addToWishList(Request $request)
    {
        $user = $request->user();
        $mobile_app_user = AppUser::where('email', $user->email)->first();
        if ($mobile_app_user) {
            $fields = $request->validate([
                'post_id' => 'required|integer'
            ]);
            $resturant = Restorant::where('id', $fields['post_id'])->first();
            if ($resturant) {
                $mobile_app_user->wishListResturants()->save($resturant);
                $response = [
                    'success' => true,
                    'data' => []
                ];
                return response($response, 200);
            } else {
                $response = [
                    'success' => false,
                    'data' => []
                ];
                return response($response, 500);
            }
        } else {
            $response = [
                'success' => false,
                'data' => []
            ];
            return response($response, 500);
        }
    }

    public function getWishList(Request $request)
    {
        $user = $request->user();
        $mobile_app_user = AppUser::where('email', $user->email)->first();
        if ($mobile_app_user) {
            $data = [];
            $user_wish_list = $mobile_app_user->wishListResturants()->paginate(5);
            //if (!empty($user_wish_list) && is_array($user_wish_list)) {}
            
            foreach ($user_wish_list as $resturant) {
                $user_photo = asset($user->photo);
                $data_item = [
                    'ID' => $resturant->id,
                    'post_title' => $resturant->name,
                    'author' => [
                        'id' => $mobile_app_user->id,
                        'display_name' => $mobile_app_user->name,
                        'user_nicename' => '',
                        'user_photo' => $user_photo,
                        'user_url' => 'url',
                        'user_level' => 'level',
                        'description' => 'user description',
                        'tag' => 'user tag',
                        'rate' => 'user rate',
                        'token' => $mobile_app_user->token,
                        'user_email' => $mobile_app_user->email
                    ],
                    'image' => [
                        'full' => [
                            'url' => $resturant->getLogomAttribute()
                        ],
                        'thumb' => [
                            'url' => $resturant->getIconAttribute()
                        ],
                    ],
                    'category' => [
                        'name' => 'fake Category name'
                    ],
                    'rating_avg' => $resturant->getRestaurantRating(),
                    'rating_count' => $resturant->getRestaurantRatingCount(),
                    'post_date' => $resturant->created_at,
                    'date_establish' => $resturant->pivot->created_at,
                    'post_status' => '', // todo
                    'status' => '', // todo
                    'wishlist' => true, // todo
                    'address' => $resturant->address,
                    'phone' => $resturant->phone,
                    'fax' => '',
                    'email' => '',
                    'website' => '',
                    'post_excerpt' => $resturant->description,
                    'price_min' => $resturant->getMinPrice(),
                    'price_max' => $resturant->getMaxPrice(),
                    'guid' => 'www.google.com',
                    'opening_hour' => $resturant->getHours(),
                    'galleries' => null,
                    'features' => null,
                    'related' => null,
                    'lastest' => null,
                    'latitude' => $resturant->lat,
                    'longitude' => $resturant->lng
                ];
                array_push($data, $data_item);
            }
            $pagination = [
                'page' => $user_wish_list->currentPage(),
                'per_page' => $user_wish_list->perPage(),
                'max_page' => $user_wish_list->lastPage(),
                'total' => $user_wish_list->total()
            ];
            $response = [
                'success' => true,
                'data' => $data,
                'pagination' => $pagination
            ];
            return response($response, 200);
        }
    }

    public function removeFromWishList(Request $request)
    {
        $user = $request->user();
        if ($user) {
            if ($request->has('post_id')) {
                $fields = $request->validate([
                    'post_id' => 'required|integer'
                ]);
                $restaurantId = $fields['post_id'];
                $user->wishListResturants()->detach($restaurantId);
                $response = [
                    'success' => true,
                    'data' => []
                ];
                return response($response, 200);
            } else {
                if (!$user->wishListResturants->isEmpty()) {
                    $user->wishListResturants()->detach();
                }
                $response = [
                    'success' => true,
                    'data' => []
                ];
                return response($response, 200);
            }
        } else {
            $response = [
                'success' => false,
                'message' => 'User does not Exist.'
            ];
            return response($response, 500);
        }
    }
}
