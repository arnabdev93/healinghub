<?php

namespace App\Http\Controllers\API;

// use App\Http\Controllers\Controller;
use App\Http\Controllers\API\BaseController;
use Illuminate\Http\Request;
use App\Models\CartItem;
use App\Helpers\ShoppingCart;

use App\Models\Product;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use DB;
class CartController extends BaseController
{
    public function index()
    {
        try{
            $user_id = Auth::id();
            $shopping_cart = new ShoppingCart;
            $cart_data = $shopping_cart->get($user_id);
            if($cart_data){
                $result = $shopping_cart->getCartdata($cart_data);
                return $this->sendResponse($result, 'Successfull');
            }else{
                return $this->sendError("Cart is empty");
            }
        }catch(\Exception $e){
            return $this->sendError($e->getMessage());
        }
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required',
            'quantity' => 'required|numeric|min:1',
            'medicine_power' => 'nullable',
            'product_price_id' => 'required'
        ]);
        if ($validator->passes()) {
            $product = Product::select('id','name','status','image','medicine_power')->where('id',$request->product_id)->where('status',1)->first();
            if($product){
                if($product->medicine_power){
                    if(!$request->medicine_power){
                        return $this->sendError("Medicine Power required");
                    }
                }
                $user_id = Auth::id();
                $shopping_cart = new ShoppingCart;
                $cart_add = $shopping_cart->addNew($user_id,$request,$product);
                return $this->sendResponse([], 'Successfull');
            }else{
                return $this->sendError("Product not available");
            }
        }else{
            return $this->sendError($validator->errors()->first());
        }
    }
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cart_item_id' => 'required',
            'quantity' => 'required|numeric|min:1',
            'type' => 'required|string|in:add,remove',
        ]);
        if ($validator->passes()) {
            try{
                $user_id = Auth::id();
                /*Update cart*/
                $shopping_cart = new ShoppingCart;
                $cart_update = $shopping_cart->update($user_id,$request);
                if($cart_update){
                    $cart_data = $shopping_cart->get($user_id);
                    if($cart_data){
                        $result = $shopping_cart->getCartdata($cart_data);
                        return $this->sendResponse($result, 'Successfull');
                    }else{
                        return $this->sendError("Cart is empty");
                    }
                }else{
                    return $this->sendError("Something error happend. Please try after sometimes.");
                }
            }catch(\Exception $e){
                return $this->sendError($e->getMessage());
            }
        }else{
            return $this->sendError($validator->errors()->first());
        }
    }
    public function cartItemDelete($id)
    {
        try{
            $user_id = Auth::id();
            $cart_item = CartItem::find($id);
            if($cart_item){
                $cart_item->delete();

                $shopping_cart = new ShoppingCart;
                $cart_data = $shopping_cart->get($user_id);
                if($cart_data){
                    $result = $shopping_cart->getCartdata($cart_data);
                    return $this->sendResponse($result, 'Successfull');
                }else{
                    return $this->sendError("Cart is empty");
                }
            }else{
                return $this->sendError("Cart Item not exists.");
            }
        }catch(\Exception $e){
            return $this->sendError($e->getMessage());
        }
    }
}
