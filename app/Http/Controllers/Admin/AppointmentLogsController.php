<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppointmentStatusLog;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AppointmentLogsController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $logs = AppointmentStatusLog::query()
            ->leftJoin('book_appointments','appointment_status_logs.appointment_id','=','book_appointments.id')
            ->leftJoin('users as changer','appointment_status_logs.changed_by','=','changer.id')
            ->select(
                'appointment_status_logs.*',
                'book_appointments.appointment_no',
                'changer.name as changed_by_name'
            );
            if($request->log_date){
                $logs->whereDate('appointment_status_logs.changed_at',$request->log_date);
            }

            return DataTables::of($logs)

            ->addColumn('appointment_no', function($row){
                return $row->appointment_no ?? '-';
            })

            ->addColumn('changed_by', function($row){
                return $row->changed_by_name ?? '-';
            })

            ->addColumn('note', function($row){
                return ucfirst($row->note);
            })

            ->addColumn('status_change', function($row){

                if($row->old_status){
                    return '<span class="badge bg-secondary">'.$row->old_status.'</span>
                            <i class="mdi mdi-arrow-right"></i>
                            <span class="badge bg-success">'.$row->new_status.'</span>';
                }

                return '<span class="badge bg-success">'.$row->new_status.'</span>';
            })

            ->addColumn('changed_at', function($row){
                return date('d M Y h:i A',strtotime($row->changed_at));
            })

            ->rawColumns(['status_change'])

            ->toJson();
        }

        $url = route('appointments.logs.index');

        $builder = app('datatables.html');

        $builder->ajax([
            'url'=>$url,
            'data'=>"function(d){
                d.log_date = $('#log_date_filter').val();
            }"
        ]);

        $builder->parameters([
            'lengthChange' => true,
            'searching' => true,
            'processing' => true,
            'serverSide' => true,
        ]);

        $dataTable = $builder->columns([

            'appointment_no'=>[
                'data'=>'appointment_no',
                'name'=>'book_appointments.appointment_no',
                'title'=>'Appointment No'
            ],

            'changed_by'=>[
                'data'=>'changed_by',
                'name'=>'changer.name',
                'title'=>'Changed By'
            ],

            'note'=>[
                'data'=>'note',
                'name'=>'appointment_status_logs.note',
                'title'=>'Note'
            ],

            'status_change'=>[
                'data'=>'status_change',
                'name'=>'status_change',
                'title'=>'Status Change',
                'orderable'=>false,
                'searchable'=>false
            ],

            'changed_at'=>[
                'data'=>'changed_at',
                'name'=>'appointment_status_logs.changed_at',
                'title'=>'Changed At'
            ],

        ]);

        return view('admin.appointments-logs.index',compact('dataTable'));
    }
}
