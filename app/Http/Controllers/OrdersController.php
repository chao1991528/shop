<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\OrderRequest;
use App\Services\OrderService;
use App\Models\UserAddress;
use App\Models\Order;
use App\Exceptions\InvalidRequestException;
use App\Http\Requests\SendReviewRequest;
use Carbon\Carbon;
use App\Events\OrderReviewed;

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

    public function received(Order $order, Request $request)
    {
        //权限判断
        $this->authorize('own', $order);
        //订单状态是否为已发货
        if($order->ship_status != Order::SHIP_STATUS_DELIVERED){
            throw new InvalidRequestException("订单状态有误");
        }

        //更新订单状态
        $order->update([
            'ship_status' => Order::SHIP_STATUS_RECEIVED
        ]);

        //返回订单信息
        return $order;
    }

    public function review(Order $order)
    {
        //检验权限
        $this->authorize('own', $order);
        if(!$order->paid_at){
            throw new InvalidRequestException("订单未支付，不可评价");
        }

        return view('orders.review', ['order' => $order->load(['items.productSku', 'items.product'])]);
    }

    public function sendReview(Order $order, SendReviewRequest $request)
    {
        //权限检测
        $this->authorize('own', $order);
        //订单是否支付
        if(!$order->paid_at){
            throw new InvalidRequestException("订单未支付，不可评价");
        }
        //订单是否已经评价
        if($order->reviewed){
            throw new InvalidRequestException("订单已经评价过了");
        }

        $reviews = $request->input('reviews');
        //开启事务
        \DB::transaction(function() use ($reviews, $order){
            foreach ($reviews as $review) {
                $item = $order->items()->find($review['id']);
                $item->update([
                    'rating' => $review['rating'],
                    'review' => $review['review'],
                    'reviewed_at' => Carbon::now()
                ]);
            }
            //订单标记为已评价
            $order->update(['reviewed' => true]);

            event(new OrderReviewed($order));
        });

        return redirect()->back();
    }
}
