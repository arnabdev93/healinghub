@extends('layouts.app')
@section('page_title')
   
@endsection
@section('content')
   <div class="content-header">
      <div class="d-flex align-items-center">
         <div class="me-auto healbreadcrumb">
            <h4 class="page-title">Create Banner</h4>
            <div class="d-inline-block align-items-center">
               <nav>
                  <ol class="breadcrumb">
                     <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="mdi mdi-home-outline"></i></a></li>
                     <li class="breadcrumb-item"><a href="{{route('banner.index')}}">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-banner" style="width: 1em; height: 1em; display: inline;">
                           <path d="M4 15s1-1 5-1 5.998 1 8 1c1.657 0 2.357-.649 2.75-1.025V4c-.504.501-1.363 1.003-2.75 1.003C13.998 5 10 4 5 4 1 4 0 8 0 11.667c0 4 1 6.585 4 7.585"></path>
                       </svg>
                     </a></li>
                     <li class="breadcrumb-item active" aria-current="page">Create</li>
                  </ol>
               </nav>
            </div>
         </div>
         
      </div>
   </div>
   <section class="content">
       <!-- Basic Forms -->
        <div class="box">
         <!-- /.box-header -->
         <div class="box-body">
           <div class="row">
            <div class="col">
               <form role="form" action="{{ route('banner.store') }}" method="post" enctype="multipart/form-data">
                  @csrf
                 <div class="row">
                     <div class="col-xl-6 col-12">                
                        <div class="form-group @error('image') error @endif">
                           <label class="form-label">Image <span class="text-danger">*</span></label>
                           <div class="controls">
                              <input type="file" class="form-control browse-btn @error('image') is-invalid @endif" name="image" accept="image/*,text/plain" @error('image') aria-invalid="true" @endif> 
                              @error('image') <div class="help-block"><ul role="alert"><li>{{$message}}</li></ul></div> @endif
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="text-xs-right">
                     <button type="submit" class="btn btn-sm btn-info">Submit</button>
                  </div>
               </form>

            </div>
            <!-- /.col -->
           </div>
           <!-- /.row -->
         </div>
         <!-- /.box-body -->
        </div>
        <!-- /.box -->

      </section>
@endsection
@push('page_scripts')
   <!-- <script src="{{ asset('assets/vendor_components/dropzone/dropzone.js') }}"></script> -->
@endpush