@extends('layouts.app')
@section('page_title')
   Update Settings
@endsection
@section('content')
   <div class="content-header">
      <div class="d-flex align-items-center">
         <div class="me-auto healbreadcrumb">
            <h4 class="page-title">Update Settings</h4>
            <div class="d-inline-block align-items-center">
               <nav>
                  <ol class="breadcrumb">
                     <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="mdi mdi-home-outline"></i></a></li>
                     <li class="breadcrumb-item active" aria-current="page">Update Settings</li>
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
                  <form action="{{ route('earning-percentage.update') }}" method="POST">
                    @csrf

                    <div class="form-group mb-3" style="max-width: 400px;">
                        <label class="form-label">
                            <strong>Earning Percentage From Doctor</strong>
                        </label>
                        <input type="number" step="0.01" name="earning_percentage" class="form-control"
                            value="{{ old('earning_percentage', $setting->item_value ?? '') }}"
                            required>
                        @error('earning_percentage')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="text-xs-right">
                            <button type="submit" class="btn btn-sm btn-info">Update</button>
                    </div>
                </form>
               </div>
            </div>
         </div>
      </div>
   </section>
@endsection
