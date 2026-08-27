<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Category;
use App\Models\UserDetail;

use App\Models\Setting;
use App\Models\DoctorSettlement;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Validator;
use App\Helpers\CustomHelper;
use App\Models\BookAppointment;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        if ($request->ajax()) {

           $listQry = User::query()
            ->select('users.*', 'categories.name as category_name')
            ->leftJoin('user_details', 'user_details.user_id', '=', 'users.id')
            ->leftJoin('categories', 'categories.id', '=', 'user_details.category_id')
            ->selectSub(function ($q) {
                $q->from('book_appointments')
                    ->selectRaw('COALESCE(SUM(amount),0)')
                    ->whereColumn('doctor_id', 'users.id')
                    ->where('status', 'completed');
            }, 'total_earnings');

            if ($request->type) {
                $listQry->where('users.role', $request->type);
            }

            if ($request->filled('user_id')) {
                $listQry->where('users.id', $request->user_id);
            }
            if ($request->filled('category_id')) {
                $listQry->where('user_details.category_id', $request->category_id);
            }

            // Filter by Created At Date Range
            if ($request->filled('start_date') && $request->filled('end_date')) {
                $listQry->whereBetween('users.created_at', [
                    $request->start_date . ' 00:00:00',
                    $request->end_date . ' 23:59:59'
                ]);
            }

            $lists = $listQry->orderBy('users.id', 'DESC');

            return DataTables::of($lists)

                ->addColumn('image', function ($row) {
                    $user_detail = UserDetail::select('image')->where('user_id', $row->id)->first();

                    if ($user_detail && $user_detail->image_path) {
                        return '<a href="' . $user_detail->image_path . '" target="_blank">
                                <img src="' . $user_detail->image_path . '" width="50" class="img-rounded" />
                                </a>';
                    }

                    return '<img src="' . asset('images/no-image.png') . '" width="50">';
                })

                ->addColumn('total_earnings', function ($row) {
                    return '<span class="badge bg-success">₹ ' . $row->total_earnings . '</span>';
                })
                ->addColumn('category', function ($row) {
                    return $row->category_name ?? '-';
                })
                ->addColumn('status', function ($row) {

                    if ($row->status == 1) {
                        $status = '<button type="button" class="btn btn-sm btn-toggle active listStatusUpdate"
                                    data-bs-toggle="button"
                                    aria-pressed="true"
                                    data-status="0"
                                    data-itemid="' . $row->id . '"
                                    data-url="' . route('user-status-update') . '">
                                    <span class="handle"></span>
                                </button>';
                    } else {
                        $status = '<button type="button" class="btn btn-sm btn-toggle listStatusUpdate"
                                    data-bs-toggle="button"
                                    aria-pressed="false"
                                    data-status="1"
                                    data-itemid="' . $row->id . '"
                                    data-url="' . route('user-status-update') . '">
                                    <span class="handle"></span>
                                </button>';
                    }

                    return $status;
                })

                ->addColumn('created_at', function ($row) {
                    return date('Y-m-d g:i a', strtotime($row->created_at));
                })

                ->addColumn('action', function ($row) {

                    return '<a class="btn btn-sm btn-secondary" href="' . route('users.edit', $row->id) . '">Edit</a>
                            <a href="'.route('doctor.earnings.details',$row->id).'" class="btn btn-sm btn-info">Details</a>';
                })

                ->rawColumns(['action', 'image', 'status', 'total_earnings'])
                ->toJson();
        }

        $doctors = User::where('role', 'doctor')->orderBy('name', 'ASC')->get();

        $url = url('users?type=doctor');

        $builder = app('datatables.html');

        $builder->ajax([
            'url' => $url,
            'data' => 'function(d) {
                d.start_date = $("#start_date_value").val();
                d.end_date = $("#end_date_value").val();
                d.user_id = $("#patient_filter").val();
                d.category_id = $("#category_filter").val();
            }'
        ]);

        $builder->parameters([
            'lengthChange' => false,
            'searching' => true,
            'stateSave' => true,
            'deferLoading' => 0
        ]);

        $dataTable = $builder->columns([
            'image' => [
                'data' => 'image',
                'name' => 'image',
                'title' => 'Image',
                'searchable' => false
            ],

            'name' => [
                'data' => 'name',
                'name' => 'users.name',
                'title' => 'Name'
            ],
            'category' => [
                'data' => 'category',
                'name' => 'categories.name',
                'title' => 'Category',
                'searchable' => false
            ],
            'mobile' => [
                'data' => 'mobile',
                'name' => 'users.mobile',
                'title' => 'Mobile'
            ],

            'total_earnings' => [
                'data' => 'total_earnings',
                'name' => 'total_earnings',
                'title' => 'Total Earnings',
                'searchable' => false
            ],

            'status' => [
                'data' => 'status',
                'name' => 'users.status',
                'title' => 'Status',
                'searchable' => false
            ],

            'created_at' => [
                'data' => 'created_at',
                'name' => 'users.created_at',
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

        $pageName = 'Doctors';
        $pageCreateUrl = route('users.create', ['type' => 'doctor']);
        $categories = Category::select('id', 'name')->whereIn('id', [1, 2])->get();
        return view('admin.user.index', compact('dataTable', 'pageName', 'pageCreateUrl', 'doctors','categories'));
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

    //total earnings tab is running from this controller//

    public function doctorEarnings(Request $request)
    {
        if ($request->ajax()) {
            // Log::info('Filtering with:', [
            //     'start' => $request->start_date,
            //     'end'   => $request->end_date,
            //     'user'  => $request->user_id
            // ]);

            $query = User::where('role','doctor');

            if ($request->filled('user_id')) {
                $query->where('id', $request->user_id);
            }

            return DataTables::of($query)

            ->addColumn('total_earnings', function ($row) use ($request) {

                $appointment = BookAppointment::where('doctor_id',$row->id)
                    ->where('status','completed');

                if ($request->filled('start_date') && $request->filled('end_date')) {
                    $appointment->whereBetween('booking_date', [$request->start_date, $request->end_date]);
                }elseif ($request->filled('start_date') || $request->filled('end_date')) {
                    $date = $request->start_date ?: $request->end_date;
                    $appointment->where('booking_date', $date);
                }

                $total = $appointment->sum('amount');

                return '<span class="badge bg-success">₹ '.$total.'</span>';
            })

            ->addColumn('latest_income', function ($row){

                $latest = BookAppointment::where('doctor_id',$row->id)
                    ->where('status','completed')
                    ->latest('booking_date')
                    ->first();

                if(!$latest){
                    return '<span class="badge bg-secondary">0</span>';
                }

                return '<span class="badge bg-primary">₹ '.$latest->amount.'</span>';
            })

            ->addColumn('action',function($row){
                return '<a href="'.route('doctor.earnings.details',$row->id).'" class="btn btn-sm btn-primary">Details</a>';
            })

            ->rawColumns(['total_earnings','latest_income','action'])
            ->make(true);
        }

        $doctors = User::where('role', 'doctor')->orderBy('name', 'ASC')->get();

        $builder = app('datatables.html');

        $builder->ajax([
            'url'=>route('doctor.earnings'),
            'data'=>"function(d){
                d.start_date = $('#start_date_value').val();
                d.end_date = $('#end_date_value').val();
                d.user_id = $('#patient_filter').val();
            }"
        ]);

        $dataTable = $builder->columns([
            ['data'=>'name','name'=>'name','title'=>'Doctor'],
            ['data'=>'mobile','name'=>'mobile','title'=>'Mobile'],
            ['data'=>'total_earnings','name'=>'total_earnings','title'=>'Total Earnings'],
            ['data'=>'latest_income','name'=>'latest_income','title'=>'Latest Income'],
        ])->addAction([
            'data'=>'action',
            'name'=>'action',
            'title'=>'Action',
            'orderable'=>false,
            'searchable'=>false
        ]);

        return view('admin.total-earnings.index',compact('dataTable', 'doctors'));
    }

    public function totalPlatformEarnings(Request $request)
    {
        $query = BookAppointment::where('status','completed');

        if($request->filter_type == 'monthly'){
            $month = \Carbon\Carbon::parse("1 ".$request->month)->month;

            $query->whereMonth('booking_date',$month)
                ->whereYear('booking_date',$request->year);
        }

        if($request->filter_type == 'yearly'){
            $query->whereYear('booking_date',$request->year);
        }

        return response()->json([
            'total_income'=>$query->sum('amount')
        ]);
    }

    // public function doctorEarningsDetails(Request $request,$doctor_id)
    // {
    //     $doctor = User::with('details')->findOrFail($doctor_id);

    //     if($request->ajax()){

    //         $query = BookAppointment::with(['user.details'])
    //             ->where('doctor_id',$doctor_id)
    //             ->where('status','completed');

    //         if($request->booking_date){
    //             $query->whereDate('booking_date',$request->booking_date);
    //         }

    //         // return DataTables::of($query)

    //         // ->addIndexColumn()

    //         // ->addColumn('patient_name',function($row){
    //         //     return $row->user->name ?? 'N/A';
    //         // })

    //         // ->addColumn('date',function($row){
    //         //     return date('d-m-Y',strtotime($row->booking_date));
    //         // })

    //         // ->addColumn('call_type',function($row){
    //         //     return ucfirst($row->appointment_type);
    //         // })

    //         // ->addColumn('amount',function($row){
    //         //     return '<span class="badge bg-success">₹ '.$row->amount.'</span>';
    //         // })

    //         // ->rawColumns(['amount'])

    //         // ->make(true);
    //         return DataTables::of($query)

    //         ->addIndexColumn()

    //         ->addColumn('patient_name', function ($row) {
    //             return $row->user->name ?? 'N/A';
    //         })
    //         ->filterColumn('patient_name', function ($query, $keyword) {
    //             $query->whereHas('user', function ($q) use ($keyword) {
    //                 $q->where('name', 'like', "%{$keyword}%");
    //             });
    //         })

    //         ->addColumn('date', function ($row) {
    //             return date('d-m-Y', strtotime($row->booking_date));
    //         })
    //         ->filterColumn('date', function ($query, $keyword) {
    //             $query->whereRaw("DATE_FORMAT(booking_date, '%d-%m-%Y') like ?", ["%{$keyword}%"]);
    //         })

    //         ->addColumn('call_type', function ($row) {
    //             return ucfirst($row->appointment_type);
    //         })
    //         ->filterColumn('call_type', function ($query, $keyword) {
    //             $query->where('appointment_type', 'like', "%{$keyword}%");
    //         })

    //         ->addColumn('amount', function ($row) {
    //             return '<span class="badge bg-success">₹ '.$row->amount.'</span>';
    //         })

    //         ->rawColumns(['amount'])

    //         ->make(true);
    //     }

    //     $upcomingAppointments = BookAppointment::with(['user.details'])
    //         ->where('doctor_id',$doctor_id)
    //         ->where('status','upcoming')
    //         ->latest()
    //         ->limit(5)
    //         ->get();

    //     $completedAppointments = BookAppointment::with(['user.details'])
    //         ->where('doctor_id',$doctor_id)
    //         ->where('status','completed')
    //         ->latest()
    //         ->limit(5)
    //         ->get();

    //     $cancelledAppointments = BookAppointment::with(['user.details'])
    //         ->where('doctor_id',$doctor_id)
    //         ->where('status','cancelled')
    //         ->latest()
    //         ->limit(5)
    //         ->get();

    //     $builder = app('datatables.html');

    //     $builder->ajax([
    //         'url'=>route('doctor.earnings.details',$doctor_id),
    //         'data'=>"function(d){
    //             d.booking_date = $('#booking_date_filter').val();
    //         }"
    //     ]);

    //     $dataTable = $builder->columns([
    //         ['data'=>'DT_RowIndex','name'=>'id','title'=>'#','orderable'=>false,'searchable'=>false],
    //         ['data'=>'patient_name','name'=>'patient_name','title'=>'Patient Name'],
    //         ['data'=>'date','name'=>'booking_date','title'=>'Date'],
    //         ['data'=>'call_type','name'=>'appointment_type','title'=>'Call Type'],
    //         ['data'=>'amount','name'=>'amount','title'=>'Amount'],
    //     ]);

    //     return view(
    //         'admin.doctor-profile.index',
    //         compact(
    //             'dataTable',
    //             'doctor',
    //             'upcomingAppointments',
    //             'completedAppointments',
    //             'cancelledAppointments'
    //         )
    //     );
    // }
    public function doctorEarningsDetails(Request $request, $doctor_id)
    {
        $doctor = User::with('details')->findOrFail($doctor_id);

        $selectedMonth = (int) ($request->month ?? now()->month);
        $selectedYear = (int) ($request->year ?? now()->year);

        if ($request->ajax()) {

            $query = BookAppointment::with(['user.details'])
                ->where('doctor_id', $doctor_id)
                ->where('status', 'completed');

            if ($request->month) {
                $query->whereMonth('booking_date', $request->month);
            }
            if ($request->year) {
                $query->whereYear('booking_date', $request->year);
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('patient_name', function ($row) {
                    return $row->user->name ?? 'N/A';
                })
                ->filterColumn('patient_name', function ($query, $keyword) {
                    $query->whereHas('user', function ($q) use ($keyword) {
                        $q->where('name', 'like', "%{$keyword}%");
                    });
                })
                ->addColumn('date', function ($row) {
                    return date('d-m-Y', strtotime($row->booking_date));
                })
                ->filterColumn('date', function ($query, $keyword) {
                    $query->whereRaw("DATE_FORMAT(booking_date, '%d-%m-%Y') like ?", ["%{$keyword}%"]);
                })
                ->addColumn('call_type', function ($row) {
                    return ucfirst($row->appointment_type);
                })
                ->filterColumn('call_type', function ($query, $keyword) {
                    $query->where('appointment_type', 'like', "%{$keyword}%");
                })
                ->addColumn('amount', function ($row) {
                    return '<span class="badge bg-success">₹ ' . $row->amount . '</span>';
                })
                ->rawColumns(['amount'])
                ->make(true);
        }

        $upcomingAppointments = BookAppointment::with(['user.details'])
            ->where('doctor_id', $doctor_id)
            ->where('status', 'upcoming')
            ->latest()->limit(5)->get();

        $completedAppointments = BookAppointment::with(['user.details'])
            ->where('doctor_id', $doctor_id)
            ->where('status', 'completed')
            ->latest()->limit(5)->get();

        $cancelledAppointments = BookAppointment::with(['user.details'])
            ->where('doctor_id', $doctor_id)
            ->where('status', 'cancelled')
            ->latest()->limit(5)->get();

        // ---- Earnings summary (month/year onujayi) ----
        $totalAmount = BookAppointment::where('doctor_id', $doctor_id)
            ->where('status', 'completed')
            ->whereMonth('booking_date', $selectedMonth)
            ->whereYear('booking_date', $selectedYear)
            ->sum('amount');

        $earningPercentage = Setting::where('item_key', 'earning_percentage')->value('item_value') ?? 0;
        $platformShare = round($totalAmount * $earningPercentage / 100, 2);
        $doctorShare = round($totalAmount - $platformShare, 2);

        $isSettled = DoctorSettlement::where('doctor_id', $doctor_id)
            ->where('month', $selectedMonth)
            ->where('year', $selectedYear)
            ->exists();

        $builder = app('datatables.html');

        $builder->ajax([
            'url' => route('doctor.earnings.details', $doctor_id),
            'data' => "function(d){
                d.month = " . json_encode((string) $selectedMonth) . ";
                d.year = " . json_encode((string) $selectedYear) . ";
            }"
        ]);
        $builder->parameters([
             'dom' => 'frtip',
        ]);

        $dataTable = $builder->columns([
            ['data' => 'DT_RowIndex', 'name' => 'id', 'title' => '#', 'orderable' => false, 'searchable' => false],
            ['data' => 'patient_name', 'name' => 'patient_name', 'title' => 'Patient Name'],
            ['data' => 'date', 'name' => 'booking_date', 'title' => 'Date'],
            ['data' => 'call_type', 'name' => 'appointment_type', 'title' => 'Call Type'],
            ['data' => 'amount', 'name' => 'amount', 'title' => 'Amount'],
        ]);

        return view(
            'admin.doctor-profile.index',
            compact(
                'dataTable',
                'doctor',
                'upcomingAppointments',
                'completedAppointments',
                'cancelledAppointments',
                'selectedMonth',
                'selectedYear',
                'totalAmount',
                'earningPercentage',
                'platformShare',
                'doctorShare',
                'isSettled'
            )
        );
    }
    public function settleDoctorEarnings(Request $request, $doctor_id)
    {
        $request->validate([
            'month' => 'required|integer|between:1,12',
            'year' => 'required|digits:4|integer',
        ]);

        $alreadySettled = DoctorSettlement::where('doctor_id', $doctor_id)
            ->where('month', $request->month)
            ->where('year', $request->year)
            ->exists();

        if ($alreadySettled) {
            return redirect()->back()->withErrors('Ei mash already settled.');
        }

        $totalAmount = BookAppointment::where('doctor_id', $doctor_id)
            ->where('status', 'completed')
            ->whereMonth('booking_date', $request->month)
            ->whereYear('booking_date', $request->year)
            ->sum('amount');
        if ($totalAmount <= 0) {
            return redirect()->back()->with(
                'error',
                'No earning available for the selected month and year.'
            );
        }
        $earningPercentage = Setting::where('item_key', 'earning_percentage')->value('item_value') ?? 0;
        $platformShare = round($totalAmount * $earningPercentage / 100, 2);
        $doctorShare = round($totalAmount - $platformShare, 2);

        DB::beginTransaction();
        try {
            $settlement = new DoctorSettlement();
            $settlement->doctor_id = $doctor_id;
            $settlement->month = $request->month;
            $settlement->year = $request->year;
            $settlement->total_amount = $totalAmount;
            $settlement->earning_percentage = $earningPercentage;
            $settlement->platform_share = $platformShare;
            $settlement->doctor_share = $doctorShare;
            $settlement->settled_at = now();
            $settlement->settled_by = Auth::id();
            $settlement->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors('Settlement failed: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Settlement done successfully.');
    }
    public function earningPercentage()
    {
        $setting = Setting::select('id', 'item_key', 'item_value')
            ->where('item_key', 'earning_percentage')
            ->first();

        return view('admin.settings.earning', compact('setting'));
    }

    public function earningPercentageUpdate(Request $request)
    {
        $request->validate([
            'earning_percentage' => 'required|numeric',
        ]);

        Setting::where('item_key', 'earning_percentage')->update([
            'item_value' => $request->earning_percentage,
        ]);

        return redirect()->route('setting-manage')
            ->withSuccess('Earning Percentage Updated Successfully');
    }

}
