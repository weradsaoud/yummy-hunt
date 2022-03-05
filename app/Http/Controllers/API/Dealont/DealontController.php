<?php namespace App\Http\Controllers\API\Dealont; use App\City;use Illuminate\Support\Facades\Log; use App\Models\AppSetting; use App\Models\Cuisine; use App\Models\Feature; use App\Models\RestaurantCategory; use App\Models\Slider; use App\Restorant; use
Illuminate\Http\Request; class DealontController {
    public function homeInit(Request $request)
    {
        $sliders = Slider::where('active', 'Y')->get()->pluck('image');
        $categories = RestaurantCategory::withCount("restorants")->where('active', 'Y')->get();
        $locations = City::withCount("restorants")->where('active', 'Y')->orderByDesc('id')->get()->makeHidden(['created_at', 'updated_at']);
        $recent_posts = Restorant::with(['category'])->orderByDesc('id')->get()->makeHidden(['created_at', 'updated_at'])->take(10);
        return response()->json([
            'success' => true,
            'data' => [
                'sliders' => $sliders,
                'categories' => $categories,
                'locations' => $locations,
                'recent_posts' => $recent_posts,
            ],
        ]);
    }
    public function settingInit(Request $request)
    {
        $settings = AppSetting::where('active', 'Y')->get()->makeHidden(['id', 'created_at', 'updated_at']);
        $place_sort_option = [
            [
                "title" => "Latest Post",
                "field" => "post_date",
                "lang_key" => "post_date_desc",
                "value" => "DESC",
            ],
            [
                "title" => "Oldest Post",
                "field" => "post_date",
                "lang_key" => "post_date_desc",
                "value" => "ASC",
            ],
            [
                "title" => "Most Views",
                "field" => "comment_count",
                "lang_key" => "comment_count_desc",
                "value" => "DESC",
            ],
        ];
        //VERIFY: withCount for restaurant undereach category using laravel 8.0
        $categories = RestaurantCategory::withCount("restorants")->where('active', 'Y')->get()->makeHidden(['created_at', 'updated_at', 'active']);

        $cuisines = Cuisine::withCount('restorants')->where('active', 'Y')->get()->makeHidden(['created_at', 'updated_at']);
        $features = Feature::withCount('restorants')->where('active', 'Y')->get()->makeHidden(['created_at', 'updated_at']);
        //VERIFY: withCount for restaurant undereach City using laravel 8.0
        $locations = City::withCount("restorants")->where('active', 'Y')->orderByDesc('id')->get()->makeHidden(['created_at', 'updated_at']);
        return response()->json([
            'success' => true,
            'data' => [
                'categories' => $categories,
                'cuisines' => $cuisines,
                'features' => $features,
                'locations' => $locations,
                'settings' => $settings,
                'place_sort_option' => $place_sort_option,
            ],
        ]);
    }
    public function locationList(Request $request)
    {
        //VERIFY: withCount for restaurant undereach City using laravel 8.0
        $locations = City::withCount("restorants")->where('active', 'Y')->orderByDesc('id')->get()->makeHidden(['created_at', 'updated_at', 'active']);
        return response()->json([
            'success' => true,
            'data' => $locations,
        ]);
    }
    public function categoryList(Request $request)
    {
        //VERIFY: withCount for restaurant undereach category using laravel 8.0
        $categories = RestaurantCategory::withCount("restorants")->where('active', 'Y')->get()->makeHidden(['created_at', 'updated_at', 'active']);
        return response()->json([
            'success' => true,
            'data' => $categories,
        ]);
    }
    public function placeList(Request $request)
    {
        $per_page = @$request->per_page ? (int) @$request->per_page : 20;
        $places = (new Restorant)->newQuery();
        if (@$request->has('s')) {
            $places->orWhere('name', 'like', '%' . $request->input("s") . '%')->orWhere('name', 'like', '%' . $request->input("s") . "%");
        }
        if(@$request->has('location')){

            $places->where('city_id',$request->input('location'));
        }
        if(@$request->has('category')){
          	Log::alert($request->input('category'));
       		$places->whereIn('restaurant_category_id',$request->input('category'));
        }
      
      	 if(@$request->has('cuisines')){
          	Log::alert($request->input('cuisines'));
       		$places->whereHas('cuisines',function($query) use ($request) {
                $query->whereIn('cuisines.id',$request->input('cuisines'));
               });
        }
      
        $places_data = $places->with('category')->where('active', '1')->orderByDesc('id')->paginate($per_page);
        return response()->json([
            "success" => true,
            "pagination" => [
                "page" => $places_data->currentPage(),
                "per_page" => $places_data->perPage(),
                "max_page" => $places_data->lastPage(),
                "total" => $places_data->total(),
            ],
            "data" => $places_data->items(),
        ]);
    }
    public function placeView(Request $request)
    {
        $restaurant = Restorant::with(['category','cuisines','features','user'])->findOrFail($request->id);
        if ($restaurant) {
            return response()->json([
                "success" => true,
                "data" => $restaurant,
            ]);
        } else {
            return response()->json([
                "success" => false,
                "data" => [
                    "status" => "404"
                ],
                "error" => "Not Found"
                ]);
        }
    }
}
