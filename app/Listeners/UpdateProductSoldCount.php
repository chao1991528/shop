<?php

namespace App\Listeners;

use App\Events\OrderPaid;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\OrderItem;

//  implements ShouldQueue 代表此监听器是异步执行的
class UpdateProductSoldCount implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  OrderPaid  $event
     * @return void
     */
    public function handle(OrderPaid $event)
    {
        //获取订单
        $order = $event->getOrder();
        //遍历订单中的商品信息
        foreach ($order->items as $item) {
            $product = $item->product;
            // 计算对应商品的销量
            $soldCount = OrderItem::query()->where('product_id', $product->id)
                                       ->whereHas('order', function($query){
                                            $query->whereNotNull('paid_at');
                                       })->sum('amount');

        }
        //更新产品销量
        $product->update([
            'sold_count' => $soldCount
        ]);
    }
}
