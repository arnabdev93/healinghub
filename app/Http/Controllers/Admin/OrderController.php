<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Order;
use App\Models\Address;
use App\Models\BookAppointment;
use App\Models\OrderItem;
use App\Models\User;

class OrderController extends Controller
{

    public function prescriptionOrders(Request $request)
    {
        if (request()->ajax()) {
            // Log::info('Filtering with:', [
            //     'start' => $request->start_date,
            //     'end'   => $request->end_date,
            //     'user'  => $request->user_id
            // ]);
            $listQry = Order::query()
                        ->leftJoin('users','orders.user_id','=','users.id')
                        ->leftJoin('addresses','orders.address_id','=','addresses.id')
                        ->where('orders.type','prescription')
                        ->select(
                            'orders.*',
                            'users.name as user_name',
                            'users.mobile',
                            'addresses.address as full_address'
                        );

            if ($request->has('start_date') && $request->start_date != '') {
                $listQry->whereDate('orders.created_at', '>=', $request->start_date);
            }
            if ($request->has('end_date') && $request->end_date != '') {
                $listQry->whereDate('orders.created_at', '<=', $request->end_date);
            }
            if ($request->has('user_id') && $request->user_id != '') {
                $listQry->where('orders.user_id', $request->user_id);
            }
            if($request->has('status') && $request->status != ''){
                $listQry->where('orders.status', $request->status);
            }
            $deliveredTotal = (clone $listQry)
            ->where('orders.delivery_status', 'delivered')
            ->sum('orders.total');
            // dd($deliveredTotal);

            $lists = $listQry->orderBy('id', 'DESC');

            return DataTables::of($lists)

            ->addColumn('order_source', function ($row) {

                if($row->appointment_id){
                    return '<span class="badge badge-sm badge-success-light">Appointment</span>';
                }else{
                    return '<span class="badge badge-sm badge-info-light">Direct Order</span>';
                }

            })

            ->addColumn('status',function($row){

                $statusHtml = $status = $row->status;

                if($status=='pending'){
                    $statusHtml = '<span class="badge badge-sm badge-danger-light">Pending</span>';
                }elseif ($status=='accept') {
                    $statusHtml = '<span class="badge badge-sm badge-success-light">Accepted</span>';
                }else{
                    $statusHtml = '<span class="badge badge-sm badge-warning-light">'.ucfirst($status).'ed</span>';
                }

                return $statusHtml;

            })
            ->addColumn('delivery_status', function ($row) {
                switch ($row->delivery_status) {
                    case 'delivered':
                        return '<span class="badge badge-sm badge-success-light">Delivered</span>';
                    default:
                        return '-';
                }
            })

            ->addColumn('user',function($row){

                return $row->user_name.'['.$row->mobile.']';

            })

            ->addColumn('address',function($row){

                $address = Address::select('address','pincode','city','state','country','building')->where('id',$row->address_id)->first();

                $new_address = '';

                if ($address->building) {
                    $new_address = $address->building.', ';
                }

                return $new_address.$address->address.', '.$address->city.', '.$address->state.' - '.$address->pincode.', '.$address->country;

            })

            ->addColumn('created_at',function($row){

                return date('Y-m-d g:i a',strtotime($row->created_at));

            })

            ->addColumn('action',function($row){

                return '
                    <a class="btn btn-sm btn-primary" href="'.route('prescription-orders.show',$row->id).'">Details</a>
                ';

            })

            ->rawColumns(['action','status','order_source','delivery_status'])
            ->with('delivered_total', $deliveredTotal)
            ->toJson();
        }

        $patients = User::where('role', 'customer')->orderBy('name', 'ASC')->get();

        $url = url('prescription-orders');

        $builder = app('datatables.html');

        $builder->ajax([
            'url' => $url,
            'data' => 'function(d) {
                d.start_date = $("#start_date_value").val();
                d.end_date = $("#end_date_value").val();
                d.user_id = $("#patient_filter").val();
                d.status = $("#status").val();
            }'
        ]);

        $builder->parameters([
            'lengthChange' => false,
            'searching' => true,
        ]);

