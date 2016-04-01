<?php

namespace App\Http\Controllers\Admin;

use App\Orders\Order;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class OrdersController extends Controller
{
    public function index($status = null)
    {
        $orders = $this->getOrdersForStatus($status);
        return view('admin.orders.index')->with(compact('orders', 'status'));
    }

    public function show($orderId)
    {
        $order = Order::withTrashed()->with('items.options', 'items.customisations')->findOrFail($orderId);

        return view('admin.orders.show')->with(compact('order'));
    }

    public function setFulfilledStatus($orderId, Request $request)
    {
        $this->validate($request, ['fulfill' => 'required|boolean']);
        $order = Order::findOrFail($orderId);

        $request->fulfill ? $order->fulfill() : $order->unfulfill();

        return response()->json(['new_state' => $order->isFulfilled()]);
    }

    public function setCancelledStatus(Request $request, $orderId)
    {
        $this->validate($request, ['cancel' => 'required|boolean']);
        $order = Order::findOrFail($orderId);

        $request->cancel ? $order->cancel() : $order->restore();

        return response()->json(['new_state' => $order->isCancelled()]);
    }


    public function archive($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return redirect('admin/orders');
    }

    private function getOrdersForStatus($status)
    {
        if($status === 'cancelled') {
            return Order::with('items.options')->where('cancelled', 1)->latest()->paginate(10);
        }

        if($status === 'ongoing') {
            return Order::with('items.options')->where('cancelled', 0)->where('fulfilled', 0)->latest()->paginate(10);
        }

        if($status === 'fulfilled') {
            return Order::with('items.options')->where('cancelled', 0)->where('fulfilled', 1)->latest()->paginate(10);
        }

        if($status === 'archived') {
            return Order::onlyTrashed()->with('items.options')->latest()->paginate(10);
        }

        return Order::with('items.options')->latest()->paginate(10);
    }
}
