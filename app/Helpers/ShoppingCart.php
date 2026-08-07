<?php
namespace App\Helpers;

use Illuminate\Database\Eloquent\Builder;
use App\Models\CartItem;
use App\Models\Cart;
use App\Models\Address;
use App\Models\Setting;
use App\Models\ProductPrice;

use App\Helpers\CouponCodeDiscount;
use App\Helpers\CustomHelper;
use DB;
class ShoppingCart{
	public function __construct(){

    }
    public function get($user_id)
    {
    	$cart = Cart::with(['cart_items' => function($query){
		    				$query->select('id','cart_id','product_id','product_price_id','quantity','medicine_power')->with(['product' => function($query){
			    					$query->select('id','image','name','description','status')
                                    ->selectRaw("CASE
                                        WHEN LENGTH(products.description) > 0
                                        THEN CONCAT(SUBSTRING_INDEX(SUBSTRING_INDEX(products.description, ' ', 15), ' ', -15),
                                                    IF(LENGTH(products.description) - LENGTH(REPLACE(products.description, ' ', '')) > 15, '...', ''))
                                        ELSE ''
                                    END as description");
			    				},'product_price'=>function($query){
                                    $query->select('id','product_id','pack_size','price','special_price');
                                }]);
		    			}])->select('id','user_id')->where('user_id',$user_id)->first();
    	if($cart){
    		if ($cart->cart_items->count()==0) {
    			$cart->delete();
    			$cart = null;
    		}
    	}
    	return $cart;
    }
    public function getCartdata($cart_data)
    {
        $sub_total = $delivery_charge = $packaging_charge = $total = $discount_amount = $gst_on_item_total = $gst_on_packaging_charge = $gst_on_delivery_charge = $total_tax = 0;
        $coupon = null;
        $cart_items_count = $cart_data->cart_items->count();
        if($cart_items_count>0){
        	$promo_id = $cart_data->promo_id;
        	$user_id = $cart_data->user_id;
            $settings = Setting::select('id','item_key','item_value')->whereIn('item_key',['gst_enable','service_tax','delivery_charge','packaging_charge'])->pluck('item_value', 'item_key')->toArray();

        	$delivery_charge = $settings['delivery_charge'] ?? 0;
            $packaging_charge = $settings['packaging_charge'] ?? 0;
            $service_tax = $settings['service_tax'] ?? 0;

            $total_quantity = $cart_data->cart_items->sum('quantity');
            if($packaging_charge){
                $packaging_charge = $packaging_charge * $total_quantity;
            }

            foreach ($cart_data->cart_items as $key => $val) {
                $product_price = ProductPrice::select('id','price','special_price')->where('id',$val->product_price_id)->first();
                if(!$product_price){
                    continue;
                }
                $item_price = $product_price->price;
                if($product_price->special_price>0){
                	$item_price = $product_price->special_price;
                }
                $full_amount = $item_price * $val->quantity;
                $sub_total += $full_amount;
            }
            if($service_tax && $settings['gst_enable']){
                $gst_on_item_total = (($sub_total * $service_tax) / 100);
                if($packaging_charge){
                    $gst_on_packaging_charge = (($packaging_charge * $service_tax) / 100);
                }
                if($delivery_charge){
                    $gst_on_delivery_charge = (($delivery_charge * 18) / 100);
                }
                $total_tax = $gst_on_item_total + $gst_on_packaging_charge + $gst_on_delivery_charge;
            }

            $total_without_discount = $sub_total + $delivery_charge + $packaging_charge + $total_tax;

            $total = ($total_without_discount - $discount_amount);
        }
        $result = [
            'cart'=>$cart_data,'sub_total'=>CustomHelper::moneyRound($sub_total),'total'=>CustomHelper::moneyRound($total),'delivery_charge'=>CustomHelper::moneyRound($delivery_charge),'discount_amount'=>CustomHelper::moneyRound($discount_amount),'cart_item_count'=>$cart_items_count,'packaging_charge'=>CustomHelper::moneyRound($packaging_charge),'gst_on_item_total'=>CustomHelper::moneyRound($gst_on_item_total),'gst_on_packaging_charge'=>CustomHelper::moneyRound($gst_on_packaging_charge),'gst_on_delivery_charge'=>CustomHelper::moneyRound($gst_on_delivery_charge),'total_tax'=>CustomHelper::moneyRound($total_tax),'service_tax'=>CustomHelper::moneyRound($service_tax),'coupon'=>$coupon
        ];
        return $result;
    }
    public function addNew($user_id,$request,$product)
    {
    	$cart = Cart::select('id','user_id')->where('user_id',$user_id)->first();
    	$quantity = $request->quantity;
    	$product_id = $request->product_id;
    	$product_price_id = $request->product_price_id;
        $medicine_power = $request->medicine_power;
    	if($cart == null){
    		$cart = new Cart;
    		$cart->user_id = $user_id;
    		$cart->save();
    	}
    	if($cart){
            $cartItemQry = CartItem::where('cart_id',$cart->id)->where('product_id',$product_id)->where('product_price_id',$product_price_id);
            if($product->medicine_power){
                $cartItemQry->where('medicine_power',$medicine_power);
            }
    		$cart_item = $cartItemQry->pluck('id');
    		if($cart_item->count() == 0 && $quantity > 0){
    			/* Add Item to Cart */
				$cart_item = new CartItem;
				$cart_item->cart_id=$cart->id;
				$cart_item->product_id=$product_id;
				$cart_item->product_price_id=$product_price_id;
				$cart_item->medicine_power=$medicine_power;
				$cart_item->quantity=$quantity;
				$cart_item->save();
    		}
    	}
    	return 1;
    }
    public function update($user_id,$request)
    {
    	$cart = Cart::where('user_id',$user_id)->first();
    	$quantity = $request->quantity;
    	$cart_item_id = $request->cart_item_id;
    	$added_extra_id = $added_modifier_id = null;
    	if(!empty($request->added_extra_id)){
    		$added_extra_id = $request->added_extra_id;
    	}
    	if(!empty($request->added_modifier_id)){
    		$added_modifier_id = $request->added_modifier_id;
    	}
    	// print_r($added_extra_id);exit;
    	if($cart){
    		$cartitem = CartItem::where('id',$cart_item_id)->first();
    		if($cartitem){
    			if($request->type=='add'){//for Add quantity
    				$cart_item_quantity = ($cartitem->quantity)+$quantity;
    			}else{//for Remove quantity
    				$cart_item_quantity = ($cartitem->quantity)-$quantity;
    			}
    			if($cart_item_quantity){
    				$cartitem->quantity = $cart_item_quantity;
    				$cartitem->save();
    			}else{
    				/* if cart-item quantity is 0 then delete cart-item from cart and check Cart(if cart is emapty then delete cart also.) */
    				$cartitem->delete();
    				// $cart = Cart::where('user_id',$user_id)->first();
    				// if($cart){
    				// 	$cartitem_count = $cart->cart_items()->count();
    				// 	if($cartitem_count==0){
    				// 		$cart->delete();
    				// 	}
    				// }
    			}
		    	return 1;
    		}else{
    			return null;
    		}
    	}else{
    		return null;
    	}
    }
    public function clear($user_id)
    {
    	$cart = Cart::where('user_id',$user_id)->first();
    	if($cart){
			$cart_items = $cart->cart_items();
			foreach ($cart_items as $cart_item) {
				$cart_item->added_modifiers()->detach();
			}
			$cart_items->delete();
			$cart->delete();
    		return true;
    	}else{
    		return false;
    	}

    }

}
