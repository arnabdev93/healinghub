{{-- 'price.quote.requests.index' --}}
{{-- @extends('layouts.app')
@section('page_title')
   
@endsection
@section('content')
   <div class="content-header">
      <div class="d-flex align-items-center">
         <div class="me-auto healbreadcrumb">
            <h4 class="page-title">Edit Price Quote</h4>
            <div class="d-inline-block align-items-center">
               <nav>
                  <ol class="breadcrumb">
                     <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="mdi mdi-home-outline"></i></a></li>
                     <li class="breadcrumb-item"><a href="{{route('price.quote.requests.index')}}">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-banner" style="width: 1em; height: 1em; display: inline;">
                           <path d="M4 15s1-1 5-1 5.998 1 8 1c1.657 0 2.357-.649 2.75-1.025V4c-.504.501-1.363 1.003-2.75 1.003C13.998 5 10 4 5 4 1 4 0 8 0 11.667c0 4 1 6.585 4 7.585"></path>
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
                <form method="POST" action="{{ route('price.quote.requests.update',$quote->id) }}">
                    @csrf
                    <div class="row">
                        <div class="col-xl-6 col-12">
                            <div class="form-groups mb-3">
                                <label class="form-label">User Name</label>
                                <input type="text" class="form-control" value="{{ $quote->user->name ?? '' }}" readonly>
                            </div>
                        </div>
                        @php
                            $building = isset($quote->address->building) ? $quote->address->building.', ' : '';
                            $address = isset($quote->address->address) ? $quote->address->address.', ' : '';
                            $city = isset($quote->address->city) ? $quote->address->city.', ' : '';
                            $state = isset($quote->address->state) ? $quote->address->state.', ' : '';
                            $country = isset($quote->address->country) ? $quote->address->country : '';
                            $address = $building.$address.$city.$state.$country.$address
                        @endphp
                        <div class="col-xl-6 col-12">
                            <div class="form-groups mb-3">
                                <label class="form-label">User Address</label>
                                <textarea class="form-control" rows="3" readonly>
                                {{ (string)$address }}
                                PIN - {{ $quote->address->country ?? '' }}
                                </textarea>
                            </div>
                        </div>
                        <div class="col-xl-6 col-12">
                            <div class="form-groups mb-3">
                                <label class="form-label">Notes</label>
                                <textarea name="notes" class="form-control" rows="3">{{ $quote->notes }}</textarea>
                            </div>
                        </div>
                        <div class="col-xl-6 col-12">
                            <div class="form-groups mb-3">
                                <label class="form-label">Price</label>
                                <input type="number" name="price" class="form-control" value="{{ $quote->price }}">
                            </div>
                        </div>
                        <div class="col-xl-6 col-12">
                            <div class="form-groups mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-control">
                                    <option value="pending" {{ $quote->status=='pending' ? 'selected':'' }}>
                                        Pending
                                    </option>
                                    <option value="quoted" {{ $quote->status=='quoted' ? 'selected':'' }}>
                                        Quoted
                                    </option>
                                    <option value="approved" {{ $quote->status=='approved' ? 'selected':'' }}>
                                        Approved
                                    </option>
                                    <option value="rejected" {{ $quote->status=='rejected' ? 'selected':'' }}>
                                        Rejected
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="form-groups mb-3">
                            <button type="submit" class="btn btn-primary">
                                Update Quote
                            </button>
                            <a href="{{ route('price.quote.requests.index') }}" class="btn btn-secondary">Back</a>
                        </div>
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
@endsection --}}
@extends('layouts.app')

@section('page_title')
Edit Prescription
@endsection

@section('content')

<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto healbreadcrumb">

            <h4 class="page-title">Edit Prescription</h4>

            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">

                        <li class="breadcrumb-item">
                            <a href="{{ route('home') }}">
                                <i class="mdi mdi-home-outline"></i>
                            </a>
                        </li>

                        <li class="breadcrumb-item">
                            <a href="{{route('price.quote.requests.index')}}">
                                Prescriptions
                            </a>
                        </li>

                        <li class="breadcrumb-item active">
                            Update
                        </li>

                    </ol>
                </nav>
            </div>

        </div>
    </div>
</div>

<section class="content">

<div class="box">
<div class="box-body">

<div class="row">
<div class="col">

<form method="POST" action="{{ route('price.quote.requests.update',$quote->id) }}">
@csrf

<div class="row">

{{-- USER NAME --}}
<div class="col-xl-6 col-12">
<div class="form-group mb-3">

<label class="form-label">User Name</label>

<input type="text"
class="form-control"
value="{{ $quote->user->name ?? '' }}"
readonly>

</div>
</div>


{{-- ORDER NUMBER --}}
<div class="col-xl-6 col-12">
<div class="form-group mb-3">

<label class="form-label">Order No</label>

<input type="text"
class="form-control"
value="{{ $quote->orderno }}"
readonly>

</div>
</div>


{{-- NOTES --}}
<div class="col-xl-6 col-12">
<div class="form-group mb-3">

<label class="form-label">Notes</label>

<textarea name="notes"
class="form-control"
rows="3">{{ $quote->notes }}</textarea>

</div>
</div>


{{-- STATUS --}}
<div class="col-xl-6 col-12">
<div class="form-group mb-3">

<label class="form-label">Status</label>

<select name="status" class="form-control">

<option value="pending"
{{ $quote->status == 'pending' ? 'selected' : '' }}>
Pending
</option>

<option value="accept"
{{ $quote->status == 'accept' ? 'selected' : '' }}>
Accepted
</option>

<option value="reject"
{{ $quote->status == 'reject' ? 'selected' : '' }}>
Rejected
</option>

<option value="cancel"
{{ $quote->status == 'cancel' ? 'selected' : '' }}>
Cancelled
</option>

</select>

</div>
</div>


{{-- PRESCRIPTION IMAGES --}}
<div class="col-12">

<label class="form-label">Prescription Images</label>

<div class="row">

@forelse($quote->items as $item)

<div class="col-md-2 mb-3">

<a href="{{ asset('storage/'.$item->image) }}" target="_blank">

<img src="{{ asset('storage/'.$item->image) }}"
class="img-fluid rounded border"
style="height:100px; object-fit:cover">

</a>

</div>

@empty

<div class="col-12">
<p>No Prescription Images</p>
</div>

@endforelse

</div>

</div>


{{-- BUTTONS --}}
<div class="col-12 mt-3">

<button type="submit" class="btn btn-primary">
Update Prescription
</button>

<a href="{{route('price.quote.requests.index')}}"
class="btn btn-secondary">
Back
</a>

</div>


</div>
</form>

</div>
</div>

</div>
</div>

</section>

@endsection