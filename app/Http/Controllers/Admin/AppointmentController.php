<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BookAppointment;
use App\Models\Payment;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\User;
use App\Models\Address;
use Illuminate\Support\Facades\Auth;
use App\Models\AppointmentStatusLog;
use Illuminate\Support\Facades\Validator;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $appointments = BookAppointment::query()
            ->leftJoin('users as patient','book_appointments.user_id','=','patient.id')
            ->leftJoin('users as doctor','book_appointments.doctor_id','=','doctor.id')
            ->select(
                'book_appointments.*',
                'patient.name as patient_name',
                'doctor.name as doctor_name'
            );

            if($request->start_date && $request->end_date){

                $appointments->whereBetween('book_appointments.booking_date', [
                    $request->start_date,
                    $request->end_date
                ]);

            }elseif ($request->filled('start_date') || $request->filled('end_date')) {
                $date = $request->start_date ?: $request->end_date;
                $appointments->where('book_appointments.booking_date', $date);
            }

            if ($request->patient_id) {
                $appointments->where('book_appointments.user_id', $request->patient_id);
            }

            if ($request->doctor_id) {
                $appointments->where('book_appointments.doctor_id', $request->doctor_id);
            }

            if($request->appointment_type){

                $appointments->where('book_appointments.appointment_type', $request->appointment_type);

            }

            if($request->appointment_status){

                $appointments->where('book_appointments.status', $request->appointment_status);

            }
            $appointments->orderBy('created_at','DESC');
            return DataTables::of($appointments)

            ->addColumn('patient', function($row){
                return $row->patient_name ?? '-';
            })

            ->addColumn('doctor', function($row){
                return $row->doctor_name ?? '-';
            })

            ->addColumn('booking_date', function($row){
                return date('d M Y', strtotime($row->booking_date));
            })

            ->addColumn('booking_time', function($row){
                return date('h:i A', strtotime($row->booking_time));
            })

            ->addColumn('appointment_type', function($row){
                return ucfirst($row->appointment_type);
            })

            ->addColumn('status', function($row){

                if($row->status=='upcoming'){
                    return '<span class="badge bg-warning text-dark">Upcoming</span>';
                }elseif($row->status=='completed'){
                    return '<span class="badge bg-success">Completed</span>';
                }elseif($row->status=='cancelled'){
                    return '<span class="badge bg-danger">Cancelled</span>';
                }else{
                    return '<span class="badge bg-secondary">'.$row->status.'</span>';
                }

            })

            ->addColumn('action', function($row){

                return '<a class="btn btn-sm btn-info" href="'.route('appointments.show',$row->id).'">Details</a>';

            })

            ->rawColumns(['status','action'])
            ->toJson();
        }

        $url = route('appointments.index');

        $builder = app('datatables.html');

        $builder->ajax([
            'url'=>$url,
            'data'=>"function(d){
                d.start_date = $('#start_date_value').val();
                d.end_date = $('#end_date_value').val();
                d.patient_id = $('#patient_filter').val();
                d.doctor_id = $('#doctor_id_filter').val();
                d.appointment_type = $('#appointment_type_filter').val();
                d.appointment_status = $('#appointment_status_filter').val();
            }"
        ]);

        // d.start_time = $('#start_time_filter').val();
        // d.appointment_type = $('#appointment_type_filter').val();
        // d.appointment_status = $('#appointment_status_filter').val();

        $builder->parameters([
            'lengthChange' => true,
            'searching' => true,
            'processing' => true,
            'serverSide' => true,
        ]);

        $dataTable = $builder->columns([

            'patient' => [
                'data'=>'patient',
                'name'=>'patient.name',
                'title'=>'Patient'
            ],

            'doctor' => [
                'data'=>'doctor',
                'name'=>'doctor.name',
                'title'=>'Doctor'
            ],

            'booking_date'=>[
                'data'=>'booking_date',
                'name'=>'book_appointments.booking_date',
                'title'=>'Date'
            ],

            'booking_time'=>[
                'data'=>'booking_time',
                'name'=>'book_appointments.booking_time',
                'title'=>'Time'
            ],

            'appointment_type'=>[
                'data'=>'appointment_type',
                'name'=>'book_appointments.appointment_type',
                'title'=>'Type'
            ],

            'amount'=>[
                'data'=>'amount',
                'name'=>'book_appointments.amount',
                'title'=>'Fee'
            ],

            'status'=>[
                'data'=>'status',
                'name'=>'book_appointments.status',
                'title'=>'Status'
            ],

        ])->addAction([
            'defaultContent'=>'',
            'data'=>'action',
            'name'=>'action',
            'title'=>'Action',
            'orderable'=>false,
            'searchable'=>false,
        ]);

        $statuses = BookAppointment::query()
                    ->select('status')
                    ->distinct()
                    ->pluck('status');

        $patients = User::where('role', 'customer')->select('id', 'name')->get();
        $doctors = User::where('role', 'doctor')->select('id', 'name')->get();

        return view('admin.appointments.index',compact('dataTable','statuses','patients', 'doctors'));
    }

    public function show($id)
    {
        $appointment = BookAppointment::with([
            'user.details',
            'doctor.details'
        ])->findOrFail($id);
        // dd($appointment->doctor->details);

        $patient = $appointment->user;
        $doctor  = $appointment->doctor;
        $address = Address::select('address','pincode','city','state','country','building')
        ->where('user_id', $patient->id)
        ->first();
        // dd($address);
        $logs = \App\Models\AppointmentStatusLog::with('user')
            ->where('appointment_id',$appointment->id)
            ->latest('changed_at')
            ->get();

        $prescriptions = [];

        if($appointment->status == 'completed'){
            $prescriptions = \App\Models\Prescription::where('appointment_id',$appointment->id)->get();
        }

        $payments = Payment::with('method')
            ->where('appointment_id',$appointment->id)
            ->latest()
            ->get();

        return view(
            'admin.appointments.details',
            compact(
                'address',
                'appointment',
                'patient',
                'doctor',
                'logs',
                'prescriptions',
                'payments'
            )
        );
    }
    public function updateStatus(Request $request, $id)
    {
        $rules = [
            'status' => 'required|in:completed,cancelled',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $appointment = BookAppointment::findOrFail($id);

        $appointment->update([
            'status' => $request->status,
        ]);

        AppointmentStatusLog::create([
            'appointment_id' => $appointment->id,
            'changed_by'     => Auth::id(),
            'note'           => 'Status updated by admin',
            'new_status'     => $appointment->status,
            'changed_at'     => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Appointment status updated successfully.',
            'log' => [
                'user_name'       => Auth::user()->name,
                'new_status'      => $appointment->status,
                'changed_at'      => now()->format('d M, h:i A'),
                'appointment_no'  => $appointment->appointment_no,
            ],
        ]);
    }
}
