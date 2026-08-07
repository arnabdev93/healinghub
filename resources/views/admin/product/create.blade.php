@extends('layouts.app')
@section('page_title')
   
@endsection
@section('content')
   <div class="content-header">
      <div class="d-flex align-items-center">
         <div class="me-auto healbreadcrumb">
            <h4 class="page-title">Create Product</h4>
            <div class="d-inline-block align-items-center">
               <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item"><a href="{{route('products.index')}}">
                           <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 1em; height: 1em; display: inline;">
                             <path d="M12 2L2 7v10l10 5 10-5V7l-10-5z"></path>
                             <path d="M2 7l10 5 10-5"></path>
                             <path d="M12 12v10"></path>
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
               <form role="form" action="{{ route('products.store') }}" method="post" enctype="multipart/form-data">
                  @csrf
                 <div class="row">
                     <div class="col-md-6"> 
                        <div class="form-group  @error('parent_id') error @endif">
                           <label class="form-label">Parent Category <span class="text-danger">*</span></label>
                           <div class="controls">
                              <select name="parent_id" class="form-control select2" id="parent_id" @error('parent_id') aria-invalid="true" @endif>
                                 <option value="">Select Parent Category</option>
                                 @foreach($categories as $category)
                                    <option value="{{$category->id}}" {{old('parent_id') == $category->id ? 'selected' : ''}}>{{$category->name}}</option>
                                 @endforeach
                              </select>
                              @error('parent_id') <div class="help-block"><ul role="alert"><li>{{$message}}</li></ul></div> @endif
                           </div>
                        </div>
                     </div>
                     <div class="col-md-6"> 
                        <div class="form-group  @error('category_id') error @endif">
                           <label class="form-label">Sub Category <span class="text-danger">*</span></label>
                           <div class="controls">
                              <select name="category_id" class="form-control select2" id="category_id" @error('category_id') aria-invalid="true" @endif>
                                 <option value="">Select Sub Category</option>
                                 <!-- Options are appened dynamically here -->
                              </select>
                              @error('category_id') <div class="help-block"><ul role="alert"><li>{{$message}}</li></ul></div> @endif
                           </div>
                        </div>
                     </div>
                     <div class="col-md-6"> 
                        <div class="form-group  @error('trending_category_id.*') error @endif">
                           <label class="form-label">Trending Categories</label>
                           <div class="controls">
                              <select name="trending_category_id[]" class="form-control select2" id="trending_category_id" @error('trending_category_id.*') aria-invalid="true" @endif multiple>
                                 <!-- <option value="">Select Trending Category</option> -->
                                 @foreach($trending_categories as $trend_cate)
                                    <option value="{{$trend_cate->id}}" {{(in_array($trend_cate->id,old('trending_category_id',[]))) ? 'selected' : ''}}>{{$trend_cate->name}}</option>
                                 @endforeach
                              </select>
                              @error('trending_category_id.*') <div class="help-block"><ul role="alert"><li>{{$message}}</li></ul></div> @endif
                           </div>
                        </div>
                     </div>
                     <div class="col-md-6"> 
                        <div class="form-group  @error('name') error @endif">
                           <label class="form-label">Name <span class="text-danger">*</span></label>
                           <div class="controls">
                              <input type="text" name="name" class="form-control" value="{{old('name')}}" @error('name') aria-invalid="true" @endif> 
                              @error('name') <div class="help-block"><ul role="alert"><li>{{$message}}</li></ul></div> @endif
                           </div>
                        </div>
                     </div>
                     <div class="col-md-3"> 
                        <div class="form-group  @error('medicine_power.*') error @endif">
                           <label class="form-label">Medicine Power</label>
                           <div class="controls">
                              <select name="medicine_power[]" class="form-control select2" @error('medicine_power.*') aria-invalid="true" @endif multiple>
                                    <option value="6" {{in_array('6', old('medicine_power',[])) ? 'selected' : ''}}>6</option>
                                    <option value="30" {{in_array('30', old('medicine_power',[])) ? 'selected' : ''}}>30</option>
                                    <option value="200" {{in_array('200', old('medicine_power',[])) ? 'selected' : ''}}>200</option>
                                    <option value="1M" {{in_array('1M', old('medicine_power',[])) ? 'selected' : ''}}>1M</option>
                              </select>
                              @error('medicine_power.*') <div class="help-block"><ul role="alert"><li>{{$message}}</li></ul></div> @endif
                           </div>
                        </div>
                     </div>
                     <div class="col-md-3">                
                        <div class="form-group @error('image') error @endif">
                           <label class="form-label">Product Image <span class="text-danger">*</span></label>
                           <div class="controls">
                              <input type="file" class="form-control browse-btn" name="image" accept="image/*,text/plain" @error('image') aria-invalid="true" @endif> 
                              @error('image') <div class="help-block"><ul role="alert"><li>{{$message}}</li></ul></div> @endif
                           </div>
                        </div>
                     </div>
                     <div class="col-md-6">                
                        <div class="form-group @error('product_images.*') error @endif">
                           <label class="form-label">Slider Images <span class="text-danger">*</span></label>
                           <div class="controls">
                              <input type="file" class="form-control browse-multiple-btn" name="product_images[]" accept="image/*,text/plain" @error('product_images.*') aria-invalid="true" @endif multiple> 
                              @error('product_images.*') <div class="help-block"><ul role="alert"><li>{{$message}}</li></ul></div> @endif
                              <div id="productImagePreview" class="d-flex flex-wrap gap-1">
                                 <!-- Images are append here after selection -->
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="col-md-12"> 
                        <div class="form-group">
                           <!-- <label class="form-label">Different Pack Price</label> -->
                           <button type="button" class="btn btn-sm btn-primary addMorePackPrice">Add Pack Price</button>
                           <?php
                              $pack_sizes = old('pack_size') ? old('pack_size') : [];
                              $pack_prices = old('pack_price') ? old('pack_price') : [];
                              $pack_special_prices = old('pack_special_price') ? old('pack_special_price') : [];
                              $pack_count = max(count($pack_sizes), count($pack_prices), count($pack_special_prices));
                           ?>
                           @if($pack_count)
                              @for($i = 0; $i < $pack_count; $i++)
                                 <div class="row {{$i}}">
                                    <div class="col-md-4">
                                       <div class="form-group  @error('pack_size.'.$i) error @endif">
                                          <label class="form-label">Pack Size <span class="text-danger">*</span></label>
                                          <div class="controls">
                                             <input type="text" class="form-control" name="pack_size[]" placeholder="Default" value="{{ old('pack_size.'.$i) }}" @error('pack_size.'.$i) aria-invalid="true" @endif> 
                                             @error('pack_size.'.$i) <div class="help-block"><ul role="alert"><li>{{$message}}</li></ul></div> @endif
                                          </div>
                                       </div>
                                    </div>
                                    <div class="col-md-4">
                                       <div class="form-group  @error('pack_price.'.$i) error @endif">
                                          <label class="form-label">Pack Price <span class="text-danger">*</span></label>
                                          <div class="controls">
                                             <input type="text" placeholder="150" class="form-control" name="pack_price[]" value="{{ old('pack_price.'.$i) }} @error('pack_price.'.$i)" aria-invalid="true" @endif oninput="this.value = this.value.replace(/[^0-9.]/g, '')" onpaste="this.value = this.value.replace(/[^0-9.]/g, ''); return false;"> 
                                             @error('pack_price.'.$i) <div class="help-block"><ul role="alert"><li>{{$message}}</li></ul></div> @endif
                                          </div>
                                       </div>
                                    </div>
                                    <div class="col-md-3">
                                       <div class="form-group  @error('pack_special_price.'.$i) error @endif">
                                          <label class="form-label">Pack Special Price</label>
                                          <div class="controls">
                                             <input type="text" placeholder="100" class="form-control" name="pack_special_price[]" value="{{ old('pack_special_price.'.$i) }}" @error('pack_special_price.'.$i) aria-invalid="true" @endif oninput="this.value = this.value.replace(/[^0-9.]/g, '')" onpaste="this.value = this.value.replace(/[^0-9.]/g, ''); return false;"> 
                                             @error('pack_special_price.'.$i) <div class="help-block"><ul role="alert"><li>{{$message}}</li></ul></div> @endif
                                          </div>
                                       </div>
                                    </div>
                                    <div class="col-md-1">
                                       <a href="javascript:void(0)" class="btn btn-sm btn-danger removePackPrice">x</a>
                                    </div>
                                 </div>
                              @endfor
                           @endif
                           <!-- Add More Pack Sizes Dynamically -->
                        </div>
                     </div>
                     @if ($errors->has('pack_size.0') || $errors->has('pack_price.0'))
                        <span class="text-danger mb-3">At least one product pack size and price is required.</span>
                     @elseif($errors->has('pack_size') || $errors->has('pack_price'))
                        <span class="text-danger mb-3">{{ $errors->first('pack_size') ?: $errors->first('pack_price') }}</span>
                     @endif
                     <div class="col-md-12"> 
                        <div class="form-group  @error('description') error @endif">
                           <label class="form-label">Description <span class="text-danger">*</span></label>
                           <div class="controls">
                              <textarea name="description" class="form-control" @error('description') aria-invalid="true" @endif>{{old('description')}}</textarea>
                              @error('description') <div class="help-block"><ul role="alert"><li>{{$message}}</li></ul></div> @endif
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
<script type="text/javascript">
   var parent_id = "{{old('parent_id')}}";
   if(parent_id){
      subCategoryLists();
   }
   $(document).on('change','#parent_id', function(){
      var parent_id = $(this).val();
      subCategoryLists();
   });
   /* Article Add More START */
      $(document).on('click','.addMorePackPrice',function () {
         var packHtml = `<div class="row">
                           <div class="col-md-4">
                              <div class="form-group ">
                                 <label class="form-label">Pack Size <span class="text-danger">*</span></label>
                                 <div class="controls">
                                    <input type="text" class="form-control" name="pack_size[]" placeholder="Default"> 
                                 </div>
                              </div>
                           </div>
                           <div class="col-md-4">
                              <div class="form-group">
                                 <label class="form-label">Pack Price <span class="text-danger">*</span></label>
                                 <div class="controls">
                                    <input type="text" placeholder="150" class="form-control" name="pack_price[]" oninput="this.value = this.value.replace(/[^0-9.]/g, '')" onpaste="this.value = this.value.replace(/[^0-9.]/g, ''); return false;"> 
                                 </div>
                              </div>
                           </div>
                           <div class="col-md-3">
                              <div class="form-group">
                                 <label class="form-label">Pack Special Price</label>
                                 <div class="controls">
                                    <input type="text" placeholder="100" class="form-control" name="pack_special_price[]" oninput="this.value = this.value.replace(/[^0-9.]/g, '')" onpaste="this.value = this.value.replace(/[^0-9.]/g, ''); return false;"> 
                                 </div>
                              </div>
                           </div>
                           <div class="col-md-1">
                              <a href="javascript:void(0)" class="btn btn-sm btn-danger removePackPrice">x</a>
                           </div>
                        </div>`;
         $(this).parent().append(packHtml);
     });
     $(document).on('click','.removePackPrice',function(){
         $(this).parent().parent().remove();
     })
     $(document).on('focus','.articleTitleInput',function() {

         $(this).parent().addClass('is-focused');
     })
     $(document).on('blur','.articleTitleInput',function() {
         if($(this).val()){
             $(this).parent().addClass('is-filled');
         }
         $(this).parent().removeClass('is-focused');
     });
     $(document).on('focus','.articleDescriptionInput',function() {

         $(this).parent().addClass('is-focused');
     })
     $(document).on('blur','.articleDescriptionInput',function() {
         if($(this).val()){
             $(this).parent().addClass('is-filled');
         }
         $(this).parent().removeClass('is-focused');
     });
   /* Article Add More END */
   async function subCategoryLists() {
      var parent_id = $('#parent_id option:selected').val();
      var category_id = "{{ old('category_id') }}";
      var url = "{{ url('sub-categories') }}?parent_id="+parent_id;
      let out = await fetch(url).catch((err) => {console.error(err)});
      out = await out.json();
      var category_options = '<option value="">Select Sub Category</option>';
      if(out.data.categories.length){
         for (let category of out.data.categories) {
            if(category_id==category.id){
                category_options+='<option value="'+category.id+'" selected>'+category.name+'</option>';
            }else{
               category_options+='<option value="'+category.id+'">'+category.name+'</option>'; 
            }
         }
      }
      $('#category_id').html(category_options);
   }
</script>
@endpush