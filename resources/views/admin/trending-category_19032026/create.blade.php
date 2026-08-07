@extends('layouts.app')
@section('page_title')
   
@endsection
@section('content')
   <div class="content-header">
      <div class="d-flex align-items-center">
         <div class="me-auto healbreadcrumb">
            <h4 class="page-title">Create Trending Category</h4>
            <div class="d-inline-block align-items-center">
               <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item"><a href="{{route('trending-categories.index')}}">
                           <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 1em; height: 1em; display: inline;">
                             <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
                             <path d="M12 9l.94 2.88h3.03l-2.45 1.78.94 2.88L12 15.54l-2.45 1.78.94-2.88-2.45-1.78h3.03z"></path>
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
               <form role="form" action="{{ route('trending-categories.store') }}" method="post" enctype="multipart/form-data">
                  @csrf
                 <div class="row">
                     <div class="col-md-6"> 
                        <div class="form-group  @error('name') error @endif">
                           <label class="form-label">Category Name <span class="text-danger">*</span></label>
                           <div class="controls">
                              <input type="text" name="name" class="form-control" value="{{old('name')}}" @error('name') aria-invalid="true" @endif autofocus> 
                              @error('name') <div class="help-block"><ul role="alert"><li>{{$message}}</li></ul></div> @endif
                           </div>
                        </div>
                     </div>
                     <div class="col-md-6">                
                        <div class="form-group @error('image') error @endif">
                           <label class="form-label">Image <span class="text-danger">*</span></label>
                           <div class="controls">
                              <input type="file" class="form-control browse-btn" name="image" accept="image/*,text/plain" @error('image') aria-invalid="true" @endif> 
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

@endpush