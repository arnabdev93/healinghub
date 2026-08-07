<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\TrendingCategory;
use App\Helpers\CustomHelper;
class TrendingCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (request()->ajax()) {
            $listQry = TrendingCategory::query();
            $lists = $listQry->orderBy('id', 'DESC');

            return \DataTables::of($lists)->addColumn('image',function($row){
                return '<a href="'.$row->image_path.'" target="_blank"><img src="' . $row->image_path . '" border="0" width="50" class="img-rounded" align="center" /></a>';
            })->addColumn('status',function($row){
                if($row->status==1){
                    $status = '<button type="button" class="btn btn-sm btn-toggle active listStatusUpdate" data-bs-toggle="button" aria-pressed="true" data-status="0" data-itemid="'.$row->id.'" data-url="'.route('trending-category-status-update').'">
                                <span class="handle"></span>
                               </button>';
                }else{
                    $status = '<button type="button" class="btn btn-sm btn-toggle listStatusUpdate" data-bs-toggle="button" aria-pressed="false" data-status="1" data-itemid="'.$row->id.'" data-url="'.route('trending-category-status-update').'">
                                <span class="handle"></span>
                               </button>';
                }
                return $status;
            })->addColumn('created_at',function($row){
                return date('Y-m-d g:i a',strtotime($row->created_at));
            })->addColumn('action',function($row){
                return '
                        <a class="btn btn-sm btn-secondary" href="'.route('trending-categories.edit',$row->id).'">Edit</a>
                        ';
            })->rawColumns(['action','image','status'])->toJson();
        }
        $url = url('trending-categories');
        $builder = app('datatables.html');
        $builder->ajax($url);
        $builder->parameters([
            'lengthChange' => false,
            // 'pageLength' => 10,
            // 'lengthMenu' => [20],
            'searching' => false,//13-10-2025
        ]);
        
        $dataTable = $builder->columns([
            'image' => ['data' => 'image', 'name' => 'image', 'title' => 'Image'],
            'name' => ['data' => 'name', 'name' => 'name', 'title' => 'Name'],
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
        return view('admin.trending-category.index',compact('dataTable'));
    }
    public function statusUpdate(Request $request)
    {
        $item = TrendingCategory::select('id','status')->where('id',$request->id)->first();
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
        return view('admin.trending-category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:trending_categories,name',
            'image' => 'required|file|mimes:jpeg,jpg,png,webp,gif'
        ]);
        try{
            $category = new TrendingCategory;
            $category->name = $request->name;
            $category->image = $request->image->store('trending-category','public');
            $category->save();
            return redirect()->route('trending-categories.index')->withSuccess('Trending Category Added Successfully');
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
        $category = TrendingCategory::find($id);
        if($category){
            return view('admin.trending-category.edit',compact('category'));
        }else{
            return redirect()->route('trending-categories.index')->withErrors(['error'=>'Trending Category not found']);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|unique:trending_categories,name,'.$id,
            'image' => 'nullable|file|mimes:jpeg,jpg,png,webp,gif'
        ]);
        try{
            $category = TrendingCategory::find($id);
            $category->name = $request->name;
            if($request->hasFile('image')){
                CustomHelper::removeExistingFileFromStorage($category->image);//This is a helper function
                $category->image = $request->image->store('category','public');    
            }
            $category->save();
            return redirect()->route('trending-categories.index')->withSuccess('Trending Category Updated Successfully');
        }catch(\Exception $e){
            return redirect()->back()->withErrors(['error'=>$e->getMessage()])->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = TrendingCategory::find($id);
        // delete from local storage
        CustomHelper::removeExistingFileFromStorage($category->image);//This is a helper function
        $category->delete();

        return response()->json(['status'=>1,'message'=>'Trending Category Deleted']);
    }
}
