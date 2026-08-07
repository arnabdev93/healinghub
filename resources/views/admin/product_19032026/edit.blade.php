@extends('layouts.app')
@section('page_title')
   
@endsection
@section('content')
   <div class="content-header">
      <div class="d-flex align-items-center">
         <div class="me-auto healbreadcrumb">
            <h4 class="page-title">Update Product</h4>
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
               @if($errors->any())
                   {{ implode('', $errors->all('<div>:message</div>')) }}
               @endif
               <form role="form" action="{{ route('products.update',$product->id) }}" method="post" enctype="multipart/form-data">
                  @csrf
                  @method("PUT")
                 <div class="row">
                     <div class="col-md-6"> 
                        <div class="form-group  @error('parent_id') error @endif">
                           <label class="form-label">Parent Category <span class="text-danger">*</span></label>
                           <div class="controls">
                              <select name="parent_id" class="form-control select2" id="parent_id" @error('parent_id') aria-invalid="true" @endif>
                                 <option value="">Select Parent Category</option>
                                 @foreach($categories as $category)
                                    <option value="{{$category->id}}" {{old('parent_id',$product->category->parent_id) == $category->id ? 'selected' : ''}}>{{$category->name}}</option>
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
                     <?php
                        $product_trending_categories = $product->trending_categories()->pluck('category_id')->toArray();
                     ?>
                     <div class="col-md-6">
                        <div class="form-group  @error('trending_category_id') error @endif">
                           <label class="form-label">Trending Categories</label>
                           <div class="controls">
                              <select name="trending_category_id[]" class="form-control select2" id="trending_category_id" @error('trending_category_id') aria-invalid="true" @endif multiple>
                                 <!-- <option value="">Select Trending Category</option> -->
                                 @foreach($trending_categories as $trend_cate)
                                    <option value="{{$trend_cate->id}}" {{(in_array($trend_cate->id,old('trending_category_id', $product_trending_categories))) ? 'selected' : ''}}>{{$trend_cate->name}}</option>
                                 @endforeach
                              </select>
                              @error('trending_category_id') <div class="help-block"><ul role="alert"><li>{{$message}}</li></ul></div> @endif
                           </div>
                        </div>
                     </div>
                     <div class="col-md-6"> 
                        <div class="form-group  @error('name') error @endif">
                           <label class="form-label">Name <span class="text-danger">*</span></label>
                           <div class="controls">
                              <input type="text" name="name" class="form-control" value="{{old('name',$product->name)}}" @error('name') aria-invalid="true" @endif> 
                              @error('name') <div class="help-block"><ul role="alert"><li>{{$message}}</li></ul></div> @endif
                           </div>
                        </div>
                     </div>
                     <?php
                        $medicine_powers = [];
                        $product_medicine_power = $product->medicine_power;
                        if($product_medicine_power){
                           $medicine_powers = explode(',',$product_medicine_power);
                        }
                     ?>
                     <div class="col-md-3"> 
                        <div class="form-group  @error('medicine_power.*') error @endif">
                           <label class="form-label">Medicine Power</label>
                           <div class="controls">
                              <select name="medicine_power[]" class="form-control select2" @error('medicine_power.*') aria-invalid="true" @endif multiple>
                                    <option value="6" {{in_array('6', old('medicine_power', $medicine_powers)) ? 'selected' : ''}}>6</option>
                                    <option value="30" {{in_array('30', old('medicine_power', $medicine_powers)) ? 'selected' : ''}}>30</option>
                                    <option value="200" {{in_array('200', old('medicine_power', $medicine_powers)) ? 'selected' : ''}}>200</option>
                                    <option value="1M" {{in_array('1M', old('medicine_power', $medicine_powers)) ? 'selected' : ''}}>1M</option>
                              </select>
                              @error('medicine_power.*') <div class="help-block"><ul role="alert"><li>{{$message}}</li></ul></div> @endif
                           </div>
                        </div>
                     </div>
                     <div class="col-md-3">                
                        <div class="form-group @error('image') error @endif">
                           <label class="form-label">Product Image</label>
                           <div class="controls">
                              <input type="file" class="form-control browse-btn" name="image" accept="image/*,text/plain" @error('image') aria-invalid="true" @endif> 
                              @error('image') <div class="help-block"><ul role="alert"><li>{{$message}}</li></ul></div> @endif
                              <img src="{{$product->image_path}}" height="50" width="50" class="preview">
                           </div>
                        </div>
                     </div>
                     <div class="col-md-6">                
                        <div class="form-group @error('product_images.*') error @endif">
                           <label class="form-label">Slider Images</label>
                           <div class="controls">
                              <input type="file" class="form-control browse-multiple-btn" name="product_images[]" accept="image/*,text/plain" @error('product_images.*') aria-invalid="true" @endif multiple> 
                              @error('product_images.*') <div class="help-block"><ul role="alert"><li>{{$message}}</li></ul></div> @endif
                              
                              <div id="productImagePreview" class="d-flex flex-wrap gap-1">
                                 @foreach ($product->images as $productImgKey => $productImg)
                                    <div class="image-wrapper">
                                       <img src="{{ $productImg->image_path }}" alt="#" >
                                       <a href="javascript:void(0)" class="delete-icon itemImageDelete" data-url="{{ route('product-image.destroy',$productImg->id) }}" style="z-index: 2;">&times;</a>
                                    </div>
                                 @endforeach
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
                              $productPackPrices = ($product->prices->count()>0) ? $product->prices->toArray() : [];
                              $pack_count = max(count($pack_sizes), count($pack_prices), count($pack_special_prices), count($productPackPrices));
                           ?>
                           @if($pack_count)
                              @for($i = 0; $i < $pack_count; $i++)
                                 <div class="row {{$i}}">
                                    <input type="hidden" name="product_price_id[]" value="{{ !empty($productPackPrices[$i]) ? $productPackPrices[$i]['id'] : '' }}">
                                    <div class="col-md-4">
                                       <div class="form-group  @error('pack_size.'.$i) error @endif">
                                          <label class="form-label">Pack Size <span class="text-danger">*</span></label>
                                          <div class="controls">
                                             <input type="text" class="form-control" name="pack_size[]" placeholder="Default" value="{{ old('pack_size.'.$i) ? old('pack_size.'.$i) : (!empty($productPackPrices[$i]) ? $productPackPrices[$i]['pack_size'] : '') }}" @error('pack_size.'.$i) aria-invalid="true" @endif> 
                                             @error('pack_size.'.$i) <div class="help-block"><ul role="alert"><li>{{$message}}</li></ul></div> @endif
                                          </div>
                                       </div>
                                    </div>
                                    <div class="col-md-4">
                                       <div class="form-group  @error('pack_price.'.$i) error @endif">
                                          <label class="form-label">Pack Price <span class="text-danger">*</span></label>
                                          <div class="controls">
                                             <input type="text" placeholder="150" class="form-control" name="pack_price[]" value="{{ old('pack_price.'.$i) ? old('pack_price.'.$i) : (!empty($productPackPrices[$i]) ? $productPackPrices[$i]['price'] : '') }}" @error('pack_price.'.$i)aria-invalid="true" @endif oninput="this.value = this.value.replace(/[^0-9.]/g, '')" onpaste="this.value = this.value.replace(/[^0-9.]/g, ''); return false;"> 
                                             @error('pack_price.'.$i) <div class="help-block"><ul role="alert"><li>{{$message}}</li></ul></div> @endif
                                          </div>
                                       </div>
                                    </div>
                                    <div class="col-md-3">
                                       <div class="form-group  @error('pack_special_price.'.$i) error @endif">
                                          <label class="form-label">Pack Special Price</label>
                                          <div class="controls">
                                             <input type="text" placeholder="100" class="form-control" name="pack_special_price[]" value="{{ old('pack_special_price.'.$i) ? old('pack_special_price.'.$i) : (!empty($productPackPrices[$i]) ? $productPackPrices[$i]['special_price'] : '') }}" @error('pack_special_price.'.$i) aria-invalid="true" @endif oninput="this.value = this.value.replace(/[^0-9.]/g, '')" onpaste="this.value = this.value.replace(/[^0-9.]/g, ''); return false;"> 
                                             @error('pack_special_price.'.$i) <div class="help-block"><ul role="alert"><li>{{$message}}</li></ul></div> @endif
                                          </div>
                                       </div>
                                    </div>
                                    <div class="col-md-1">
                                       <a href="javascript:void(0)" class="btn btn-sm btn-danger removePackPrice" data-id="{{ !empty($productPackPrices[$i]) ? $productPackPrices[$i]['id'] : '' }}">x</a>
                                    </div>
                                 </div>
                              @endfor
                           @endif
                           <!-- Add More Pack Sizes Dynamically -->
                        </div>
                     </div>
                     <div class="col-md-12"> 
                        <div class="form-group  @error('description') error @endif">
                           <label class="form-label">Description <span class="text-danger">*</span></label>
                           <div class="controls">
                              <textarea name="description" class="form-control" @error('description') aria-invalid="true" @endif>{{old('description',$product->description)}}</textarea>
                              @error('description') <div class="help-block"><ul role="alert"><li>{{$message}}</li></ul></div> @endif
                           </div>
                        </div>
                     </div>
                     
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
<script type="text/javascript">
   var parent_id = "{{old('parent_id',$product->category->parent_id)}}";
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
                           <input type="hidden" name="product_price_id[]" value="">
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
                              <a href="javascript:void(0)" class="btn btn-sm btn-danger removePackPrice" data-id="">x</a>
                           </div>
                        </div>`;
         $(this).parent().append(packHtml);
      });
      $(document).on('click','.removePackPrice',function(){
         var productPriceID = $(this).data('id');
         var ele = $(this);
         if(productPriceID){
            Swal.fire({
              title: 'Are you sure?',
              text: "You won't be able to revert this!",
              position: 'top-end',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Delete'
            }).then((result) => {
                if (result.isConfirmed) {
                  $('.loader').show();
                    var url = "{{ route('product-price.destroy',':id') }}";
                    url = url.replace(':id',productPriceID);
                    ele.attr('disabled',true);
                    $.ajax({
                        url: url,
                        type: "DELETE",
                        data:  {
                           '_token':"{{ csrf_token() }}"
                        },
                        success: function(data){
                           $('.loader').hide();
                            if(data.status){
                              icon = 'success';
                              Toast.fire({
                                 icon: icon,
                                 title: data.message
                              });
                              ele.parent().parent().remove();
                           }else{
                              icon = 'error';
                              ele.attr('disabled',false);
                              Toast.fire({
                                 icon: icon,
                                 title: data.message
                              });
                           }
                        },
                        error: function(xhr){
                           ele.attr('disabled',false);
                           $('.loader').hide();
                           Toast.fire({
                              icon: 'error',
                              title: 'Status:'+xhr.status+' Message:'+xhr.responseText
                           });
                        }
                    });
                }
            });
         }else{
            ele.parent().parent().remove();
         }
      });
     
   /* Article Add More END */
   async function subCategoryLists() {
      var parent_id = $('#parent_id option:selected').val();
      var category_id = "{{ old('category_id',$product->category_id) }}";
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