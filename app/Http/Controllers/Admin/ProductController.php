<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Product;
use App\Models\ProductPrice;
use App\Models\ProductImage;
use App\Models\Category;
use App\Models\TrendingCategory;
use App\Models\OrderItem;
use Yajra\DataTables\Facades\DataTables;
use App\Helpers\CustomHelper;
use Illuminate\Support\Facades\Validator;
class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        if ($request->ajax()) {

            $listQry = Product::query()
                                ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
                                ->select([
                                    'products.id',
                                    'products.name',
                                    'products.image',
                                    'products.status',
                                    'products.created_at',
                                    'products.category_id',
                                    'categories.name as category_name'
                                ]);

            if($request->has('category_id') && $request->category_id != ''){
                $catId = $request->category_id;
                $categoryIds = Category::where(function ($q) use ($catId) {
                    $q->where('id', $catId)
                    ->orWhere('parent_id', $catId);
                })->pluck('id');

                if ($categoryIds->isNotEmpty()) {
                    $listQry->whereIn('products.category_id', $categoryIds);
                }
            }

            if (!empty($request->start_date) && !empty($request->end_date)) {
                $listQry->whereBetween('products.created_at', [
                    $request->start_date . ' 00:00:00', 
                    $request->end_date . ' 23:59:59'
                ]);
            }

            $lists = $listQry->orderBy('products.id', 'DESC');

            return DataTables::of($lists)

            ->addColumn('image',function($row){
                return '<a href="'.$row->image_path.'" target="_blank">
                            <img src="'.$row->image_path.'" border="0" width="50" class="img-rounded" />
                        </a>';
            })

            ->addColumn('status',function($row){

                if($row->status==1){
                    $status = '<button type="button" class="btn btn-sm btn-toggle active listStatusUpdate"
                    data-bs-toggle="button"
                    data-status="0"
                    data-itemid="'.$row->id.'"
                    data-url="'.route('product-status-update').'">
                    <span class="handle"></span>
                    </button>';
                }else{
                    $status = '<button type="button" class="btn btn-sm btn-toggle listStatusUpdate"
                    data-bs-toggle="button"
                    data-status="1"
                    data-itemid="'.$row->id.'"
                    data-url="'.route('product-status-update').'">
                    <span class="handle"></span>
                    </button>';
                }

                return $status;
            })

            ->addColumn('category',function($row){
                return $row->category_name ?? '';
            })

            ->addColumn('created_at',function($row){
                return date('Y-m-d g:i a',strtotime($row->created_at));
            })

            ->addColumn('action',function($row){
                return '<a class="btn btn-sm btn-secondary"
                href="'.route('products.edit', $row->id).'">Edit</a>';
            })

