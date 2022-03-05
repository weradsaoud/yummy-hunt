<?php

namespace App;

use App\MyModel;
use App\Traits\HasConfig;
use willvincent\Rateable\Rateable;
use Illuminate\Support\Facades\DB;
use App\Models\Variants;
class Restorant extends MyModel
{
    use Rateable;
    use HasConfig;

    protected $modelName="App\Restorant";
    protected $fillable = ['name', 'subdomain', 'user_id', 'lat', 'lng', 'address', 'phone', 'logo', 'description', 'city_id'];
    protected $appends = ['alias', 'logom', 'icon', 'coverm'];
    protected $imagePath = '/uploads/restorants/';

    protected $casts = [
        'radius' => 'array',
    ];

    protected $attributes = [
        'radius' => '{}',
    ];

    /**
     * Get the user that owns the restorant.
     */
    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function getAliasAttribute()
    {
        return $this->subdomain;
    }

    public function getLinkAttribute()
    {
        if (config('settings.wildcard_domain_ready')){
            //As subdomain
            return (isset($_SERVER['HTTPS'])&&$_SERVER["HTTPS"] ?"https://":"http://").$this->subdomain.".".str_replace($this->subdomain.".","",str_replace("www.","",$_SERVER['HTTP_HOST']));
        }else{
            //As link
            return route('vendor',$this->subdomain);
        }
    }

    public function getLogomAttribute()
    {
        return $this->getImge($this->logo, config('global.restorant_details_image'));
    }

    public function getIconAttribute()
    {
        return $this->getImge($this->logo, str_replace('_large.jpg', '_thumbnail.jpg', config('global.restorant_details_image')), '_thumbnail.jpg');
    }

    public function getCovermAttribute()
    {
        return $this->getImge($this->cover, config('global.restorant_details_cover_image'), '_cover.jpg');
    }

    public function categories()
    {
        return $this->hasMany(\App\Categories::class, 'restorant_id', 'id')->where(['categories.active' => 1]);
    }

    public function localmenus()
    {
        return $this->hasMany(\App\Models\LocalMenu::class, 'restaurant_id', 'id');
    }

    public function hours()
    {
        return $this->hasOne(\App\Hours::class, 'restorant_id', 'id');
    }

    public function tables()
    {
        return $this->hasMany(\App\Tables::class, 'restaurant_id', 'id');
    }

    public function areas()
    {
        return $this->hasMany(\App\RestoArea::class, 'restaurant_id', 'id');
    }

    public function visits()
    {
        return $this->hasMany(\App\Visit::class, 'restaurant_id', 'id');
    }

    public function orders()
    {
        return $this->hasMany(\App\Order::class, 'restorant_id', 'id');
    }

    public function coupons()
    {
        return $this->hasMany(\App\Coupons::class, 'restaurant_id', 'id');
    }



