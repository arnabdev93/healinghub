<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Category;
use App\Helpers\CustomHelper;
class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (request()->ajax()) {
            $listQry = Category::query()
                                ->leftJoin('categories as parent','categories.parent_id','=','parent.id')
                                ->select(
                                    'categories.*',
                                    'parent.name as parent_name'
                                );

            if (!empty($request->parent_id)) {
                $listQry->where('categories.parent_id', $request->parent_id);
            }

            if (!empty($request->start_date) && !empty($request->end_date)) {
                $listQry->whereBetween('categories.created_at', [
                    $request->start_date . ' 00:00:00',
                    $request->end_date . ' 23:59:59'
                ]);
            }

            $lists = $listQry->orderBy('categories.id', 'DESC');

            return DataTables::of($lists)->addColumn('image',function($row){
                return '<a href="'.$row->image_path.'" target="_blank"><img src="' . $row->image_path . '" border="0" width="50" class="img-rounded" align="center" /></a>';
            })->addColumn('status',function($row){
                $status = '';
                if($row->parent_id){
                    if($row->status==1){
                        $status = '<button type="button" class="btn btn-sm btn-toggle active listStatusUpdate" data-bs-toggle="button" aria-pressed="true" data-status="0" data-itemid="'.$row->id.'" data-url="'.route('category-status-update').'">
                                    <span class="handle"></span>
                                   </button>';
                    }else{
                        $status = '<button type="button" class="btn btn-sm btn-toggle listStatusUpdate" data-bs-toggle="button" aria-pressed="false" data-status="1" data-itemid="'.$row->id.'" data-url="'.route('category-status-update').'">
                                    <span class="handle"></span>
                                   </button>';
                    }
                }

                return $status;
            })->addColumn('parent_category',function($row){
                return $row->parent_name ?? '';
            })->addColumn('created_at',function($row){
                return date('Y-m-d g:i a',strtotime($row->created_at));
            })->addColumn('action',function($row){
                return ($row->parent_id) ? '
                        <a class="btn btn-sm btn-secondary" href="'.route('categories.edit',$row->id).'">Edit</a>
                        ' : '';
            })->rawColumns(['action','image','status'])->toJson();
        }
        $url = url('categories');
        $builder = app('datatables.html');
        $builder->ajax([
            'url' => $url,
            'data' => "function(d){
                d.parent_id  = $('#category_filter').val();
                d.start_date = $('#start_date_value').val();
                d.end_date   = $('#end_date_value').val();
            }"
        ]);
        $builder->parameters([
            'lengthChange' => false,
            // 'pageLength' => 10,
            // 'lengthMenu' => [20],
            'searching' => true,
            'stateSave' => true,
            'deferLoading' => 0
        ]);

        $dataTable = $builder->columns([

            'image' => [
                'data' => 'image',
                'name' => 'categories.image',
                'title' => 'Image',
                'searchable' => false
            ],

            'parent_category' => [
                'data' => 'parent_category',
                'name' => 'parent.name',
                'title' => 'Parent Category'
            ],

            'name' => [
                'data' => 'name',
                'name' => 'categories.name',
                'title' => 'Name'
            ],

            'status' => [
                'data' => 'status',
                'name' => 'categories.status',
                'title' => 'Status'
            ],

            'created_at' => [
                'data' => 'created_at',
                'name' => 'categories.created_at',
                'title' => 'CreatedAt'
            ],

        ])->addAction([
            'defaultContent' => '',
            'data' => 'action',
            'name' => 'action',
            'title' => 'Action',
            'orderable' => false,
            'searchable' => false,
        ]);

        $categories = Category::whereNull('parent_id')
                ->select('id','name')
                ->orderBy('name')
                ->get();

        return view('admin.category.index',compact('dataTable','categories'));
    }

    public function subCategories(Request $request)
    {
        $parent_id = $request->parent_id;
        $categoryQry = Category::select('id','name');
        if($request->parent_id){
            $categoryQry->where('parent_id',$request->parent_id);
        }
        $categories = $categoryQry->get();
        return response()->json(['status'=>1,'message'=>'Successful','data'=>['categories'=>$categories]]);
    }

    public function statusUpdate(Request $request)
    {
        $item = Category::select('id','status')->where('id',$request->id)->first();
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
        return view('admin.category.create',compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'parent_id' => 'required',
            'name' => 'required|unique:categories,name',
            'image' => 'required|file|mimes:jpeg,jpg,png,webp,gif'
        ]);
        try{
            $category = new Category;
            $category->name = $request->name;
            $category->parent_id = $request->parent_id;
            $category->image = $request->image->store('category','public');
            $category->save();
            return redirect()->route('categories.index')->withSuccess('Category Added Successfully');
        }catch(\Exception $e){
            return redirect()->back()->withErrors(['error'=>$e->getMessage()])->withInput();
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
    public function edit(string $id)
    {
        $category = Category::find($id);
        if($category){
            $categories = Category::select('id','name')->whereNull('parent_id')->get();
            return view('admin.category.edit',compact('category','categories'));
        }else{
            return redirect()->route('categories.index')->withErrors(['error'=>'Category not found']);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'parent_id' => 'required',
            'name' => 'required|unique:categories,name,'.$id,
            'image' => 'nullable|file|mimes:jpeg,jpg,png,webp,gif'
        ]);
        try{
            $category = Category::find($id);
            $category->name = $request->name;
            $category->parent_id = $request->parent_id;
            if($request->hasFile('image')){
                CustomHelper::removeExistingFileFromStorage($category->image);//This is a helper function
                $category->image = $request->image->store('category','public');
            }
            $category->save();
            return redirect()->route('categories.index')->withSuccess('Category Updated Successfully');
        }catch(\Exception $e){
            return redirect()->back()->withErrors(['error'=>$e->getMessage()])->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::find($id);
        // delete from local storage
        CustomHelper::removeExistingFileFromStorage($category->image);//This is a helper function
        $category->delete();

        return response()->json(['status'=>1,'message'=>'Category Deleted']);
    }
}
