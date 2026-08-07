<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Order;
use App\Models\Address;
use App\Models\User;

class OrderController extends Controller
{
    public function prescriptionOrders(Request $request)
    {
        if (request()->ajax()) {
            $listQry = Order::where('type','prescription');
            $lists = $listQry->orderBy('id', 'DESC');

            return \DataTables::of($lists)->addColumn('status',function($row){
                $statusHtml = $status = $row->status;
                if($status=='pending'){
                    $statusHtml = '<span class="badge badge-sm badge-danger-light">Pending</span>';
                }elseif ($status=='accept') {
                    $statusHtml = '<span class="badge badge-sm badge-success-light">Accepted</span>';
                }else{
                    $statusHtml = '<span class="badge badge-sm badge-warning-light">'.ucfirst($status).'ed</span>';
                }
                return $statusHtml;
            })->addColumn('user',function($row){
                $user = User::select('name','mobile')->where('id',$row->user_id)->first();
                return $user->name.'['.$user->mobile.']';
            })->addColumn('address',function($row){
                $address = Address::select('address','pincode','city','state','country','building')->where('id',$row->address_id)->first();
                $new_address = '';
                if ($address->building) {
                    $new_address = $address->building.', ';
                }
                return $new_address.$address->address.', '.$address->city.', '.$address->state.' - '.$address->pincode.', '.$address->country;
            })->addColumn('created_at',function($row){
                return date('Y-m-d g:i a',strtotime($row->created_at));
            })->addColumn('action',function($row){
                return '
                        <a class="btn btn-sm btn-primary" href="'.route('prescription-orders.show',$row->id).'">Details</a>
                        ';
            })->rawColumns(['action','status'])->toJson();
        }
        $url = url('prescription-orders');
        $builder = app('datatables.html');
        $builder->ajax($url);
        $builder->parameters([
            'lengthChange' => false,
            // 'pageLength' => 10,
            // 'lengthMenu' => [20],
            'searching' => false,//13-10-2025
        ]);
        
        $dataTable = $builder->columns([
            'orderno' => ['data' => 'orderno', 'name' => 'orderno', 'title' => 'orderno'],
            'user' => ['data' => 'user', 'name' => 'user', 'title' => 'User Name[Mobile]'],
            'address' => ['data' => 'address', 'name' => 'address', 'title' => 'Address'],
            'total' => ['data' => 'total', 'name' => 'total', 'title' => 'Price'],
            'status' => ['data' => 'status', 'name' => 'status', 'title' => 'Status'],
            'created_at' => ['data' => 'created_at', 'name' => 'created_at', 'title' => 'CreatedAt'],
        ])->addAction([
            'defaultContent' => '',
            'data'           => 'action',
            'name'           => 'action',
            'title'          => 'Action',
            'render'         => null,
            'orderable'      => false,
            'searchable'     => false,
            'exportable'     => false,
            'printable'      => true,
            'footer'         => '',
        ]);
        return view('admin.order.prescription-order',compact('dataTable'));
    }
    public function prescriptionOrderDetails($id)
    {
        $order = Order::find($id);
        if($order){
            if($order->type!='prescription'){
                return redirect()->route('prescription-orders')->withErrors('Select Prescription Order Only');
            }
            $user = User::select('id','name','mobile','email')->where('id',$order->user_id)->first();
            $address = Address::select('address','pincode','city','state','country','building')->where('id',$order->address_id)->first();
            return view('admin.order.prescription-order-details',compact('order','user','address'));
        }else{
            return redirect()->route('prescription-orders')->withErrors('Prescription Order not found');
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
}
