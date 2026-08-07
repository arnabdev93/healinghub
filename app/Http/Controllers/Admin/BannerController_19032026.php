<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Banner;
use App\Helpers\CustomHelper;
class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (request()->ajax()) {
            $bannerQry = Banner::query();
            $banners = $bannerQry->orderBy('id', 'DESC');
            
            return \DataTables::of($banners)->addColumn('image',function($row){
                return '<a href="'.$row->image_path.'" target="_blank"><img src="' . $row->image_path . '" border="0" width="50" class="img-rounded" align="center" /></a>';
            })->addColumn('status',function($row){
                if($row->status==1){
                    $status = '<button type="button" class="btn btn-sm btn-toggle active listStatusUpdate" data-bs-toggle="button" aria-pressed="true" data-status="0" data-itemid="'.$row->id.'" data-url="'.route('banner-status-update').'">
                                <span class="handle"></span>
                               </button>';
                }else{
                    $status = '<button type="button" class="btn btn-sm btn-toggle listStatusUpdate" data-bs-toggle="button" aria-pressed="false" data-status="1" data-itemid="'.$row->id.'" data-url="'.route('banner-status-update').'">
                                <span class="handle"></span>
                               </button>';
                }                
                return $status;
            })->addColumn('created_at',function($row){
                return date('Y-m-d g:i a',strtotime($row->created_at));
            })->addColumn('action',function($row){
                return '
                        <a class="btn btn-sm btn-secondary" href="'.route('banner.edit',$row->id).'">Edit</a>
                        <a class="btn btn-sm btn-danger delete_item_from_list" data-url="'.route('banner.destroy',$row->id).'" data-id="'.$row->id.'">Delete</a>';
            })->rawColumns(['action','image','status'])->toJson();
        }
        $url = url('banner');
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
        return view('admin.banner.index',compact('dataTable'));
    }
    public function statusUpdate(Request $request)
    {
        $item = Banner::select('id','status')->where('id',$request->id)->first();
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
        return view('admin.banner.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|file|mimes:jpeg,jpg,png,webp,gif'
        ]);
        try{
            $banner = new Banner;
            $banner->image = $request->image->store('banner','public');
            $banner->save();
            // Banner::create($data);
            return redirect()->route('banner.index')->withSuccess('Banner Added Successfully');
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
        $banner = Banner::find($id);
        if($banner){
            return view('admin.banner.edit',compact('banner'));
        }else{
            return redirect()->route('banner.index')->withErrors(['error'=>'Banner not found']);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'image' => 'required|file|mimes:jpeg,jpg,png,webp,gif'
        ]);

        try{
            $banner = Banner::find($id);
            CustomHelper::removeExistingFileFromStorage($banner->image);//This is a helper function
            $banner->image = $request->image->store('banner', 'public');
            
            $banner->save();
            return redirect()->route('banner.index')->withSuccess('Banner Updated Successfully');
        }catch(\Exception $e){
            return redirect()->back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $banner = Banner::find($id);
        // delete from local storage
        CustomHelper::removeExistingFileFromStorage($banner->image);//This is a helper function
        $banner->delete();

        return response()->json(['status'=>1,'message'=>'Banner Deleted']);
    }
}
