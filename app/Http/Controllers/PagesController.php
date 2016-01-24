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

class PagesController extends Controller
{
    public function home()
    {
        $collections = Collection::all()->random(2);
        return view('front.pages.home')->with(compact('collections'));
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
        $products = $category->products;
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
}
