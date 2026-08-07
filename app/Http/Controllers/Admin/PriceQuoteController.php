<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PriceQuote;
use Yajra\DataTables\Facades\DataTables;
use App\Helpers\CustomHelper;
use App\Models\Order;
use App\Models\OrderItem;

class PriceQuoteController extends Controller
{
    // public function index(Request $request)
    // {
    //     if ($request->ajax()) {

    //         $quotes = PriceQuote::query()
    //             ->leftJoin('users', 'price_quotes.user_id', '=', 'users.id')
    //             ->select('price_quotes.*', 'users.name as user_name')
    //             ->orderBy('price_quotes.id', 'DESC');

    //         return DataTables::of($quotes)
    //             ->addColumn('user_name', function ($row) {
    //                 return $row->user_name ?? '-';
    //             })
    //             ->addColumn('notes', function ($row) {
    //                 return $row->notes ?? '-';
    //             })
    //             ->addColumn('price', function ($row) {
    //                 return $row->price ?? '-';
    //             })
    //             ->addColumn('images', function ($row) {
    //                 if(!$row->images){
    //                     return 'No Image';
    //                 }
    //                 $image = trim($row->images,'"');
    //                 return '<a href="'.asset('storage/'.$image).'" target="_blank">
    //                             <img src="'.asset('storage/'.$image).'" width="60">
    //                         </a>';
    //             })
    //             ->addColumn('status', function ($row) {

    //                 if ($row->status == 'approved') {
    //                     return '<span class="badge bg-success text-dark">'.$row->status.'</span>';
    //                 } elseif ($row->status == 'pending') {
    //                     return '<span class="badge bg-warning text-dark">'.$row->status.'</span>';
    //                 } elseif ($row->status == 'rejected') {
    //                     return '<span class="badge bg-danger text-dark">'.$row->status.'</span>';
    //                 } else {
    //                     return '<span class="badge bg-secondary text-dark">'.$row->status.'</span>';
    //                 }

    //             })
    //             ->addColumn('action', function ($row) {
    //                 return '
    //                     <a class="btn btn-sm btn-secondary" href="'.route('price.quote.requests.edit',$row->id).'">Edit</a>

    //                     <a class="btn btn-sm btn-danger delete_item_from_list"
    //                     data-url="'.route('price.quote.requests.destroy',$row->id).'"
    //                     data-id="'.$row->id.'">Delete</a>
    //                 ';
    //             })
    //             ->rawColumns(['status','action','images'])
    //             ->toJson();
    //     }

    //     $url = route('price.quote.requests.index');

    //     $builder = app('datatables.html');

    //     $builder->ajax($url);

    //     $builder->parameters([
    //         'lengthChange' => false,
    //         'searching' => false,
    //     ]);

    //     $dataTable = $builder->columns([

    //         'user_name' => [
    //             'data' => 'user_name',
    //             'name' => 'users.name',
    //             'title' => 'User Name'
    //         ],

    //         'notes' => [
    //             'data' => 'notes',
    //             'name' => 'price_quotes.notes',
    //             'title' => 'Notes'
    //         ],

    //         'price' => [
    //             'data' => 'price',
    //             'name' => 'price_quotes.price',
    //             'title' => 'Price'
    //         ],

    //         'images' => [
    //             'data' => 'images',
    //             'name' => 'price_quotes.images',
    //             'title' => 'Images'
    //         ],

    //         'status' => [
    //             'data' => 'status',
    //             'name' => 'price_quotes.status',
    //             'title' => 'Status'
    //         ],

    //     ])->addAction([
    //         'defaultContent' => '',
    //         'data' => 'action',
    //         'name' => 'action',
    //         'title' => 'Action',
    //         'orderable' => false,
    //         'searchable' => false,
    //         'exportable' => false,
    //         'printable' => true,
    //     ]);
        
    //     return view('admin.price-quotes.index', compact('dataTable'));
    // }

    // public function index(Request $request)
    // {
    //     if ($request->ajax()) {

    //         $quotes = PriceQuote::query()
    //             ->leftJoin('users', 'price_quotes.user_id', '=', 'users.id')
    //             ->select(
    //                 'price_quotes.*',
    //                 'users.name as user_name'
    //             );

    //         return DataTables::of($quotes)

    //             ->filter(function ($query) use ($request) {

    //                 if ($request->has('search') && $request->search['value'] != '') {

    //                     $search = $request->search['value'];

    //                     $query->where(function ($q) use ($search) {
    //                         $q->where('users.name', 'LIKE', "%{$search}%")
    //                         ->orWhere('price_quotes.notes', 'LIKE', "%{$search}%")
    //                         ->orWhere('price_quotes.price', 'LIKE', "%{$search}%")
    //                         ->orWhere('price_quotes.status', 'LIKE', "%{$search}%");
    //                     });
    //                 }
    //             })

