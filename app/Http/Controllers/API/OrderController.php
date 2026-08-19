<?php

namespace App\Http\Controllers\API;

// use App\Http\Controllers\Controller;
use App\Http\Controllers\API\BaseController;
use Illuminate\Http\Request;

use App\Helpers\ShoppingCart;

use App\Models\Order;
use App\Models\Address;
use App\Models\OrderItem;
use App\Models\OrderStatusLog;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use Razorpay\Api\Api;
use DB;
class OrderController extends BaseController
{
    public function index(Request $request)
    {
        // only cart/prescription type is allowed
        $user_id = Auth::id();
        $url = asset('storage/');
        $orders = Order::select('orders.id','orders.orderno','orders.status','orders.user_id','orders.total','orders.created_at','orders.delivery_date')
                        ->where('orders.user_id',$user_id)
                        ->where('orders.type',$request->type)
                        ->selectRaw("(SELECT SUBSTRING_INDEX(GROUP_CONCAT(CONCAT('{$url}/', image) SEPARATOR ','), ',', 4) FROM order_items WHERE order_items.order_id = orders.id) as item_images")
                        ->orderBy('id','DESC')->paginate(20);
        $result = [
            'orders' => $orders
        ];
        return $this->sendResponse($result,'Successful');
    }
    public function getOrderDetails($id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required',
        ]);

        if ($validator->passes()) {
            $order = Order::with([
                'user:id,name,email',
                'address:id,address,country,city,state,pincode',
                'items'
            ])->where('id', $id)->first();

            if (!$order) {
                return $this->sendError("Order not found");
            }

            $itemCount = $order->items->sum('quantity');

            $data = [
                'order_id'   => $order->id,
                'order_no'   => $order->orderno,
                'status'     => $order->status,
                'type'       => $order->type,
                'user' => [
                    'id'    => $order->user->id ?? null,
                    'name'  => $order->user->name ?? '',
                    'email' => $order->user->email ?? ''
                ],
                'address' => [
                    'address'   => $order->address->address ?? '',
                    'city'    => $order->address->city ?? '',
                    'state'   => $order->address->state ?? '',
                    'pincode' => $order->address->pincode ?? '',
                    'country' => $order->address->country ?? ''
                ],
                'created_at'    => $order->created_at,
                'updated_at'    => $order->updated_at,
                'delivery_date' => $order->delivery_date,
                'subtotal'        => $order->subtotal,
                'total'           => $order->total,
                'delivery_charge' => $order->delivery_charge,
                'discount'        => $order->discount_amount,
                'item_count' => $itemCount,
                'items' => $order->items->map(function ($item) {
                    return [
                        'item_id'       => $item->id,
                        'product_id'    => $item->product_id,
                        'name'          => $item->name,
                        'image'         => asset('storage/'.$item->image),
                        'quantity'      => $item->quantity,
                        'price'         => $item->amount,
                        'total_price'   => $item->amount * $item->quantity,
                        'medicine_power'=> $item->medicine_power,
                        'pack_size'     => $item->pack_size,
                        'created_at'    => $item->created_at,
                    ];
                })
            ];

            return $this->sendResponse($data,'Successful');
        }else{
            return $this->sendError($validator->errors()->first());
        }
    }
    // public function store(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'address_id' => 'required',
    //         'payment_method' => 'required',//This is multiple/single like customer can pay from (Wallet,Cod) or (Wallet,Upi)
    //     ]);
    //     if ($validator->passes()) {
    //         $user_id = Auth::id();
    //         $address_id = $request->address_id;
    //         $payment_method = $request->payment_method;

    //         $address = Address::select('id','is_delivery','latitude','longitude','receipent_name','phone','address')->where('id',$address_id)->where('user_id',$user_id)->first();
    //         if(!$address){
    //             return $this->sendError("Address not found");
    //         }
    //         $shopping_cart = new ShoppingCart;
    //         $cart = $shopping_cart->get($user_id);
    //         if(!$cart){
    //             return $this->sendError("Cart is empty");
    //         }
    //         $cart_items = $cart->cart_items;
    //         if($cart_items->count()==0){
    //             return $this->sendError("Cart Items not available");
    //         }
    //         $cart_data = $shopping_cart->getCartdata($cart);
    //         $sub_total = $cart_data['sub_total'];
    //         $total = $cart_data['total'];
    //         // Now create Order
    //         $order = new Order;
    //         $order->orderno = 'HBC-'.time();
    //         $order->user_id = $user_id;
    //         $order->address_id = $address_id;
    //         $order->subtotal = $sub_total;
    //         $order->type = 'cart';
    //         $order->total = $total;
    //         $order->total_tax = $cart_data['total_tax'];
    //         $order->service_tax = $cart_data['service_tax'];
    //         $order->gst_on_item_total = $cart_data['gst_on_item_total'];
    //         $order->gst_on_packaging_charge = $cart_data['gst_on_packaging_charge'];
    //         $order->gst_on_delivery_charge = $cart_data['gst_on_delivery_charge'];
    //         $order->delivery_charge = $cart_data['delivery_charge'];
    //         $order->packaging_charge = $cart_data['packaging_charge'];
    //         $order->discount_amount = $cart_data['discount_amount'];
    //         $order->order_type = $payment_method;
    //         $order->status = 'pending';
    //         $order->pay_status = 'pending';
    //         $order->save();

    //         $order_id = $order->id;

    //         foreach ($cart_items as $key => $value) {
    //             $product = $value->product;
    //             $product_price = $value->product_price;
    //             $item_price = $product_price->price;
    //             if($product_price->special_price>0){
    //                 $item_price = $product_price->special_price;
    //             }
    //             $order_item = new OrderItem;
    //             $order_item->order_id = $order_id;
    //             $order_item->product_id = $value->product_id;
    //             $order_item->product_price_id = $value->product_price_id;
    //             $order_item->medicine_power = $value->medicine_power;
    //             $order_item->pack_size = $product_price->pack_size;
    //             $order_item->amount = $item_price;
    //             $order_item->quantity = $value->quantity;
    //             $order_item->name = $product->name;
    //             $order_item->image = $product->image;
    //             $order_item->save();
    //         }
    //         $status_log = new OrderStatusLog;
    //         $status_log->user_id = $user_id;
    //         $status_log->order_id = $order_id;
    //         $status_log->status = 'pending';
    //         $status_log->notes = 'Order Created';
    //         $status_log->save();

    //         return $this->sendResponse([],'Successful');
    //     }else{
    //         return $this->sendError($validator->errors()->first());
    //     }
    // }
    public function uploadPrescription(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'address_id' => 'required',
            'notes' => 'nullable',
            'images' => 'required|array|min:1|max:5',
            'images.*' => 'required|file|mimes:jpeg,jpg,png,webp,gif'
        ]);
        if ($validator->passes()) {
            $user_id = Auth::id();

            $checkExists = Order::select('id')->where('user_id',$user_id)->where('status','pending')->count();
            if($checkExists>5){
                return $this->sendError("Prescription already uploaded");
            }
            $checkAddressExists = Address::select('id')->where('id',$request->address_id)->first();
            if(!$checkAddressExists){
                return $this->sendError("Address not found");
            }
            $order = new Order;
            $order->user_id = $user_id;
            $order->type = 'prescription';
            $order->address_id = $request->address_id;
            $order->orderno = 'HBP-'.time();
            $order->notes = $request->notes;
            $order->save();

            $order_id = $order->id;

            if (!empty($request->images)) {
                foreach ($request->images as $key => $value) {
                    $item = new OrderItem;
                    $item->order_id = $order_id;
                    $item->image = $value->store('order', 'public');
                    $item->save();
                }
            }
            return $this->sendResponse([],'Successful');
        }else{
            return $this->sendError($validator->errors()->first());
        }
    }

    //Razorpay
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'address_id' => 'required',
            'payment_method' => 'required',//This is multiple/single like customer can pay from (Wallet,Cod) or (Wallet,Upi)
            'razorpay_payment_id' => 'required_if:payment_method,upi',
            'razorpay_order_id' => 'required_if:payment_method,upi',
            'razorpay_signature' => 'required_if:payment_method,upi',
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }

        $user_id = Auth::id();
        $address_id = $request->address_id;
        $payment_method = $request->payment_method;

        $address = Address::select('id','is_delivery','latitude','longitude','receipent_name','phone','address')->where('id',$address_id)->where('user_id',$user_id)->first();
        if(!$address){
            return $this->sendError("Address not found");
        }

        if($payment_method == 'upi'){
            try {
                $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));
                $api->utility->verifyPaymentSignature([
                    'razorpay_order_id'   => $request->razorpay_order_id,
                    'razorpay_payment_id' => $request->razorpay_payment_id,
                    'razorpay_signature'  => $request->razorpay_signature,
                ]);
            } catch (\Exception $e) {
                return $this->sendError("Payment verification failed");
            }
        }

        DB::beginTransaction();
        try {
            $pay_status = ($payment_method == 'upi') ? 'paid' : 'pending';
            $order = $this->placeOrderFromCart(
                $user_id,
                $address_id,
                $payment_method,
                $request->razorpay_order_id ?? null,
                $request->razorpay_payment_id ?? null,
                $pay_status
            );
            if(!$order){
                DB::rollBack();
                return $this->sendError("Cart Items not available");
            }
            DB::commit();
            return $this->sendResponse([],'Successful');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->sendError($e->getMessage());
        }
    }
    public function createRazorpayOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'address_id' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }

        try {
            $user_id = Auth::id();
            // dd($user_id);
            $address = Address::where('id',$request->address_id)->where('user_id',$user_id)->first();
            if(!$address){
                return $this->sendError("Address not found");
            }

            $shopping_cart = new ShoppingCart;
            $cart = $shopping_cart->get($user_id);
            if(!$cart || $cart->cart_items->count()==0){
                return $this->sendError("Cart is empty");
            }
            $cart_data = $shopping_cart->getCartdata($cart);
            $amount = round($cart_data['total'] * 100);

            $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));
            $razorpay_order = $api->order->create([
                'receipt'  => 'rcpt_'.$user_id.'_'.time(),
                'amount'   => $amount,
                'currency' => 'INR',
                'notes'    => [
                    'user_id'    => $user_id,
                    'address_id' => $request->address_id,
                ],
            ]);

            return $this->sendResponse([
                'razorpay_order_id' => $razorpay_order['id'],
                'razorpay_key'      => config('services.razorpay.key'),
                'amount'            => $amount,
            ], 'Razorpay order created');

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
    private function placeOrderFromCart($user_id, $address_id, $payment_method, $razorpay_order_id, $transaction_id,$pay_status = 'pending')
    {
        $existing = Order::where('razorpay_order_id', $razorpay_order_id)->first();
        if($existing){
            return $existing;
        }

        $shopping_cart = new ShoppingCart;
        $cart = $shopping_cart->get($user_id);
        if(!$cart || $cart->cart_items->count()==0){
            return null;
        }
        $cart_items = $cart->cart_items;
        $cart_data = $shopping_cart->getCartdata($cart);

        $order = new Order;
        $order->orderno = 'HBC-'.time();
        $order->user_id = $user_id;
        $order->address_id = $address_id;
        $order->subtotal = $cart_data['sub_total'];
        $order->type = 'cart';
        $order->total = $cart_data['total'];
        $order->total_tax = $cart_data['total_tax'];
        $order->service_tax = $cart_data['service_tax'];
        $order->gst_on_item_total = $cart_data['gst_on_item_total'];
        $order->gst_on_packaging_charge = $cart_data['gst_on_packaging_charge'];
        $order->gst_on_delivery_charge = $cart_data['gst_on_delivery_charge'];
        $order->delivery_charge = $cart_data['delivery_charge'];
        $order->packaging_charge = $cart_data['packaging_charge'];
        $order->discount_amount = $cart_data['discount_amount'];
        $order->order_type = $payment_method;
        $order->status = 'pending';
        // $order->pay_status = 'paid';
        $order->pay_status = $pay_status;
        $order->transaction_id = $transaction_id;
        $order->razorpay_order_id = $razorpay_order_id;
        $order->save();

        foreach ($cart_items as $value) {
            $product = $value->product;
            $product_price = $value->product_price;
            if (!$product || !$product_price) {
                continue;
            }
            $item_price = $product_price->special_price>0 ? $product_price->special_price : $product_price->price;
            $order_item = new OrderItem;
            $order_item->order_id = $order->id;
            $order_item->product_id = $value->product_id;
            $order_item->product_price_id = $value->product_price_id;
            $order_item->medicine_power = $value->medicine_power;
            $order_item->pack_size = $product_price->pack_size;
            $order_item->amount = $item_price;
            $order_item->quantity = $value->quantity;
            $order_item->name = $product->name;
            $order_item->image = $product->image;
            $order_item->save();
        }

        $status_log = new OrderStatusLog;
        $status_log->user_id = $user_id;
        $status_log->order_id = $order->id;
        $status_log->status = 'pending';
        $status_log->notes = 'Order Created';
        $status_log->save();

        $shopping_cart->clear($user_id);

        return $order;
    }
    public function razorpayWebhook(Request $request)
    {
        $webhookSecret = config('services.razorpay.webhook_secret');
        $signature = $request->header('X-Razorpay-Signature');

        try {
            $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));
            $api->utility->verifyWebhookSignature($request->getContent(), $signature, $webhookSecret);
        } catch (\Exception $e) {
            return response('Invalid signature', 400);
        }

        $payload = $request->all();

        if(($payload['event'] ?? null) == 'payment.captured'){
            $payment = $payload['payload']['payment']['entity'];
            $razorpay_order_id = $payment['order_id'];
            $razorpay_payment_id = $payment['id'];
            $notes = $payment['notes'] ?? [];
            $user_id = $notes['user_id'] ?? null;
            $address_id = $notes['address_id'] ?? null;

            if($user_id && $address_id){
                DB::beginTransaction();
                try {
                    $this->placeOrderFromCart(
                        $user_id,
                        $address_id,
                        'upi',
                        $razorpay_order_id,
                        $razorpay_payment_id,
                        'paid'
                    );
                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollBack();
                }
            }
        }

        return response('OK', 200);
    }




}