    //Mobile_App_API
    public function restaurantOrdersRatings()
    {
        return $this->hasManyThrough(\App\Ratings::class, \App\Order::class);
    }
    public function restaurantItems()
    {
        return $this->hasManyThrough(\App\Items::class, \App\Categories::class, 'restorant_id', 'category_id', 'id', 'id');
    }
    public function getRestaurantRating()
    {
        return $this->restaurantOrdersRatings()->avg('rating');
    }
    public function getRestaurantRatingCount()
    {
        return $this->restaurantOrdersRatings()->count();
    }
    public function getMinPrice()
    {
        $minPriceItem = $this->restaurantItems()->min('price');
        $minPriceVariant = Variants::whereIn('item_id', function ($q) {
            $q->select('id')->from('items')->whereIn('category_id', function ($q2) {
                $q2->select('id')->from('categories')->where('restorant_id', $this->id);
            });
        })->min('price');
        if ($minPriceItem) {
            if ($minPriceVariant) {
                return ($minPriceItem < $minPriceVariant) ? $minPriceItem : $minPriceVariant;
            } else {
                return $minPriceItem;
            }
        } else {
            return 0;
        }
    }
    public function getMaxPrice()
    {
        if ($this->restaurantItems && !$this->restaurantItems->isEmpty()) {
            $maxPriceItem = DB::select('SELECT id, MAX(price)as p FROM items WHERE category_id IN( SELECT id FROM categories WHERE restorant_id = ?)', [$this->id]);
            $maxPriceVariant = DB::select('SELECT id, MAX(price)as p FROM variants WHERE item_id IN(SELECT id FROM items WHERE category_id IN( SELECT id FROM categories WHERE restorant_id = ?))', [$this->id]);

            if ($maxPriceVariant[0]->p) {
                if ($maxPriceItem[0]->p > $maxPriceVariant[0]->p) {
                    $extrasSumPrice = DB::select('SELECT SUM(price) as s from extras where item_id = ?', [$maxPriceItem[0]->id]);
                    if ($extrasSumPrice[0]->s)
                        return $extrasSumPrice[0]->s + $maxPriceItem[0]->p;
                    else
                        return $maxPriceItem[0]->p;
                } else {
                    $extrasSumPrice = DB::select('SELECT SUM(price) as s from extras where id IN(select extra_id from variants_has_extras where variant_id = ?)', [$maxPriceItem[0]->id]);
                    if ($extrasSumPrice[0]->s)
                        return $extrasSumPrice[0]->s + $maxPriceVariant[0]->p;
                    else
                        return $maxPriceVariant[0]->p;
                }
            } else {
                $extrasSumPrice = DB::select('SELECT SUM(price) as s from extras where item_id = ?', [$maxPriceItem[0]->id]);
                if ($extrasSumPrice[0]->s)
                    return $extrasSumPrice[0]->s + $maxPriceItem[0]->p;
                else
                    return $maxPriceItem[0]->p;
            }
        } else {
            return 0;
        }
    }
    public function getHours()
    {
        $hours = $this->hours;
        if($hours){
            $shift0_from = $hours->shift0_from;
            $shift0_to = $hours->shift0_to;
            $shift1_from = $hours->shift1_from;
            $shift1_to = $hours->shift1_to;
            $shift2_from = $hours->shift2_from;
            $shift2_to = $hours->shift2_to;
            $shift3_from = $hours->shift3_from;
            $shift3_to = $hours->shift3_to;
            $shift4_from = $hours->shift4_from;
            $shift4_to = $hours->shift4_to;
            $shift5_from = $hours->shift5_from;
            $shift5_to = $hours->shift5_to;
            $shift6_from = $hours->shift6_from;
            $shift6_to = $hours->shift6_to;
            return [
                [
                    'key' => 'first shift',
                    'schedule' => [
                        'start' => $shift0_from,
                        'end' => $shift0_to
                    ]
                ],
                [
                    'key' => 'second shift',
                    'schedule' => [
                        'start' => $shift1_from,
                        'end' => $shift1_to
                    ]
                ],
                [
                    'key' => '3rd shift',
                    'schedule' => [
                        'start' => $shift2_from,
                        'end' => $shift2_to
                    ]
                ],
                [
                    'key' => '4th shift',
                    'schedule' => [
                        'start' => $shift3_from,
                        'end' => $shift3_to
                    ]
                ],
                [
                    'key' => '5th shift',
                    'schedule' => [
                        'start' => $shift4_from,
                        'end' => $shift4_to
                    ]
                ],
                [
                    'key' => '6th shift',
                    'schedule' => [
                        'start' => $shift5_from,
                        'end' => $shift5_to
                    ]
                ],
                [
                    'key' => '7th shift',
                    'schedule' => [
                        'start' => $shift6_from,
                        'end' => $shift6_to
                    ]
                ],
            ];
        }else{
            return null;
        }
        
    }
    //Mobile_App_API

    public static function boot()
    {
        parent::boot();
        self::deleting(function (self $restaurant) {
            if (config('settings.is_demo')) {
                return false; //In demo disable deleting
            } else {
                //Delete orders
                foreach ($restaurant->orders()->get() as $order) {
                    //Delete Order items
                    //Delete Oders statuses
                    $order->delete();
                }

                //Delete Categories
                foreach ($restaurant->categories()->get() as $category) {
                    $category->delete();
                    //Delete items
                        //Delete extras
                        //Delete Options
                        //Deletee Options
                }

                //Delete Hours
                $restaurant->hours()->forceDelete();

                //Delete Tables
                $restaurant->tables()->forceDelete();

                //Delete Restoareas
                $restaurant->areas()->forceDelete();

                //Delete Visits to this restaruant
                $restaurant->visits()->forceDelete();

                //Delete Local menus
                $restaurant->localmenus()->forceDelete();

                return true;
            }
        });
    }

    public function category(){
        return $this->belongsTo(\App\Models\RestaurantCategory::class,'restaurant_category_id');
    }


    public function cuisines()
    {
        return $this->belongsToMany(\App\Models\Cuisine::class, \App\Models\RestorantHasCuisine::class);
    }

    public function features()
    {
        return $this->belongsToMany(\App\Models\Feature::class, \App\Models\RestorantHasFeature::class);
    }

}