    //             ->addColumn('user_name', function ($row) {
    //                 return $row->user_name ?? '-';
    //             })

    //             ->addColumn('notes', function ($row) {
    //                 return $row->notes ?? '-';
    //             })

    //             ->addColumn('price', function ($row) {
    //                 return $row->price ?? '-';
    //             })

    //             ->addColumn('images', function ($row) {

    //                 if (!$row->images) {
    //                     return 'No Image';
    //                 }

    //                 $image = trim($row->images, '"');

    //                 return '<a href="'.asset('storage/'.$image).'" target="_blank">
    //                             <img src="'.asset('storage/'.$image).'" width="60">
    //                         </a>';
    //             })

    //             ->addColumn('status', function ($row) {

    //                 if ($row->status == 'approved') {
    //                     return '<span class="badge bg-success">'.$row->status.'</span>';
    //                 } elseif ($row->status == 'pending') {
    //                     return '<span class="badge bg-warning text-dark">'.$row->status.'</span>';
    //                 } elseif ($row->status == 'rejected') {
    //                     return '<span class="badge bg-danger">'.$row->status.'</span>';
    //                 } else {
    //                     return '<span class="badge bg-secondary">'.$row->status.'</span>';
    //                 }

    //             })

    //             ->addColumn('action', function ($row) {
    //                 return '
    //                     <a class="btn btn-sm btn-secondary" href="'.route('price.quote.requests.edit',$row->id).'">Edit</a>

    //                     <a class="btn btn-sm btn-danger delete_item_from_list"
    //                     data-url="'.route('price.quote.requests.destroy',$row->id).'"
    //                     data-id="'.$row->id.'">Delete</a>
    //                 ';
    //             })

    //             ->rawColumns(['status','action','images'])
    //             ->orderColumn('user_name', 'users.name $1')
    //             ->orderColumn('price', 'price_quotes.price $1')
    //             ->orderColumn('status', 'price_quotes.status $1')
    //             ->toJson();
    //     }

    //     $url = route('price.quote.requests.index');

    //     $builder = app('datatables.html');

    //     $builder->ajax($url);

    //     $builder->parameters([
    //         'lengthChange' => true,
    //         'searching' => true,
    //         'processing' => true,
    //         'serverSide' => true,
    //     ]);

    //     $dataTable = $builder->columns([

    //         'user_name' => [
    //             'data' => 'user_name',
    //             'name' => 'users.name',
    //             'title' => 'User Name'
    //         ],

    //         'notes' => [
    //             'data' => 'notes',
    //             'name' => 'price_quotes.notes',
    //             'title' => 'Notes'
    //         ],

    //         'price' => [
    //             'data' => 'price',
    //             'name' => 'price_quotes.price',
    //             'title' => 'Price'
    //         ],

    //         'images' => [
    //             'data' => 'images',
    //             'name' => 'price_quotes.images',
    //             'title' => 'Images',
    //             'searchable' => false,
    //             'orderable' => false
    //         ],

    //         'status' => [
    //             'data' => 'status',
    //             'name' => 'price_quotes.status',
    //             'title' => 'Status'
    //         ],

    //     ])->addAction([
    //         'defaultContent' => '',
    //         'data' => 'action',
    //         'name' => 'action',
    //         'title' => 'Action',
    //         'orderable' => false,
    //         'searchable' => false,
    //     ]);

    //     return view('admin.price-quotes.index', compact('dataTable'));
    // }

