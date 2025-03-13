<?php

namespace App\Http\Controllers\Api\Website;

use Illuminate\Http\Request;
use App\Models\Product\Product;
use App\Models\Services\Service;
use App\Models\FrontPage\FrontPage;
use App\Http\Controllers\Controller;
use App\Models\Product\ProductCategory;
use App\Services\Product\ProductService;
use App\Services\FrontPage\FrontPageService;
use App\Services\Product\ProductImageService;
use App\Services\Product\ProductCategoryService;
use App\Http\Resources\Service\ServiceEditResource;
use App\Http\Resources\FrontPage\AllFrontPageResource;
use App\Http\Resources\FrontPage\Website\NavbarLinksSlugResource;
use App\Http\Resources\FrontPage\Website\FrontPageWebsiteResource;

class HomePageController extends Controller
{
    protected $frontPageService;

    public function __construct(FrontPageService $frontPageService)
    {
        $this->frontPageService = $frontPageService;
    }

    public function index( $slug=null ,$navbarLinks)
    {
    
        $locale = app()->getLocale();
        $homePage = FrontPage::where('controller_name', 'HomePageController')
            ->with(['sections.translations' => function ($query) use ($locale) {
                $query->where('locale', $locale);
            }])
            ->first();

        $services = Service::all();

        session(['active_navbar_link' => $slug??'']);

        // if($lang == 'ar'){
        //     session(['body_direction' => [
        //         'direction' => 'rtl',
        //         'lang' => 'ar',
        //         'body_class' => 'rtl'
        //     ]]);
        // }else{
        //     session(['body_direction' => [
        //         'direction' => 'ltr',
        //         'lang' => 'en',
        //         'body_class' => ''
        //     ]]);
        // }

         return response()->json([
            "navbarLinks"=>NavbarLinksSlugResource::collection($navbarLinks),
             "services"=> ServiceEditResource::collection($services),
            //  "page"=>new FrontPageWebsiteResource($homePage),
            //  "mainSetting"=>$mainSetting
         ]);
    }
    // public function show($lang = 'en', $slug, $singleSlug, Request $request)
    public function show( Request $request , $slug, $singleSlug)
    {

         if (!$request->singleSlug && $request->singleSlug == null) {
           return response()->json([
               "message"=>"the homePage show NotFound"
           ],404);
        }
        // $product = Product::with('translations')
        // ->whereHas('translations', function ($query) use ($singleSlug) {
        //     $query->where('slug', $singleSlug)->where('locale', app()->getLocale());
        // })
        // ->first();
        $service= Service::find($request->singleSlug);
        if (!$service) {
            return response()->json([
                "message"=> __('messages.error.not_found')
            ]);
        }
        return response()->json([
            "data"=> $service
        ]);
        // if (!$product) {
        //     $product = Product::with('translations')
        //     ->whereHas('translations', function ($query) use ($singleSlug) {
        //         $query->where('slug', $singleSlug)->whereIn('locale', ['en', 'ar']);
        //     })
        //     ->first();
        // }

        // if (!$product) {
        //     abort(404);
        // }
    }
}
