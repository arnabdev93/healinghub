@extends('layouts.app')
@section('page_title')
   
@endsection
@section('content')
   <div class="content-header">
      <div class="d-flex align-items-center">
         <div class="me-auto healbreadcrumb">
            <h4 class="page-title">Edit {{ucfirst($type)}}</h4>
            <div class="d-inline-block align-items-center">
               <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item"><a href="{{route('users.index',['type'=>$type])}}">
                           <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users" style="width: 1em; height: 1em; display: inline;"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                        </a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit</li>
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
               <form role="form" action="{{ route('users.update',$user->id) }}" method="post" enctype="multipart/form-data">
                  @csrf
                  @method('PUT')
                  <input type="hidden" name="type" value="{{$type}}">
                 <div class="row">
                     <div class="col-md-4"> 
                        <div class="form-group  @error('parent_id') error @endif">
                           <label class="form-label">Category <span class="text-danger">*</span></label>
                           <div class="controls">
                              <select name="parent_id" class="form-control select2" id="parent_id" @error('parent_id') aria-invalid="true" @endif>
                                 @foreach($categories as $category)
                                    <option value="{{$category->id}}" {{old('parent_id',$details->category_id) == $category->id ? 'selected' : ''}}>{{$category->name}}</option>
                                 @endforeach
                              </select>
                              @error('parent_id') <div class="help-block"><ul role="alert"><li>{{$message}}</li></ul></div> @endif
                           </div>
                        </div>
                     </div>
                     <div class="col-md-4"> 
                        <div class="form-group  @error('name') error @endif">
                           <label class="form-label">Name <span class="text-danger">*</span></label>
                           <div class="controls">
                              <input type="text" name="name" class="form-control" value="{{old('name',$user->name)}}" @error('name') aria-invalid="true" @endif> 
                              @error('name') <div class="help-block"><ul role="alert"><li>{{$message}}</li></ul></div> @endif
                           </div>
                        </div>
                     </div>
                     <div class="col-md-4"> 
                        <div class="form-group  @error('mobile') error @endif">
                           <label class="form-label">Mobile <span class="text-danger">*</span></label>
                           <div class="controls">
                              <input type="text" name="mobile" class="form-control" value="{{old('mobile',$user->mobile)}}" @error('mobile') aria-invalid="true" @endif> 
                              @error('mobile') <div class="help-block"><ul role="alert"><li>{{$message}}</li></ul></div> @endif
                           </div>
                        </div>
                     </div>
                     <div class="col-md-4"> 
                        <div class="form-group  @error('email') error @endif">
                           <label class="form-label">Email <span class="text-danger">*</span></label>
                           <div class="controls">
                              <input type="text" name="email" class="form-control" value="{{old('email',$user->email)}}" @error('email') aria-invalid="true" @endif> 
                              @error('email') <div class="help-block"><ul role="alert"><li>{{$message}}</li></ul></div> @endif
                           </div>
                        </div>
                     </div>
                     
                     <div class="col-md-4"> 
                        <div class="form-group  @error('consult_fee_phone') error @endif">
                           <label class="form-label">Consultation Fee for Phone Call <span class="text-danger">*</span></label>
                           <div class="controls">
                              <input type="text" name="consult_fee_phone" class="form-control" value="{{old('consult_fee_phone',$details->consult_fee_phone)}}" @error('consult_fee_phone') aria-invalid="true" @endif oninput="this.value = this.value.replace(/[^0-9.]/g, '')" onpaste="this.value = this.value.replace(/[^0-9.]/g, ''); return false;"> 
                              @error('consult_fee_phone') <div class="help-block"><ul role="alert"><li>{{$message}}</li></ul></div> @endif
                           </div>
                        </div>
                     </div>
                     <div class="col-md-4"> 
                        <div class="form-group  @error('consult_fee_vdo') error @endif">
                           <label class="form-label">Consultation Fee for VDO Call <span class="text-danger">*</span></label>
                           <div class="controls">
                              <input type="text" name="consult_fee_vdo" class="form-control" value="{{old('consult_fee_vdo',$details->consult_fee_vdo)}}" @error('consult_fee_vdo') aria-invalid="true" @endif oninput="this.value = this.value.replace(/[^0-9.]/g, '')" onpaste="this.value = this.value.replace(/[^0-9.]/g, ''); return false;"> 
                              @error('consult_fee_vdo') <div class="help-block"><ul role="alert"><li>{{$message}}</li></ul></div> @endif
                           </div>
                        </div>
                     </div>
                     <div class="col-md-4"> 
                        <div class="form-group  @error('specialist') error @endif">
                           <label class="form-label">Specialist/Degree</label>
                           <div class="controls">
                              <input type="text" name="specialist" class="form-control" value="{{old('specialist',$details->specialist)}}" @error('specialist') aria-invalid="true" @endif> 
                              @error('specialist') <div class="help-block"><ul role="alert"><li>{{$message}}</li></ul></div> @endif
                           </div>
                        </div>
                     </div>
                     
                     <div class="col-md-4"> 
                        <div class="form-group  @error('bank_name') error @endif">
                           <label class="form-label">Bank Name</label>
                           <div class="controls">
                              <input type="text" name="bank_name" class="form-control" value="{{old('bank_name',$details->bank_name)}}" @error('bank_name') aria-invalid="true" @endif> 
                              @error('bank_name') <div class="help-block"><ul role="alert"><li>{{$message}}</li></ul></div> @endif
                           </div>
                        </div>
                     </div>
                     <div class="col-md-4"> 
                        <div class="form-group  @error('bank_acc_no') error @endif">
                           <label class="form-label">Bank Account Number</label>
                           <div class="controls">
                              <input type="text" name="bank_acc_no" class="form-control" value="{{old('bank_acc_no',$details->bank_acc_no)}}" @error('bank_acc_no') aria-invalid="true" @endif> 
                              @error('bank_acc_no') <div class="help-block"><ul role="alert"><li>{{$message}}</li></ul></div> @endif
                           </div>
                        </div>
                     </div>
                     <div class="col-md-4"> 
                        <div class="form-group  @error('bank_ifsc_code') error @endif">
                           <label class="form-label">Bank IFSC Code</label>
                           <div class="controls">
                              <input type="text" name="bank_ifsc_code" class="form-control" value="{{old('bank_ifsc_code',$details->bank_ifsc_code)}}" @error('bank_ifsc_code') aria-invalid="true" @endif> 
                              @error('bank_ifsc_code') <div class="help-block"><ul role="alert"><li>{{$message}}</li></ul></div> @endif
                           </div>
                        </div>
                     </div>
                     <div class="col-md-4"> 
                        <div class="form-group  @error('upi') error @endif">
                           <label class="form-label">UPI</label>
                           <div class="controls">
                              <input type="text" name="upi" class="form-control" value="{{old('upi',$details->upi)}}" @error('upi') aria-invalid="true" @endif> 
                              @error('upi') <div class="help-block"><ul role="alert"><li>{{$message}}</li></ul></div> @endif
                           </div>
                        </div>
                     </div>
                     <div class="col-md-4">                
                        <div class="form-group @error('image') error @endif">
                           <label class="form-label">Image <span class="text-danger">*</span></label>
                           <div class="controls">
                              <input type="file" class="form-control browse-btn" name="image" accept="image/*,text/plain" @error('image') aria-invalid="true" @endif> 
                              @error('image') <div class="help-block"><ul role="alert"><li>{{$message}}</li></ul></div> @endif
                              <img src="{{$details->image_path}}" height="50" width="50" class="preview">
                           </div>
                        </div>
                     </div>
                     <div class="col-md-12"> 
                        <div class="form-group  @error('about') error @endif">
                           <label class="form-label">About</label>
                           <div class="controls">
                              <textarea name="about" class="form-control" @error('about') aria-invalid="true" @endif>{{old('about',$details->about)}}</textarea>
                              @error('about') <div class="help-block"><ul role="alert"><li>{{$message}}</li></ul></div> @endif
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