        $dataTable = $builder->columns([

            'orderno' => [
                'data' => 'orderno',
                'name' => 'orderno',
                'title' => 'Order No'
            ],

            'order_source' => [
                'data' => 'order_source',
                'name' => 'order_source',
                'title' => 'Source'
            ],

            'user' => [
                'data' => 'user',
                'name' => 'users.name',
                'title' => 'User Name[Mobile]'
            ],

            'address' => [
                'data' => 'address',
                'name' => 'addresses.address',
                'title' => 'Address',
                'searchable' => false,
            ],

            'total' => [
                'data' => 'total',
                'name' => 'orders.total',
                'title' => 'Price',
                'searchable' => false,
            ],

            'status' => [
                'data' => 'status',
                'name' => 'status',
                'title' => 'Status'
            ],
            'delivery_status'=>[
                'data'=>'delivery_status',
                'name'=>'delivery_status',
                'title'=>'Delivery Status'
            ],

            'created_at' => [
                'data' => 'created_at',
                'name' => 'orders.created_at',
                'title' => 'Created At',
                'searchable' => false,
            ],

        ])->addAction([
            'defaultContent' => '',
            'data' => 'action',
            'name' => 'action',
            'title' => 'Action',
            'orderable' => false,
            'searchable' => false,
            'exportable' => false,
            'printable' => true,
        ]);

