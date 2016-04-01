<?php

namespace App\Http\Controllers;

use App\Shipping\ShippingCalculatorFactory;
use App\Stock\Category;
use App\Stock\Collection;
use App\Stock\Product;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Michaeljoyner\Edible\ContentRepository;

class PagesController extends Controller
{

    /**
     * @var ContentRepository
     */
    private $contentRepository;

    public function __construct(ContentRepository $contentRepository)
    {
        $this->contentRepository = $contentRepository;
    }
    public function home()
    {
        $collections = Collection::all();
        $collections->count() > 1 ? $collections->random(2) : $collections;
        $page = $this->contentRepository->getPageByName('home');
        $sliderImages = $page ? $page->imagesOf('slider') : collect([]);
        $intro = $page ? $page->textFor('intro') : '';
        $products = $this->getHotProducts();
        return view('front.pages.home')->with(compact('collections', 'sliderImages', 'intro', 'products'));
    }

    public function collections()
    {
        $collections = Collection::all();
        return view('front.pages.shop')->with(compact('collections'));
    }

    public function categories($slug)
    {
        $collection = Collection::findBySlug($slug);
        $categories = $collection->categories;
        return view('front.pages.collection')->with(compact('collection', 'categories'));
    }

    public function products($slug)
    {
        $category = Category::findBySlug($slug);
        $products = $category->products()->where('available', 1)->latest()->get();
        $tags = $this->getTagSetFromProducts($products);

        return view('front.pages.category')->with(compact('category', 'products', 'tags'));
    }

    public function product($slug)
    {
        $product = Product::with('options', 'customisations')->where('slug', $slug)->firstOrFail();

        return view('front.pages.product')->with(compact('product'));
    }

    public function showCart()
    {
        return view('front.pages.cart');
    }

    public function thanks()
    {
        return view('front.pages.thanks');
    }

    public function about()
    {
        $page = $this->contentRepository->getPageByName('about');
        return view('front.pages.about')->with(compact('page'));
    }

    /**
     * @param $products
     * @return mixed
     */
    private function getTagSetFromProducts($products)
    {
        return $products->reduce(function ($carry, $product) {
            $taglist = $product->getTagsList();
            foreach ($taglist as $tag) {
                if (!in_array($tag, $carry)) {
                    $carry[] = $tag;
                }
            }

            return $carry;
        }, []);
    }

    private function getHotProducts()
    {
        $products = Product::where('available', 1)->get();

        return $products->shuffle()->take(9);

    }
}
