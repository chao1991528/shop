<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\OrderRequest;
use App\Services\OrderService;
use App\Models\UserAddress;
use App\Models\Order;

class OrdersController extends Controller
{
    //
    public function store(OrderRequest $request, OrderService $orderService)
    {
    	$user = $request->user();
        $address = UserAddress::find($request->input('address_id'));

        return $orderService->add($user, $address, $request->input('remark'), $request->input('items'));   	
    }

    public function index(Request $request)
    {
        $orders = Order::query()->with(['items.product', 'items.productSku'])
                                ->where('user_id', $request->user()->id)
                                ->orderBy('created_at', 'desc')
                                ->paginate();

        return view('orders.index', ['orders' => $orders]);
    }

    public function show(Order $order, Request $request)
    {
        return view('orders.show', ['order' => $order->load(['items.product', 'items.productSku'])]);
    }
}
