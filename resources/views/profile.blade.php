@extends('layouts.app')
@section('page_title')
@endsection
@section('content')
<div class="content-header">
   <div class="d-flex align-items-center">
      <div class="me-auto healbreadcrumb">
         <h4 class="page-title">Profile Update</h4>
         <div class="d-inline-block align-items-center">
            <nav>
               <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="mdi mdi-home-outline"></i></a></li>
                  <li class="breadcrumb-item active" aria-current="page">Update</li>
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
               <form role="form" action="{{ route('profile-update') }}" method="post" enctype="multipart/form-data">
                  @csrf
                  <div class="row">
                     <div class="col-md-6">
                        <div class="form-group @error('name') error @endif">
                           <label class="form-label">Name<span class="text-danger">*</span></label>
                           <div class="controls">
                              <input type="text" name="name" class="form-control @error('name') is-invalid @endif" value="{{ old('name', $user->name) }}">
                              @error('name')
                              <div class="help-block"><ul role="alert"><li>{{ $message }}</li></ul></div>
                              @enderror
                           </div>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group @error('email') error @endif">
                           <label class="form-label">Email</label>
                           <div class="controls">
                              <input type="text" name="email" class="form-control @error('email') is-invalid @endif" value="{{ old('email', $user->email) }}" readonly>
                              @error('email')
                              <div class="help-block"><ul role="alert"><li>{{ $message }}</li></ul></div>
                              @enderror
                           </div>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group @error('password') error @endif">
                           <label class="form-label">Password<span class="text-danger">*</span></label>
                           <div class="controls">
                              <input type="password" name="password" class="form-control @error('password') is-invalid @endif" placeholder="Leave blank if you don't want to update">
                              @error('password')
                              <div class="help-block"><ul role="alert"><li>{{ $message }}</li></ul></div>
                              @enderror
                           </div>
                        </div>
                     </div>
                     {{-- <div class="col-md-6">
                           <div class="form-group @error('image') error @endif">
                              <label class="form-label">Image <span class="text-danger">*</span></label>
                              <div class="controls">
                                 <input type="file" class="form-control browse-btn" name="image" accept="image/*,text/plain" @error('image') aria-invalid="true" @endif>
                                 @error('image') <div class="help-block"><ul role="alert"><li>{{$message}}</li></ul></div> @endif
                                 @if($user->profile_image)
                                 <img src="{{asset('storage/' . $user->profile_image)}}" height="50" width="50" class="preview">
                                 @endif
                              </div>
                           </div>
                        </div> --}}
                  </div>
                  <div class="text-xs-right">
                     <button type="submit" class="btn btn-sm btn-info">Update</button>
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
