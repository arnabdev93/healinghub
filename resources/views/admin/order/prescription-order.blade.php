@extends('layouts.app')
@section('page_title')

@endsection
@section('content')
   <div class="content-header">
      <div class="d-flex align-items-center">
         <div class="me-auto healbreadcrumb">
            <h4 class="page-title">Prescription Orders</h4>
            <div class="d-inline-block align-items-center">
               <nav>
                  <ol class="breadcrumb">
                     <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="mdi mdi-home-outline"></i></a></li>
                     <li class="breadcrumb-item active" aria-current="page">Prescription Orders</li>
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
                              <!-- <a href="{{route('categories.create')}}" class="btn btn-sm btn-primary-light mb-2">Create</a> -->
                           </div>
                        </div><!-- end col-->
                     </div>
                     <div class="table-responsive rounded card-table">
                        <div class="row mb-3">
                           <div class="col-md-2">
                              <label><strong>Choose Date Range</strong></label>
                              <input type="text" id="datefilter" class="form-control" name="datefilter" value="" placeholder="Select Dates">
                              <input type="hidden" id="start_date_value" name="start_date_value">
                              <input type="hidden" id="end_date_value" name="end_date_value">
                           </div>
                           <div class="col-md-2">
                              <label><strong>Customer Name</strong></label>
                              <select id="patient_filter" class="form-control select2-filter">
                                    <option value="">-- All Customer --</option>
                                    @foreach($patients as $patient)
                                       <option value="{{ $patient->id }}">{{ $patient->name }} [{{ $patient->mobile }}]</option>
                                    @endforeach
                              </select>
                           </div>
                            <div class="col-md-4">
                                <label class="form-label"><strong>Select Status</strong></label>
                                <select name="status" id="status"
                                        class="form-control select2-filter">
                                    <option value="">Select Status</option>
                                     <option value="pending">pending</option>
                                    <option value="accept">Accept</option>
                                    <option value="reject">Reject</option>
                                    <option value="cancel">Cancel</option>
                                </select>
                            </div>
                           <div class="col-md-2 d-flex align-items-end">
                              <button class="btn btn-primary me-2" id="filter_btn_appointment">Search</button>
                              <button class="btn btn-secondary" id="clear_filter_appointment">Clear</button>
                           </div>
                        <div class="col-md-3 mt-2">
                            <label><strong>Delivered Orders Total</strong></label>
                                <div>
                                    <span class="badge badge-lg badge-success-light fs-6 px-3 py-2" id="delivered_total_label">

                                    </span>
                                </div>
                            </div>
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
{{ $dataTable->scripts() }}
<script type="text/javascript">
$(document).ajaxComplete(function (event, xhr, settings) {
    try {
        var json = JSON.parse(xhr.responseText);
        if (json.delivered_total !== undefined) {
            $('#delivered_total_label').text('₹' + json.delivered_total);
        }
    } catch (e) {
        console.log(e);
    }

});
</script>
@endpush
