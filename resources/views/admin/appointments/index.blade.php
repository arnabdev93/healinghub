@extends('layouts.app')
@section('page_title')

@endsection
@section('content')
   <div class="content-header">
      <div class="d-flex align-items-center">
         <div class="me-auto healbreadcrumb">
            <h4 class="page-title">Booking appoinments</h4>
            <div class="d-inline-block align-items-center">
               <nav>
                  <ol class="breadcrumb">
                     <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="mdi mdi-home-outline"></i></a></li>
                     <li class="breadcrumb-item active" aria-current="page">Booking appoinments</li>
                  </ol>
               </nav>
            </div>
         </div>

      </div>
   </div>
   <section class="content">
     <div class="row">
         <div class="col-12">
             <div class="card">
                 <div class="card-body">
                     <div class="row mb-2">
                        <div class="col-xl-12">
                           <div class="text-xl-end mt-xl-0 mt-2">
                              {{-- <a href="{{route('banner.create')}}" class="btn btn-sm btn-primary-light mb-2">Create</a> --}}
                           </div>
                        </div><!-- end col-->
                     </div>

                     <div class="table-responsive rounded card-table">
                        <div class="row mb-3">
                           <div class="col-md-2">
                              <label><strong>Choose date range</strong></label>
                              <input type="text" id="datefilter" class="form-control" name="datefilter" value="" placeholder="Select Dates">
                              <input type="hidden" id="start_date_value" name="start_date_value">
                              <input type="hidden" id="end_date_value" name="end_date_value">
                           </div>
                           <div class="col-md-2">
                              <label><strong>Patient</strong></label>
                              <select id="patient_filter" class="form-control select2-filter">
                                    <option value="">-- All Patients --</option>
                                    @foreach($patients as $patient)
                                       <option value="{{ $patient->id }}">{{ $patient->name }}</option>
                                    @endforeach
                              </select>
                           </div>
                           <div class="col-md-2">
                              <label><strong>Doctor</strong></label>
                              <select id="doctor_id_filter" class="form-control select2-filter">
                                    <option value="">-- All Doctors --</option>
                                    @foreach($doctors as $doctor)
                                       <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                                    @endforeach
                              </select>
                           </div>
                           <div class="col-md-2">
                              <label><strong>Appointment type</strong></label>
                              <select id="appointment_type_filter" class="form-control">
                                 <option value="">-- Choose type --</option>
                                 <option value="audio">Audio</option>
                                 <option value="video">Video</option>
                              </select>
                           </div>
                           <div class="col-md-2">
                              <label><strong>Appointment Status</strong></label>
                              <select id="appointment_status_filter" class="form-control">
                                 <option value="">-- Choose status --</option>
                                 @if (isset($statuses) && !empty($statuses))
                                    @foreach ($statuses as $status)
                                       <option value="{{$status}}">{{ucfirst($status)}}</option>
                                    @endforeach
                                 @endif
                              </select>
                           </div>
                           <div class="col-md-2 d-flex align-items-end">
                              {{-- <button class="btn btn-primary me-2" id="appointment_search_btn_new">Search</button>
                              <button class="btn btn-secondary" id="appointment_clear_btn_new">Clear</button> --}}
                              <button class="btn btn-primary me-2" id="filter_btn_appointment">Search</button>
                              <button class="btn btn-secondary" id="clear_filter_appointment">Clear</button>
                           </div>
                        </div>
                         <table class="table border-no" id="listTableDataTableId">
                             {{ $dataTable->table() }}
                         </table>
                     </div>
                 </div>
             </div>
         </div>
      </div>
   </section>
@endsection
@push('page_scripts')
{!! $dataTable->scripts() !!}
{{-- <script>
$(document).ready(function () {
    $('#appointment_search_btn_new').on('click', function (e) {
        e.preventDefault();
        window.LaravelDataTables["dataTableBuilder"].ajax.reload();

    });

    $('#appointment_clear_btn_new').on('click', function (e) {
        e.preventDefault();
        // Clear date
        $('#datefilter').val('');
        $('#start_date_value').val('');
        $('#end_date_value').val('');

        // Clear patient
        $('#patient_filter')
            .val('')
            .trigger('change');
        // Clear doctor
        $('#doctor_id_filter')
            .val('')
            .trigger('change');
        // Clear appointment type
        $('#appointment_type_filter')
            .val('')
            .trigger('change');
        // Clear appointment status
        $('#appointment_status_filter')
            .val('')
            .trigger('change');
        // Reload table
        window.LaravelDataTables["dataTableBuilder"].ajax.reload();

    });
});
</script> --}}
@endpush