    public function index(Request $request)
    {
        if ($request->ajax()) {

            $orders = Order::query()
                ->where('orders.type','prescription')
                ->leftJoin('users', 'orders.user_id', '=', 'users.id')
                ->select(
                    'orders.*',
                    'users.name as user_name'
                );

            return DataTables::of($orders)

                ->filter(function ($query) use ($request) {

                    if ($request->has('search') && $request->search['value'] != '') {

                        $search = $request->search['value'];

                        $query->where(function ($q) use ($search) {
                            $q->where('users.name', 'LIKE', "%{$search}%")
                            ->orWhere('orders.notes', 'LIKE', "%{$search}%")
                            ->orWhere('orders.status', 'LIKE', "%{$search}%")
                            ->orWhere('orders.orderno', 'LIKE', "%{$search}%");
                        });
                    }
                })

                ->addColumn('user_name', function ($row) {
                    return $row->user_name ?? '-';
                })

                ->addColumn('orderno', function ($row) {
                    return $row->orderno ?? '-';
                })

                ->addColumn('notes', function ($row) {
                    return $row->notes ?? '-';
                })

                ->addColumn('images', function ($row) {

                    $images = OrderItem::where('order_id',$row->id)->pluck('image');

                    if($images->isEmpty()){
                        return 'No Image';
                    }

                    $html = '';

                    foreach ($images as $img) {

                        $html .= '<a href="'.asset('storage/'.$img).'" target="_blank">
                                    <img src="'.asset('storage/'.$img).'" width="60" style="margin-right:5px">
                                </a>';
                    }

                    return $html;
                })

                ->addColumn('status', function ($row) {

                    if ($row->status == 'accept') {
                        return '<span class="badge bg-success">Accepted</span>';
                    } elseif ($row->status == 'reject') {
                        return '<span class="badge bg-danger">Rejected</span>';
                    } elseif ($row->status == 'cancel') {
                        return '<span class="badge bg-secondary">Cancelled</span>';
                    } else {
                        return '<span class="badge bg-warning text-dark">Pending</span>';
                    }

                })

                ->addColumn('action', function ($row) {

                    return '
                        <a class="btn btn-sm btn-secondary" href="'.route('price.quote.requests.edit',$row->id).'">Edit</a>

                        <a class="btn btn-sm btn-danger delete_item_from_list"
                        data-url="'.route('price.quote.requests.destroy',$row->id).'"
                        data-id="'.$row->id.'">Delete</a>
                    ';
                })

                ->rawColumns(['status','action','images'])
                ->orderColumn('user_name', 'users.name $1')
                ->orderColumn('status', 'orders.status $1')
                ->orderColumn('orderno', 'orders.orderno $1')
                ->toJson();
        }

        $url = route('price.quote.requests.index');

        $builder = app('datatables.html');

        $builder->ajax($url);

        $builder->parameters([
            'lengthChange' => true,
            'searching' => true,
            'processing' => true,
            'serverSide' => true,
        ]);

        $dataTable = $builder->columns([

            'orderno' => [
                'data' => 'orderno',
                'name' => 'orders.orderno',
                'title' => 'Order No'
            ],

            'user_name' => [
                'data' => 'user_name',
                'name' => 'users.name',
                'title' => 'User Name'
            ],

            'notes' => [
                'data' => 'notes',
                'name' => 'orders.notes',
                'title' => 'Notes'
            ],

            'images' => [
                'data' => 'images',
                'name' => 'images',
                'title' => 'Images',
                'searchable' => false,
                'orderable' => false
            ],

            'status' => [
                'data' => 'status',
                'name' => 'orders.status',
                'title' => 'Status'
            ],

        ])->addAction([
            'defaultContent' => '',
            'data' => 'action',
            'name' => 'action',
            'title' => 'Action',
            'orderable' => false,
            'searchable' => false,
        ]);

        return view('admin.price-quotes.index', compact('dataTable'));
    }

    // public function edit($id)
    // {
    //     $quote = PriceQuote::with(['user','address'])->findOrFail($id);

    //     return view('admin.price-quotes.edit', compact('quote'));
    // }

    public function edit($id)
    {
        $quote = Order::with('items','user')->findOrFail($id);

        return view('admin.price-quotes.edit', compact('quote'));
    }

    // public function update(Request $request, $id)
    // {
    //     $request->validate([
    //         'notes' => 'nullable|string',
    //         'price' => 'nullable|numeric',
    //         'status' => 'required|in:pending,quoted,approved,rejected',
    //     ]);

    //     $quote = PriceQuote::findOrFail($id);

    //     $quote->update([
    //         'notes' => $request->notes,
    //         'price' => $request->price,
    //         'status' => $request->status,
    //     ]);

    //     return redirect()
    //         ->route('price.quote.requests.index')
    //         ->with('success','Price quote updated successfully');
    // }

    public function update(Request $request,$id)
    {
        $request->validate([
            'notes' => 'nullable',
            'status' => 'required|in:pending,accept,reject,cancel'
        ]);

        $order = Order::findOrFail($id);

        $order->update([
            'status' => $request->status
        ]);

        return redirect()->route('price.quote.requests.index')->with('success','Prescription updated successfully');
    }

    // public function destroy(string $id)
    // {
    //     $pricequote = PriceQuote::find($id);
    //     CustomHelper::removeExistingFileFromStorage($pricequote->image);
    //     $pricequote->delete();

    //     return response()->json(['status'=>1,'message'=>'Banner Deleted']);
    // }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);

        foreach ($order->items as $item) {
            CustomHelper::removeExistingFileFromStorage($item->image);
        }

        $order->items()->delete();
        $order->delete();

        return response()->json([
            'status'=>1,
            'message'=>'Prescription deleted'
        ]);
    }
}
