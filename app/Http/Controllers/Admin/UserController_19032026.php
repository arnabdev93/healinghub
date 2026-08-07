<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Category;
use App\Models\UserDetail;

use App\Helpers\CustomHelper;
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (request()->ajax()) {
            $listQry = User::query();
            if($request->type){
                $type = $request->type;
                $listQry->where('role',$type);
            }
            $lists = $listQry->orderBy('id', 'DESC');

            return \DataTables::of($lists)->addColumn('image',function($row){
                $user_detail = UserDetail::select('image')->where('user_id',$row->id)->first();
                return '<a href="'.$user_detail->image_path.'" target="_blank"><img src="' . $user_detail->image_path . '" border="0" width="50" class="img-rounded" align="center" /></a>';
            })->addColumn('status',function($row){
                if($row->status==1){
                    $status = '<button type="button" class="btn btn-sm btn-toggle active listStatusUpdate" data-bs-toggle="button" aria-pressed="true" data-status="0" data-itemid="'.$row->id.'" data-url="'.route('user-status-update').'">
                                <span class="handle"></span>
                               </button>';
                }else{
                    $status = '<button type="button" class="btn btn-sm btn-toggle listStatusUpdate" data-bs-toggle="button" aria-pressed="false" data-status="1" data-itemid="'.$row->id.'" data-url="'.route('user-status-update').'">
                                <span class="handle"></span>
                               </button>';
                }                
                return $status;
            })->addColumn('created_at',function($row){
                return date('Y-m-d g:i a',strtotime($row->created_at));
            })->addColumn('action',function($row){
                return '
                        <a class="btn btn-sm btn-secondary" href="'.route('users.edit',$row->id).'">Edit</a>
                        ';
            })->rawColumns(['action','image','status'])->toJson();
        }
        $url = url('users?type=doctor');
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
            'mobile' => ['data' => 'mobile', 'name' => 'mobile', 'title' => 'Mobile'],
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
        $pageName = 'Doctors';
        $pageCreateUrl = route('users.create',['type'=>'doctor']);
        return view('admin.user.index',compact('dataTable','pageName','pageCreateUrl'));
    }
    public function statusUpdate(Request $request)
    {
        $item = User::select('id','status')->where('id',$request->id)->first();
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
    public function create(Request $request)
    {
        $type = $request->type;
        if($type=='doctor'){
            $categories = Category::select('id','name')->whereIn('id',[1,2])->get();
            return view('admin.user.create',compact('type','categories'));
        }else{
            return redirect()->route('users.index',$request->type)->withErrors(['error'=>'Functionality not available']);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'parent_id' => 'required',
            'name' => 'required',
            'mobile' => 'required|digits:10',
            'email' => 'required|email',
            'consult_fee_phone' => 'required',
            'consult_fee_vdo' => 'required',
            'bank_name' => 'nullable|required_with:bank_ifsc_code,bank_acc_no',
            'bank_acc_no' => 'nullable|required_with:bank_name,bank_ifsc_code',
            'bank_ifsc_code' => 'nullable|required_with:bank_name,bank_acc_no',
            'image' => 'required|file|mimes:jpeg,jpg,png,webp,gif'
        ]);
        try{
            $checkExists = User::select('id')->where('mobile',$request->mobile)->first();
            if($checkExists){
                return redirect()->back()->withErrors(['mobile'=>'Already exisis'])->withInput();
            }
            $user = new User;
            $user->name = $request->name;
            $user->role = 'doctor';
            $user->mobile = $request->mobile;
            $user->email = $request->email;
            $user->save();

            $user_id = $user->id;

            $details = new UserDetail;
            $details->user_id = $user_id;
            $details->category_id = $request->parent_id;
            $details->consult_fee_phone = $request->consult_fee_phone;
            $details->consult_fee_vdo = $request->consult_fee_vdo;
            $details->specialist = $request->specialist;
            $details->bank_name = $request->bank_name;
            $details->bank_acc_no = $request->bank_acc_no;
            $details->bank_ifsc_code = $request->bank_ifsc_code;
            $details->upi = $request->upi;
            $details->image = $request->image->store('user','public');
            $details->about = $request->about;
            $details->save();
            return redirect()->route('users.index')->withSuccess('Doctor Created Successfully');
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
        $user = User::find($id);
        if($user){
            $categories = Category::select('id','name')->whereIn('id',[1,2])->get();
            $type = $user->role;
            $details = UserDetail::where('user_id',$id)->first();
            return view('admin.user.edit',compact('categories','user','type','details'));
        }else{
            return redirect()->route('users.index')->withErrors(['error'=>'User not found']);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'parent_id' => 'required',
            'name' => 'required',
            'mobile' => 'required|digits:10',
            'email' => 'required|email',
            'consult_fee_phone' => 'required',
            'consult_fee_vdo' => 'required',
            'bank_name' => 'nullable|required_with:bank_ifsc_code,bank_acc_no',
            'bank_acc_no' => 'nullable|required_with:bank_name,bank_ifsc_code',
            'bank_ifsc_code' => 'nullable|required_with:bank_name,bank_acc_no',
            'image' => 'nullable|file|mimes:jpeg,jpg,png,webp,gif'
        ]);
        try{
            $checkExists = User::select('id')->where('role','doctor')->where('mobile',$request->mobile)->where('id','<>',$id)->first();
            if($checkExists){
                return redirect()->back()->withErrors(['mobile'=>'Already exisis'])->withInput();
            }
            $user = User::find($id);
            $user->name = $request->name;
            $user->mobile = $request->mobile;
            $user->email = $request->email;
            $user->save();

            $details = UserDetail::where('user_id',$id)->first();
            $details->category_id = $request->parent_id;
            $details->consult_fee_phone = $request->consult_fee_phone;
            $details->consult_fee_vdo = $request->consult_fee_vdo;
            $details->specialist = $request->specialist;
            $details->bank_name = $request->bank_name;
            $details->bank_acc_no = $request->bank_acc_no;
            $details->bank_ifsc_code = $request->bank_ifsc_code;
            $details->upi = $request->upi;
            if($request->hasFile('image')){
                CustomHelper::removeExistingFileFromStorage($details->image);//This is a helper function
                $details->image = $request->image->store('user','public');
            }
            $details->about = $request->about;
            $details->save();
            return redirect()->route('users.index')->withSuccess('Doctor Updated Successfully');
        }catch(\Exception $e){
            return redirect()->back()->withErrors(['error'=>$e->getMessage()])->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
