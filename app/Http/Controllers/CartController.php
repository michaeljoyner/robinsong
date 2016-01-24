<?php

namespace App\Http\Controllers;

use App\Cart\CartRepository;
use App\Shipping\ShippingCalculatorFactory;
use App\Shipping\ShippingService;
use App\Stock\Product;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    /**
     * @var CartRepository
     */
    private $cartRepo;

    public function __construct(CartRepository $cartRepo)
    {
        $this->cartRepo = $cartRepo;
    }

    public function addItem(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $this->cartRepo->addProductToCart($product, $request->quantity, $request->options);

        return response()->json($this->cartRepo->itemCount());
    }

    public function getCartItems(Request $request)
    {
        return response()->json($this->cartRepo->cartContents(true));
    }

    public function getCartSummary(Request $request)
    {
        return response()->json([
            'total_price' => $this->cartRepo->totalPrice(),
            'item_count' => $this->cartRepo->itemCount(),
            'product_count' => $this->cartRepo->productCount()
        ]);
    }

    public function shippingPricesForWeight(Request $request, ShippingService $shippingService)
    {
        $shippingInfo = $shippingService->quote($this->cartRepo->weightOfItemsInCart(), $this->cartRepo->totalPrice());
        return response()->json($shippingInfo);
    }

    public function updateItemQuantity($rowid, Request $request)
    {
        $result = $this->cartRepo->updateProductQty($rowid, $request->qty);

        if($result) {
            return response()->json('ok');
        }

        return response('failed to update', 500);
    }

    public function deleteItem($rowid)
    {
        $this->cartRepo->deleteRow($rowid);

        return response()->json('ok');
    }

    public function emptyCart()
    {
        $this->cartRepo->clearCart();

        return response()->json('ok');
    }

}
