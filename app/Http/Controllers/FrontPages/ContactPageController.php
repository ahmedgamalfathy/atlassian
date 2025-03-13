<?php

namespace App\Http\Controllers\FrontPages;

use App\Enums\Blog\BlogStatus;
use App\Http\Controllers\Controller;
use App\Models\Blog\Blog;
use App\Models\FrontPage\FrontPage;
use App\Models\Product\Product;
use App\Services\FrontPage\FrontPageService;
use Illuminate\Http\Request;


class ContactPageController extends Controller
{
    protected $frontPageService;

    public function __construct(FrontPageService $frontPageService)
    {
        $this->frontPageService = $frontPageService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index($lang='en', $slug=null)
    {
        $locale = app()->getLocale();




        $homePage = FrontPage::where('controller_name', 'HomePageController')
            ->with(['sections.translations' => function ($query) use ($locale) {
                $query->where('locale', $locale);
            }])
            ->first();


        $products = Product::with('firstImage')->get();

        $blogs = Blog::where('is_published', BlogStatus::PUBLISHED->value)->limit(3)->get();

        return view('Contact.index', compact('homePage', 'products', 'blogs'));
    }
}