            ->rawColumns(['action','image','status'])
            ->toJson();
        }

        $url = url('products');

        $categories = Category::select('id','name')->orderBy('name')->get();

        $builder = app('datatables.html');

        $builder->ajax([
            'url'=>$url,
            'data'=>"function(d){
                let category = $('#category_filter').val();
                let start    = $('#start_date_value').val();
                let end      = $('#end_date_value').val();

                if (category) {
                    d.category_id = category;
                }
                
                if (start && end) {
                    d.start_date = start;
                    d.end_date = end;
                }
            }"
        ]);

        $builder->parameters([
            'lengthChange' => false,
            'searching' => true,
            'stateSave' => true,
            'deferLoading' => 0
        ]);

        $dataTable = $builder->columns([

            'image' => [
                'data'=>'image',
                'name'=>'products.image',
                'title'=>'Image',
                'searchable'=>false
            ],

            'category' => [
                'data'=>'category',
                'name'=>'categories.name',
                'title'=>'Category'
            ],

            'name' => [
                'data'=>'name',
                'name'=>'products.name',
                'title'=>'Name'
            ],

            'status' => [
                'data'=>'status',
                'name'=>'products.status',
                'title'=>'Status'
            ],

            'created_at' => [
                'data'=>'created_at',
                'name'=>'products.created_at',
                'title'=>'CreatedAt'
            ],

        ])->addAction([
            'defaultContent'=>'',
            'data'=>'action',
            'name'=>'action',
            'title'=>'Action',
            'orderable'=>false,
            'searchable'=>false,
        ]);

        return view('admin.product.index',compact('dataTable','categories'));
    }

    // d.category_id = $('#category_filter').val();
    // d.start_date = $('#start_date_value').val();
    // d.end_date = $('#end_date_value').val();

    public function statusUpdate(Request $request)
    {
        $item = Product::select('id','status')->where('id',$request->id)->first();
        if($item){
            $item->status = $request->status;
            $item->save();
            return response()->json(['status'=>1,'message'=>"Status Updated Successfully"]);
        }else{
            return response()->json(['status'=>0,'message'=>"Not found"]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::select('id','name')->whereNull('parent_id')->get();
        $trending_categories = TrendingCategory::select('id','name')->get();
        return view('admin.product.create',compact('categories','trending_categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'category_id' => 'required',
            'name' => 'required',
            'image' => 'required|file|mimes:jpeg,jpg,png,webp,gif',
            'product_images' => 'required|array',
            'product_images.*' => 'required|file|mimes:jpeg,jpg,png,webp,gif',
            'pack_size' => 'required|array|min:1',
            'pack_price' => 'required|array|min:1',
            'pack_size.0' => 'required',
            'pack_price.0' => 'required',
        ];

        $messages = [
            'pack_size.0.required' => 'You must provide at least one pack size.',
            'pack_price.0.required' => 'You must provide at least one pack price.',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        $validator->after(function ($validator) use ($request) {
            $pairs = [
                'pack' => ['size', 'price','special_price']
            ];
            foreach ($pairs as $prefix => [$sizeKey, $priceKey, $specialPriceKey]) {
                $sizeField = "{$prefix}_{$sizeKey}";
                $priceField = "{$prefix}_{$priceKey}";
                $specialPriceField = "{$prefix}_{$specialPriceKey}";

                $packSize = $request->input($sizeField, []);
                $packPrice = $request->input($priceField, []);
                $packSpecialPrice = $request->input($specialPriceField, []);

                $count = max(count($packSize), count($packPrice));

                if ($count === 0) {
                    $validator->errors()->add($sizeField, "You must add at least one product pack.");
                }
                for ($i = 0; $i < $count; $i++) {
                    $size = $packSize[$i] ?? '';
                    $price = $packPrice[$i] ?? '';

                    if (empty($size) || empty($price)) {
                        $validator->errors()->add("{$sizeField}.{$i}", "Size must be present at index {$i}.");
                        $validator->errors()->add("{$priceField}.{$i}", "Price must be present at index {$i}.");
                    }
                }
            }
        });
        if ($validator->passes()) {
            try{
                $product = new Product;
                $product->name = $request->name;
                $product->image = $request->image->store('product', 'public');
                $product->category_id = $request->category_id;
                $product->description = $request->description;
                if(!empty($request->medicine_power)){
                    $product->medicine_power = implode(',',$request->medicine_power);
                }else{
                    $product->medicine_power = null;
                }
                $product->save();

                $product_id = $product->id;

                if (!empty($request->product_images)) {
                    foreach ($request->product_images as $key => $value) {
                        $product_image = new ProductImage;
                        $product_image->product_id = $product_id;
                        $product_image->image = $value->store('product', 'public');
                        $product_image->save();
                    }
                }
                // Multiple Article Insert
                $pack_size = ($request->pack_size) ? $request->pack_size : [];
                $pack_price = ($request->pack_price) ? $request->pack_price : [];
                $pack_special_price = ($request->pack_special_price) ? $request->pack_special_price : [];
                $pack_count = max(count($pack_size), count($pack_price), count($pack_special_price)); // ensure we cover all indices
                if($pack_count>0){
                    for ($i = 0; $i < $pack_count; $i++) {
                        $product_price = new ProductPrice;
                        $product_price->product_id = $product_id;
                        $product_price->pack_size = $pack_size[$i];
                        $product_price->price = $pack_price[$i];
                        $product_price->special_price = $pack_special_price[$i];
                        $product_price->save();
                    }
                }
                $trending_category_ids = $request->trending_category_id ? $request->trending_category_id : [];
                $product->trending_categories()->sync($trending_category_ids);
                return redirect()->route('products.index')->withSuccess('Product Added Successfully');
            }catch(\Exception $e){
                return redirect()->back()->withErrors(['error'=>$e->getMessage()])->withInput();
            }
        }else{
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request,string $id)
    {
        $product = Product::find($id);
        if($product){
            $categories = Category::select('id','name')->whereNull('parent_id')->get();
            $trending_categories = TrendingCategory::select('id','name')->get();
            // $redirect = $request->get('redirect');
            return view('admin.product.edit',compact('categories','trending_categories','product'));
        }else{
            return redirect()->route('products.index')->withErrors(['error'=>'Product not found']);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $rules = [
            'category_id' => 'required',
            'name' => 'required',
            'image' => 'nullable|file|mimes:jpeg,jpg,png,webp,gif',
            'product_images' => 'nullable|array',
            'product_images.*' => 'nullable|file|mimes:jpeg,jpg,png,webp,gif',
            'pack_size' => 'required|array|min:1',
            'pack_price' => 'required|array|min:1',
        ];
        $validator = Validator::make($request->all(), $rules);
        $validator->after(function ($validator) use ($request) {
            $pairs = [
                'pack' => ['size', 'price','special_price']
            ];
            foreach ($pairs as $prefix => [$sizeKey, $priceKey, $specialPriceKey]) {
                $sizeField = "{$prefix}_{$sizeKey}";
                $priceField = "{$prefix}_{$priceKey}";
                $specialPriceField = "{$prefix}_{$specialPriceKey}";

                $packSize = $request->input($sizeField, []);
                $packPrice = $request->input($priceField, []);
                $packSpecialPrice = $request->input($specialPriceField, []);

                $count = max(count($packSize), count($packPrice)); // ensure we cover all indices, count($packSpecialPrice)
                if ($count === 0) {
                    $validator->errors()->add($sizeField, "You must add at least one product pack.");
                }
                for ($i = 0; $i < $count; $i++) {
                    $size = $packSize[$i] ?? '';
                    $price = $packPrice[$i] ?? '';

                    if (empty($size) || empty($price)) {
                        $validator->errors()->add("{$sizeField}.{$i}", "Size must be present at index {$i}.");
                        $validator->errors()->add("{$priceField}.{$i}", "Price must be present at index {$i}.");
                    }
                }
            }
        });
        if ($validator->passes()) {
            try{
                $product = Product::find($id);
                $product->name = $request->name;
                if($request->hasFile('image')){
                    $orderExists = OrderItem::select('id')->where('product_id',$id)->first();
                    if(!$orderExists){
                        CustomHelper::removeExistingFileFromStorage($product->image);
                    }
                    $product->image = $request->image->store('product', 'public');
                }
                $product->category_id = $request->category_id;
                $product->description = $request->description;
                if(!empty($request->medicine_power)){
                    $product->medicine_power = implode(',',$request->medicine_power);
                }else{
                    $product->medicine_power = null;
                }
                
                $product->save();

                $product_id = $product->id;

                if (!empty($request->product_images)) {
                    foreach ($request->product_images as $key => $value) {
                        $product_image = new ProductImage;
                        $product_image->product_id = $product_id;
                        $product_image->image = $value->store('product', 'public');
                        $product_image->save();
                    }
                }
                // Multiple Article Insert
                $pack_size = ($request->pack_size) ? $request->pack_size : [];
                $pack_price = ($request->pack_price) ? $request->pack_price : [];
                $pack_special_price = ($request->pack_special_price) ? $request->pack_special_price : [];
                $pack_count = max(count($pack_size), count($pack_price), count($pack_special_price)); // ensure we cover all indices
                if($pack_count>0){
                    $product_price_ids = $request->product_price_id;
                    for ($i = 0; $i < $pack_count; $i++) {
                        if($product_price_ids[$i]){
                            $product_price = ProductPrice::find($product_price_ids[$i]);
                        }else{
                            $product_price = new ProductPrice;
                            $product_price->product_id = $product_id;
                        }
                        $product_price->pack_size = $pack_size[$i];
                        $product_price->price = $pack_price[$i];
                        $product_price->special_price = $pack_special_price[$i];
                        $product_price->save();
                    }
                }
                $trending_category_ids = $request->trending_category_id ? $request->trending_category_id : [];
                $product->trending_categories()->sync($trending_category_ids);
                // if($request->redirect){
                //     return redirect($request->redirect)->withSuccess('Product Updated Successfully');
                // }

                return redirect()->route('products.index')->withSuccess('Product Updated Successfully');
            }catch(\Exception $e){
                return redirect()->back()->withErrors(['error'=>$e->getMessage()])->withInput();
            }
        }else{
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function productPriceDelete($id)
    {
        $product_price = ProductPrice::find($id);
        if($product_price){
            $product_price->delete();
            return response()->json(['status'=>1,'message'=>'ProductPrice deleted']);
        }else{
            return response()->json(['status'=>0,'message'=>'ProductPrice not found']);
        }
    }
    public function productImageDelete($id)
    {
        $product_image = ProductImage::find($id);
        if($product_image){
            CustomHelper::removeExistingFileFromStorage($product_image->image);//This is a helper function
            $product_image->delete();
            return response()->json(['status'=>1,'message'=>'Product Image deleted']);
        }else{
            return response()->json(['status'=>0,'message'=>'Product Image not found']);
        }
    }
}