        return view('admin.order.prescription-order',compact('dataTable', 'patients'));
    }

    public function cartOrders(Request $request)
    {
        if (request()->ajax()) {

            // Log::info('Filtering with:', [
            //     'start' => $request->start_date,
            //     'end'   => $request->end_date,
            //     'user'  => $request->user_id
            // ]);
            $listQry = Order::query()
                        ->leftJoin('users','orders.user_id','=','users.id')
                        ->leftJoin('addresses','orders.address_id','=','addresses.id')
                        ->where('orders.type','cart')
                        ->select(
                            'orders.*',
                            'users.name as user_name',
                            'users.mobile',
                            'addresses.address as full_address'
                        );

            if ($request->has('start_date') && $request->start_date != '') {
                $listQry->whereDate('orders.created_at', '>=', $request->start_date);
            }
            if ($request->has('end_date') && $request->end_date != '') {
                $listQry->whereDate('orders.created_at', '<=', $request->end_date);
            }
            if ($request->has('user_id') && $request->user_id != '') {
                $listQry->where('orders.user_id', $request->user_id);
            }
            if($request->has('status') && $request->status != ''){
                $listQry->where('orders.status', $request->status);
            }
            $deliveredTotal = (clone $listQry)
            ->where('orders.status', 'delivered')
            ->sum('orders.total');
            // dd($deliveredTotal);


            $lists = $listQry->orderBy('id', 'DESC');

            return DataTables::of($lists)

            ->addColumn('order_source', function ($row) {

                if($row->appointment_id){
                    return '<span class="badge badge-sm badge-success-light">Appointment</span>';
                }else{
                    return '<span class="badge badge-sm badge-info-light">Direct Order</span>';
                }

            })

            ->addColumn('status',function($row){

                $statusHtml = $status = $row->status;

                if($status=='pending'){
                    $statusHtml = '<span class="badge badge-sm badge-danger-light">Pending</span>';
                }elseif ($status=='accept') {
                    $statusHtml = '<span class="badge badge-sm badge-success-light">Accepted</span>';
                }elseif ($status=='delivered') {
                    $statusHtml =  '<span class="badge badge-sm badge-success-light">Delivered</span>';
                }
                else{
                    $statusHtml = '<span class="badge badge-sm badge-warning-light">'.ucfirst($status).'ed</span>';
                }

                return $statusHtml;

            })

            ->addColumn('user',function($row){

                // $user = User::select('name','mobile')->where('id',$row->user_id)->first();

                // return $user->name.'['.$user->mobile.']';

                return $row->user_name.'['.$row->mobile.']';

            })

            ->addColumn('address',function($row){

                $address = Address::select('address','pincode','city','state','country','building')->where('id',$row->address_id)->first();

                $new_address = '';

                if ($address->building) {
                    $new_address = $address->building.', ';
                }

                return $new_address.$address->address.', '.$address->city.', '.$address->state.' - '.$address->pincode.', '.$address->country;

            })

            ->addColumn('created_at',function($row){

                return date('Y-m-d g:i a',strtotime($row->created_at));

            })

            ->addColumn('action',function($row){

                return '
                    <a class="btn btn-sm btn-primary" href="'.route('cart-orders-details',$row->id).'">Details</a>
                ';

            })

            ->rawColumns(['action','status','order_source'])
            ->with('delivered_total', $deliveredTotal)
            ->toJson();
        }

        $patients = User::where('role', 'customer')->orderBy('name', 'ASC')->get();

        $url = url('cart-orders');

        $builder = app('datatables.html');

        $builder->ajax([
            'url' => $url,
            'data' => 'function(d) {
                d.start_date = $("#start_date_value").val();
                d.end_date = $("#end_date_value").val();
                d.user_id = $("#patient_filter").val();
                d.status = $("#status").val();
            }'
        ]);

        $builder->parameters([
            'lengthChange' => false,
            'searching' => true,
        ]);

        $dataTable = $builder->columns([

            'orderno' => [
                'data' => 'orderno',
                'name' => 'orderno',
                'title' => 'Order No'
            ],

            'order_source' => [
                'data' => 'order_source',
                'name' => 'order_source',
                'title' => 'Source'
            ],

            'user' => [
                'data' => 'user',
                'name' => 'users.name',
                'title' => 'User Name[Mobile]'
            ],

            'address' => [
                'data' => 'address',
                'name' => 'addresses.address',
                'title' => 'Address',
                'searchable' => false
            ],

            'total' => [
                'data' => 'total',
                'name' => 'orders.total',
                'title' => 'Price',
                'searchable' => false,
            ],

            'status' => [
                'data' => 'status',
                'name' => 'orders.status',
                'title' => 'Status'
            ],

            'created_at' => [
                'data' => 'created_at',
                'name' => 'orders.created_at',
                'title' => 'Created At',
                'searchable' => false,
            ],

        ])->addAction([
            'defaultContent' => '',
            'data' => 'action',
            'name' => 'action',
            'title' => 'Action',
            'orderable' => false,
            'searchable' => false,
            'exportable' => false,
            'printable' => true,
        ]);

        return view('admin.order.cart-order',compact('dataTable', 'patients'));
    }

    public function cartOrdersdetails(Request $request,$id)
    {
        $order = Order::find($id);

        if(!$order){
            return redirect()->route('cart-orders')
            ->withErrors('Cart Order not found');
        }

        if($order->type!='cart'){
            return redirect()->route('cart-orders')
            ->withErrors('Select cart Order Only');
        }

        if ($request->ajax()) {

            $items = OrderItem::where('order_id',$order->id);

            if ($request->has('search') && !empty($request->search['value'])) {
                $search = $request->search['value'];

                $items->where(function ($query) use ($search) {
                    $query->where('name', 'LIKE', "%{$search}%")
                        ->orWhere('amount', 'LIKE', "%{$search}%")
                        ->orWhere('quantity', 'LIKE', "%{$search}%");
                });
            }

            return DataTables::of($items)

            ->addColumn('product',function($row){

                $image = asset('images/no-image.png');

                $product = \App\Models\Product::with('images')
                            ->where('name',$row->name)
                            ->first();

                if($product){

                    if($product->images->count()){
                        $image = $product->images->first()->image_path;
                    }

                    elseif($product->image_path){
                        $image = $product->image_path;
                    }

                }

                elseif($row->image_path){
                    $image = $row->image_path;
                }

                return '
                <div class="d-flex align-items-center">
                    <img src="'.$image.'" class="bg-gray-300 rounded me-3" height="64">

                    <p class="m-0 d-inline-block align-middle fs-16">
                        <span class="text-body">'.$row->name.'</span>
                    </p>
                </div>';
            })

            ->addColumn('price',function($row){
                return '₹'.$row->amount;
            })

            ->addColumn('quantity',function($row){
                return $row->quantity;
            })

            ->addColumn('total',function($row){
                return '₹'.$row->amount * $row->quantity;
            })

            ->rawColumns(['product','quantity','action'])

            ->toJson();
        }

        $user = User::select('id','name','mobile','email')
        ->where('id',$order->user_id)
        ->first();

        $address = Address::select('address','pincode','city','state','country','building')
        ->where('id',$order->address_id)
        ->first();

        $totalProducts = OrderItem::where('order_id',$order->id)->count();

        $url = route('cart-orders-details',$order->id);

        $builder = app('datatables.html');

        $builder->ajax($url);

        $builder->parameters([
            'lengthChange'=>false,
            'searching'=>true,
            'paging'=>false,
            'info'=>false,
        ]);

        $dataTable = $builder->columns([

            'product'=>[
                'data'=>'product',
                'name'=>'name',
                'title'=>'Product',
                'orderable'=>false,
                'searchable'=>true
            ],

            'price'=>[
                'data'=>'price',
                'name'=>'amount',
                'title'=>'Price',
            ],

            'quantity'=>[
                'data'=>'quantity',
                'name'=>'quantity',
                'title'=>'Quantity',
                'orderable'=>false,
                'searchable'=>true
            ],

            'total'=>[
                'data'=>'total',
                'name'=>'amount',
                'title'=>'Total',
            ],

        ])->addAction([
            'defaultContent'=>'',
            'data'=>'action',
            'name'=>'action',
            'title'=>'',
            'orderable'=>false,
            'searchable'=>false,
        ]);

        return view(
            'admin.order.cart-order-details',
            compact('order','user','address','dataTable','totalProducts')
        );
    }

    public function updateCartOrderStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:accept,reject,delivered,cancel',
        ]);

        $order = Order::find($id);

        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Order not found']);
        }

        if ($request->status == 'pending') {
            return response()->json(['success' => false, 'message' => 'Cannot set status back to Pending']);
        }

        $order->status = $request->status;
        $order->save();

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully',
            'status'  => ucfirst($order->status)
        ]);
    }

    public function prescriptionOrderDetails($id)
    {
        $order = Order::with('items')->find($id);
        // dd($order);

        if($order){

            if($order->type!='prescription'){
                return redirect()->route('prescription-orders')
                ->withErrors('Select Prescription Order Only');
            }

            $user = User::select('id','name','mobile','email')
            ->where('id',$order->user_id)
            ->first();

            $address = Address::select('address','pincode','city','state','country','building')
            ->where('id',$order->address_id)
            ->first();

            $appointment = null;
            $doctor = null;

            if($order->appointment_id){

                $appointment = BookAppointment::where('id', $order->appointment_id)->first();

                if($appointment){
                    $doctor = User::select('id','name','mobile')
                    ->where('id',$appointment->doctor_id)
                    ->first();
                }
            }

            return view(
                'admin.order.prescription-order-details',
                compact('order','user','address','appointment','doctor')
            );

        }else{

            return redirect()->route('prescription-orders')
            ->withErrors('Prescription Order not found');
        }
    }

    public function priceUpdate(Request $request,$id)
    {
        $request->validate([
            'orderprice' => 'required|numeric|gt:0'
        ]);
        $order = Order::select('id','total','type')->where('id',$id)->first();
        if($order){
            if($order->type!='prescription'){
                return redirect()->route('prescription-orders')->withErrors('Select Prescription Order Only');
            }
            if($order->transaction_id){
                return redirect()->route('prescription-orders.show')->withErrors("Already paid, you can't update price");
            }
            $order->total = $request->orderprice;
            $order->save();
            return redirect()->route('prescription-orders.show',$id)->withSuccess("Price Updated");
        }else{
            return redirect()->route('prescription-orders')->withErrors(['error'=>'Prescription Order not found']);
        }
    }

    public function prescriptionOrderStatusUpdate(Request $request)
    {
        $status = $request->status;
        $id = $request->id;
        $order = Order::find($id);
        if($order){
            if($status=='accept'){
                $price = $request->price;
                if(!$price){
                    return response()->json(['status'=>2,'message'=>'Price Required']);
                }
                $order->total = $price;
            }
            $order->status=$status;
            $order->save();
            return response()->json(['status'=>1,'message'=>'Status Updated']);
        }else{
            return response()->json(['status'=>0,'message'=>'Order not found']);
        }
    }
    public function prescriptiondeliveryStatus(Request $request, $id)
    {
        $request->validate([
            'delivery_status' => 'required|in:delivered',
        ]);

        $order = Order::find($id);

        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Order not found']);
        }

        $order->delivery_status = $request->delivery_status;
        $order->save();

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully',
            'status'  => ucfirst($order->delivery_status)
        ]);
    }

}
