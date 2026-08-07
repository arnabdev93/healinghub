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
@endpush