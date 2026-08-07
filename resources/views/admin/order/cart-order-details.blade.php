@extends('layouts.app')
@section('page_title')

@endsection
@section('content')
   <div class="content-header">
      <div class="d-flex align-items-center">
         <div class="me-auto healbreadcrumb">
            <h4 class="page-title">Cart Order Details</h4>
            <div class="d-inline-block align-items-center">
               <nav>
                  <ol class="breadcrumb">
                     <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="mdi mdi-home-outline"></i></a></li>
                     <li class="breadcrumb-item"><a href="{{route('cart-orders')}}">
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
         <div class="col-12">
            <div class="card">
               <div class="card-body">
                  <div class="row">
                     <div class="col-lg-8">
                        <div class="table-responsive">
                           {!! $dataTable->table(['class'=>'table table-bordered table-centered mb-0'],true) !!}
                        </div> <!-- end table-responsive-->
                     </div>
                     <!-- end col -->

                     <div class="col-lg-4">
                        <div class="border p-3 mt-4 mt-lg-0 rounded">
                           <h4 class="header-title mb-3">Order Summary</h4>

                           <div class="table-responsive">
                              <table class="table mb-0">
                                 <tbody>
                                    <tr>
                                       <td>Total Products :</td>
                                       <td class="fw-500">{{ $totalProducts }} Items</td>
                                    </tr>
                                    <tr>
                                       <td>Order No :</td>
                                       <td class="fw-500">{{ $order->orderno }}</td>
                                    </tr>
                                    <tr>
                                       <td>Status :</td>
                                       <td class="fw-500">{{ ucfirst($order->status) }}</td>
                                    </tr>
                                    <tr>
                                       <th>Total :</th>
                                       <th>₹ {{ $order->total }}</th>
                                    </tr>
                                 </tbody>
                              </table>
                           </div>
                        </div>
                     </div> <!-- end col -->
                     {{-- new --}}
                    <div class="col-lg-8">
                        <div class="border p-3 rounded mb-3">
                            <h4 class="header-title mb-3">Customer Details</h4>

                            <div class="table-responsive">
                                <table class="table mb-0">
                                <tbody>
                                    <tr>
                                        <td>Name :</td>
                                        <td class="fw-500">{{ $user->name ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Mobile :</td>
                                        <td class="fw-500">{{ $user->mobile ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Email :</td>
                                        <td class="fw-500">{{ $user->email ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Address :</td>
                                        <td class="fw-500">
                                            @if($address)
                                            @if($address->building)
                                                {{ $address->building }},
                                            @endif
                                            {{ $address->address }}, {{ $address->city }}, {{ $address->state }} - {{ $address->pincode }}, {{ $address->country }}
                                            @else
                                            N/A
                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label"><strong>Select Status</strong></label>
                        <select name="status_changes" id="status_changes"
                                class="form-control select2-filter"
                                data-id="{{ $order->id }}">
                            <option value="">Select Status</option>

                            @if($order->status == 'pending')
                                <option value="pending" selected disabled>Pending</option>
                            @endif

                            <option value="accept" {{ $order->status == 'accept' ? 'selected' : '' }}>Accept</option>
                            <option value="reject" {{ $order->status == 'reject' ? 'selected' : '' }}>Reject</option>
                            <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                            <option value="cancel" {{ $order->status == 'cancel' ? 'selected' : '' }}>Cancel</option>
                        </select>
                    </div>

                  </div> <!-- end row -->
               </div> <!-- end card-body-->
            </div> <!-- end card-->
         </div> <!-- end col -->
      </div>
      <!-- end row -->
   </section>
@endsection
@push('page_scripts')
{!! $dataTable->scripts() !!}
<script>
$(document).ready(function () {
    $('#status_changes').on('change', function () {
        var status = $(this).val();
        var orderId = $(this).data('id');
        if (!status) return;
        $.ajax({
            url: "{{ url('cart-orders/update-status') }}/" + orderId,
            type: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                status: status
            },
            success: function (response) {
                if (response.success) {
                    Toast.fire({
                        icon: 'success',
                        title: response.message
                    });
                } else {
                    Toast.fire({
                        icon: 'error',
                        title: response.message
                    });
                }
            },
            error: function (xhr) {
                Toast.fire({
                    icon: 'error',
                    title: 'Something went wrong!'
                });
            }
        });
    });
});
</script>
@endpush
