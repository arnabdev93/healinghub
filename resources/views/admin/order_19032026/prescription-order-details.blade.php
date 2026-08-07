@extends('layouts.app')
@section('page_title')
   
@endsection
@section('content')
   <div class="content-header">
      <div class="d-flex align-items-center">
         <div class="me-auto healbreadcrumb">
            <h4 class="page-title">Prescription Order Details</h4>
            <div class="d-inline-block align-items-center">
               <nav>
                  <ol class="breadcrumb">
                     <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="mdi mdi-home-outline"></i></a></li>
                     <li class="breadcrumb-item"><a href="{{route('prescription-orders')}}">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 1em; height: 1em; display: inline;">
                           <path d="M4 2h16a2 2 0 0 1 2 2v16a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2z"></path>
                           <line x1="12" y1="7" x2="12" y2="17"></line>
                           <line x1="7" y1="12" x2="17" y2="12"></line>
                           <text x="4" y="4" font-size="8" fill="currentColor">Rx</text>
                        </svg>
                        </a></li>
                     <li class="breadcrumb-item active" aria-current="page">Details</li>
                  </ol>
               </nav>
            </div>
         </div>
         
      </div>
   </div>
   <section class="content">
      <div class="row">
         <div class="col-lg-12">
             <div class="box">
                 <div class="box-body">
                     <div class="row">
                         <div class="col-md-4 col-sm-6">
                             <div class="box box-body b-1 text-center no-shadow">
                                 <a href="{{$order->items[0]->image_path}}" id="product-image-ahref" target="_blank"><img src="{{$order->items[0]->image_path}}" id="product-image" class="img-fluid" alt="" /></a>
                             </div>
                             <div class="pro-photos">
                                 @foreach($order->items as $key=>$item)
                                    <div class="photos-item {{ ($key==0) ? 'item-active' : '' }}">
                                       <img src="{{$item->image_path}}" alt="">
                                    </div>
                                 @endforeach
                                 
                             </div>
                             <div class="clear"></div>
                         </div>
                         <div class="col-md-8 col-sm-6">
                             
                                 <!-- Product title -->
                                 <h3 class="mt-0">{{$user->name}} <a href="javascript: void(0);" class="text-muted"><i class="mdi mdi-square-edit-outline ms-2"></i></a> </h3>
                                 <p class="fs-16">
                                    {{$user->mobile}}
                                 </p>
                                 <p class="fs-16">
                                    <?php
                                    $new_address = '';
                                     if ($address->building) {
                                         $new_address = $address->building.', ';
                                     }
                                    echo $new_address.$address->address.', '.$address->city.', '.$address->state.' - '.$address->pincode.', '.$address->country;
                                    ?>
                                 </p>
                                 <!-- Product stock -->
                                 <div class="mt-3">
                                    <h4>
                                       @if($order->status=='pending')
                                          <div class="btn-group mb-5">
                                            <button type="button" class="waves-effect waves-light btn btn-primary dropdown-toggle" data-bs-toggle="dropdown">Pending</button>
                                            <div class="dropdown-menu">
                                             <a class="dropdown-item statusUpdate" data-status="accept" href="javascript:void(0)">Accept</a>
                                             <a class="dropdown-item statusUpdate" data-status="reject" href="javascript:void(0)">Reject</a>
                                            </div>
                                          </div>
                                          <!-- <span class="badge badge-sm badge-danger-light">Pending</span> -->
                                       @elseif($order->status=='accept')
                                          <span class="badge badge-sm badge-success-light">Accepted</span>
                                       @else
                                          <span class="badge badge-sm badge-warning-light">{{ucfirst($order->status)}}ed</span>
                                       @endif
                                    </h4>
                                 </div>

                                 <!-- Product description -->
                                 <div class="mt-4">
                                     <h6 class="fs-15">Price:</h6>
                                       @if($order->total)
                                          @if($order->transaction_id)
                                          <h3>{{$order->total}}</h3>
                                          @else
                                          <form action="{{route('order-price-update',$order->id)}}" method="POST">
                                             @csrf
                                             <div class="row">
                                                <div class="col-md-4"> 
                                                   <div class="form-group @error('orderprice') error @endif">
                                                      <div class="controls">
                                                         <input type="text" name="orderprice" class="form-control" value="{{$order->total}}" oninput="this.value = this.value.replace(/[^0-9.]/g, '')" onpaste="this.value = this.value.replace(/[^0-9.]/g, ''); return false;"> 
                                                         @error('orderprice') <div class="help-block"><ul role="alert"><li>{{$message}}</li></ul></div> @endif
                                                      </div>
                                                   </div>
                                                </div>
                                                <div class="col-md-8">
                                                   <button type="submit" class="btn btn-sm btn-primary">Update</button>
                                                </div>
                                             </div>
                                          </form>
                                          @endif
                                       @else
                                          <h3 style="color:red;">NOT AVAILABLE</h3>
                                       @endif
                                 </div>
                                 
                                 <div class="mt-4">
                                     <h6 class="fs-15">Description:</h6>
                                     <p class="text-fade">{{$order->notes}}</p>
                                 </div>
                             
                         </div>
                         
                     </div>
                 </div>
             </div>
         </div>
     </div>
   </section>
   <div id="top-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-top">
         <div class="modal-content">
            <div class="modal-header">
               <h4 class="modal-title" id="topModalLabel">Add Price</h4>
               <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
               <!-- <h5>Text in a modal</h5> -->
               <p>
                  <div class="col-md-12"> 
                     <div class="form-group">
                        <label class="form-label">Price <span class="text-danger">*</span></label>
                        <div class="controls">
                           <input type="text" id="modalprice" class="form-control" oninput="this.value = this.value.replace(/[^0-9.]/g, '')" onpaste="this.value = this.value.replace(/[^0-9.]/g, ''); return false;"> 
                        </div>
                     </div>
                  </div>
               </p>
            </div>
            <div class="modal-footer">
               <!-- <button type="button" class="btn btn-primary-light" data-bs-dismiss="modal">Close</button> -->
               <button type="button" class="btn btn-sm btn-primary" id="addPrice">Save changes</button>
            </div>
         </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
   </div><!-- /.modal -->
