@extends('layouts.app')
@section('page_title')
   
@endsection
@section('content')
   <div class="content-header">
      <div class="d-flex align-items-center">
         <div class="me-auto healbreadcrumb">
            <h4 class="page-title">Appoinments logs</h4>
            <div class="d-inline-block align-items-center">
               <nav>
                  <ol class="breadcrumb">
                     <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="mdi mdi-home-outline"></i></a></li>
                     <li class="breadcrumb-item active" aria-current="page">Appoinments logs</li>
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
                            <div class="col-md-3">
                                <label class="form-label"><strong>Filter by Date</strong></label>
                                <input type="date" id="log_date_filter" class="form-control">
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button class="btn btn-primary me-2" id="log_filter_btn">
                                Search
                                </button>
                                <button class="btn btn-secondary" id="clear_log_filter">
                                Clear
                                </button>
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
@endpush
