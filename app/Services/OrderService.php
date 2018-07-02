<?php
namespace App\Services;

use App\Models\User;
use App\Models\UserAddress;
use App\Models\ProductSku;
use App\Models\Order;
use App\Exceptions\InvalidRequestException;
use App\Jobs\CloseOrder;
use Carbon\Carbon;

class OrderService
{
	public function add(User $user, UserAddress $address, $remark, $items)
	{
		// 开启一个数据库事务
		$order = \DB::transaction(function() use($user, $address, $remark, $items){
			//更新此地址的最后使用时间
			$address->update(['last_used_at' => Carbon::now()]);
			//创建一个订单
			$order = new Order([
				'remark' => $remark,
				'address' => [
					'address' 	=> 	$address->full_address,
					'zip'     	=>  $address->zip,
					'contact_name' => $address->name,
					'contact_phone' => $address->contact_phone
				],
				'total_amount' => 0
			]);
			$order->user()->associate($user);
			$order->save();

			$totalAmount = 0;
			//遍历sku
			foreach ($items as $data) {
				$sku = ProductSku::find($data['sku_id']);
				//创建一个 OrderItem 并直接与当前订单关联
				$item = $order->items()->make([
					'price' => $sku->price,
					'amount' => $data['amount']
				]);
				$item->productSku()->associate($sku);
				$item->product()->associate($sku->product_id);
				$item->save();
				$totalAmount += $sku->price * $data['amount'];
				if($sku->decreaseStock($data['amount']) < 0){
					throw new InvalidRequestException("该商品库存不足");
				}			
			}
			//订单总额更新
			$order->update(['total_amount' => $totalAmount]);
			//将下单商品从购物车中删除
			$skuIds = collect($items)->pluck('sku_id')->all();
			app(CartService::class)->remove($skuIds);

			return $order;
		});

		//超时未支付的关闭
		dispatch(new CloseOrder($order, config('app.order_ttl')));

		return $order;
	}
}