@endsection
@push('page_scripts')
   <script type="text/javascript">
      $(function () {         
         $('.icolors li').on("click", function() {
            $('.icolors li').removeClass('active');
            $(this).addClass('active');
         });

         $('.photos-item').on("click", function() {
            var src = $(this).children().attr('src');
            $('#product-image').attr('src', src);
            $('#product-image-ahref').attr('href',src);
            $('.photos-item').removeClass('item-active');
            $(this).addClass('item-active');
         });
         $(document).on('click','.statusUpdate',function(){
            var status = $(this).data('status');
            if(status=='accept'){
               $('#top-modal').modal('show');
            }else{
               Swal.fire({
                  title: 'Are you sure?',
                  text: "You won't be able to revert this!",
                  position: 'top-end',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Reject'
               }).then((result) => {
                     if (result.isConfirmed) {
                        $('.loader').show();
                        $.ajax({
                           url: "{{route('prescription-order-status-update')}}",
                           type: "POST",
                           data: {
                              '_token': "{{ csrf_token() }}",
                              'status':status,
                              'id':"{{$order->id}}"
                           },
                           success: function(response) {
                              $('.loader').hide()
                              if (response.status) {
                                  icon = 'success';
                                  Toast.fire({
                                      icon: icon,
                                      title: response.message
                                  });
                                  window.location.reload();
                              } else {
                                 $('.loader').hide()
                                  icon = 'error';
                                  ele.attr('disabled', false);
                                  Toast.fire({
                                      icon: icon,
                                      title: response.message
                                  });
                              }
                           },
                           error: function(xhr) {
                              ele.attr('disabled', false);
                              $('.loader').hide();
                              Toast.fire({
                                  icon: 'error',
                                  title: 'Status:' + xhr.status + ' Message:' + xhr.responseText
                              });
                           }
                        });
                     }
               });
            }
         });
         $(document).on('click','#addPrice',function() {
            $('.loader').show();
            var ele = $(this);
            ele.attr('disabled', true);
            $.ajax({
               url: "{{route('prescription-order-status-update')}}",
               type: "POST",
               data: {
                  '_token': "{{ csrf_token() }}",
                  'status':'accept',
                  'price':$('#modalprice').val(),
                  'id':"{{$order->id}}"
               },
               success: function(response) {
                  $('.loader').hide()
                  ele.attr('disabled', false);
                  if (response.status==1) {
                      icon = 'success';
                      Toast.fire({
                          icon: icon,
                          title: response.message
                      });
                      window.location.reload();
                  } else if(response.status==2){
                     $('#modalprice').parent().parent().addClass('error');
                     $('#modalprice').parent().find('.help-block').remove();
                     $('#modalprice').parent().append('<div class="help-block"><ul role="alert"><li>Price is required</li></ul></div>');                     
                  } else{
                      icon = 'error';
                      Toast.fire({
                          icon: icon,
                          title: response.message
                      });
                  }
               },
               error: function(xhr) {
                  ele.attr('disabled', false);
                  $('.loader').hide();
                  Toast.fire({
                      icon: 'error',
                      title: 'Status:' + xhr.status + ' Message:' + xhr.responseText
                  });
               }
            });
         });
     });
   </script>
@endpush