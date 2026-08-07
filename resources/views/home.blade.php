@extends('layouts.app')
@section('page_title')
   
@endsection
@section('content')
   <!-- Home Page -->
   <section class="content">
      <div class="row">
         <div class="col-xxl-8 col-xl-7">
            <div class="box" style="background: url('{{ asset('images/svg-icon/banner.svg') }}'); background-size: cover; background-position: center;">
               <div class="box-body">
                  <h2 class="mt-0 text-white fw-600">Hello Admin!</h2>
                  <p class="m-0 text-white">Here are your important task, Updates and alerts.</p>
                  <p class="text-white mb-3">You can set your in app preferences here.</p>
               </div>
            </div>
         </div>

         {{-- New user/doctor/appointment count starts--}}
         <div class="col-xxl-4 col-xl-5">
            <div class="box">
               <div class="box-body">
                  <div class="row">
                     <div class="col-4">
                        <h3 class="fw-600 text-primary text-center">{{ $cartOrders }}</h3>
                        <p class="text-fade text-center small"><small>Total Cart Orders</small></p>
                     </div>
                     <div class="col-4">
                        <h3 class="fw-600 text-info text-center">{{ $prescriptionOrders }}</h3>
                        <p class="text-fade text-center small"><small>Total Prescription Orders</small></p>
                     </div>
                     <div class="col-4">
                        <h3 class="fw-600 text-danger text-center">{{ $totalAppointments }}</h3>
                        <p class="text-fade text-center small"><small>Total Appointments</small></p>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         {{-- New user/doctor/appointment count ends--}}

         <div class="col-xxl-12">
            <div class="row">
               {{-- Total pataint starts--}}
               <div class="col-xxl-4 col-lg-6 col-12">
                  <div class="box pull-up">
                     <div class="box-body" style="background: url('{{asset('images/svg-icon/medical/1.png')}}');  background-position: center right; background-repeat: no-repeat; background-size: 80px; margin-right: 5px;">
                        <div class="d-flex align-items-center">
                           <div class="me-10 bg-danger w-60 h-60 rounded-circle text-center l-h-70">
                              <i class="fa-solid fa-hospital-user fs-24"></i>
                           </div>
                           <div>
                              <p class="text-fade mb-5">Total Patients</p>
                              <h1 class="my-0 fw-600">{{ $totalPatients }}</h1>     
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               {{-- Total pataint ends--}}
               {{-- Total doctors starts--}}
               <div class="col-xxl-4 col-lg-6 col-12">
                  <div class="box pull-up">
                     <div class="box-body" style="background: url('{{asset('images/svg-icon/medical/2.png')}}');  background-position: center right; background-repeat: no-repeat; background-size: 75px; margin-right: 5px;">
                        <div class="d-flex align-items-center">
                           <div class="me-10 bg-warning w-60 h-60 rounded-circle text-center l-h-70">
                              <i class="fa-solid fa-user-doctor fs-24"></i>
                           </div>
                           <div>
                              <p class="text-fade mb-5">Total Doctors</p>
                              <h1 class="my-0 fw-600">{{ $totalDoctors }}</h1>    
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               {{-- Total doctors ends--}}
               {{-- Total appointments starts--}}
               <div class="col-xxl-4 col-lg-6 col-12">
                  <div class="box pull-up">
                     <div class="box-body" style="background: url('{{asset('images/svg-icon/medical/4.png')}}');  background-position: center right; background-repeat: no-repeat; background-size: 175px; margin-right: 5px;">
                        <div class="d-flex align-items-center">
                           <div class="me-10 bg-primary w-60 h-60 rounded-circle text-center l-h-70">
                              <i class="fa-solid fa-hospital-user fs-24"></i>
                           </div>
                           <div>
                              <p class="text-fade mb-5">Total Products</p>
                              <h1 class="my-0 fw-600">{{ $totalProducts }}</h1>      
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               {{-- Total appointments ends--}}
            </div>
            {{-- <div class="row g-3 mb-4">
               <div class="col-md-3">
                  <div class="card shadow-sm p-3 text-center">
                        <h6>Total Products</h6>
                        <h3>{{ $totalProducts }}</h3>
                  </div>
               </div>
               <div class="col-md-3">
                  <div class="card shadow-sm p-3 text-center">
                        <h6>Total Categories</h6>
                        <h3>{{ $totalCategories }}</h3>
                  </div>
               </div>
               <div class="col-md-3">
                  <div class="card shadow-sm p-3 text-center">
                        <h6>New Categories</h6>
                        <h3>{{ $newCategories }}</h3>
                  </div>
               </div>
               <div class="col-md-3">
                  <div class="card shadow-sm p-3 text-center">
                        <h6>Trending Categories</h6>
                        <h3>{{ $totalTrendingCategories }}</h3>
                  </div>
               </div>
            </div> --}}
            {{-- <div class="row g-3 mb-4">
               <div class="col-md-6">
                  <div class="card shadow-sm p-3 text-center">
                        <h6>Total Cart Orders</h6>
                        <h3>{{ $cartOrders }}</h3>
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="card shadow-sm p-3 text-center">
                        <h6>Total Prescription Orders</h6>
                        <h3>{{ $prescriptionOrders }}</h3>
                  </div>
               </div>
            </div> --}}
         </div>
      </div>
      <div class="row">
         {{-- Activity graph starts --}}
         <div class="col-xxl-8 col-xl-7">
             <div class="box">
                 <div class="box-header b-0 pb-0 d-flex justify-content-between align-items-center">
                     <h4 class="box-title">Activity</h4>
                     <div class="dropdown">
                        <a class="px-10 pt-5 dropdown-toggle" href="#" id="current-range-text" data-bs-toggle="dropdown">Last 6 Months</a>
                        <div class="dropdown-menu dropdown-menu-end">
                           <a class="dropdown-item filter-chart" href="#" data-range="1">Last Month</a>
                           <a class="dropdown-item filter-chart" href="#" data-range="6">Last 6 Months</a>
                           <a class="dropdown-item filter-chart" href="#" data-range="12">Last Year</a>
                        </div>
                     </div>
                 </div>
                 <div class="box-body pt-0">
                     <div class="chart">
                         <div id="healing-hub-main-chart"></div>
                     </div>
                 </div>
             </div>
         </div>
         {{-- Activity graph ends --}}

         {{-- Doctor success states start --}}
         <div class="col-xxl-4 col-xl-5">
             <div class="box">
                 <div class="box-header b-0 d-flex justify-content-between align-items-center">
                     <h4 class="box-title">Success Stats</h4>
                 </div>
                 <div class="box-body pt-0 ">
                     <div class="table-responsive">
                         <table class="table no-border m-0 scrollable">
                             <tbody>
                                 @foreach($doctorStats as $doctor)
                                 <tr>
                                    <td>
                                       <div class="me-2">
                                          <img src="{{ $doctor->details->image ? asset('storage/'.$doctor->details->image) : asset('images/avatar/avatar-13.png') }}" class="avatar avatar-sm rounded10">
                                       </div>
                                       <div class="d-flex flex-column">
                                          <a class="text-dark fs-10">{{ $doctor->name }}</a>
                                       </div>
                                    </td>
                                    <td>
                                       <div class="progress" style="height:7px;width:200px">
                                          <div class="progress-bar bg-primary" style="width: {{ min($doctor->completed_appointments*10,100) }}%">
                                          </div>
                                       </div>
                                    </td>
                                    <td>{{ $doctor->completed_appointments }}</td>
                                 </tr>
                                 @endforeach
                             </tbody>
                         </table>
                     </div>
                 </div>
             </div>
         </div>
         {{-- Doctor success states ends --}}
      </div>
      <div class="row">
         <div class="col-xxl-4 col-xl-4">
            {{-- Doctor list starts --}}
            <div class="box">
               <div class="box-header b-0 d-flex justify-content-between align-items-center">
                  <h4 class="box-title">Doctor List</h4>
               </div>
               <div class="box-body pt-0 scrollable">
                  <div>
                     @foreach($doctorList as $doctor)
                        <div class="d-flex align-items-center mb-15">
                           <div class="me-15">
                              <img src="{{ $doctor->details->image ? asset('storage/'.$doctor->details->image) : asset('images/avatar/avatar-13.png') }}" class="avatar avatar-lg rounded10">
                           </div>
                           <div class="d-flex flex-column">
                              <a class="text-dark fs-14">{{ $doctor->name }}</a>
                              <span class="text-fade">{{ $doctor->specialization ?? 'Doctor' }}</span>
                           </div>
                        </div>
                     @endforeach
                  </div>
               </div>
            </div>
            {{-- Doctor list ends --}}
         </div>
         <div class="col-xxl-8 col-xl-8">
            <div class="box">
               <div class="box-header b-0 d-flex justify-content-between align-items-center">
                  <h4 class="box-title">Appointments</h4>
               </div>
               {{-- Appointments list starts --}}
               <div class="box-body pt-0">
                  <div class="table-responsive">
                     <table class="table m-0 scrollable">
                        <thead>
                           <tr>
                              <th>No.</th>
                              <th>Name</th>
                              <th>Date &amp; Time</th>
                              <th>Age</th>
                              <th>Gender</th>
                              <th>Appoint for</th>
                           </tr>
                        </thead>
                        <tbody>
                           @foreach($latestAppointments as $key => $appointment)
                           <tr>
                              <td>{{ $key+1 }}</td>
                              <td>{{ $appointment->user->name ?? '' }}</td>
                              <td>{{ \Carbon\Carbon::parse($appointment->booking_date)->format('d M h:i A') }}</td>
                              <td>{{ $appointment->user->age ?? '-' }}</td>
                              <td>{{ $appointment->user->gender ?? '-' }}</td>
                              <td>{{ $appointment->doctor->name ?? '' }}</td>
                           </tr>
                           @endforeach
                        </tbody>
                     </table>
                  </div>
               </div>
               {{-- Appointments list ends --}}
            </div>
         </div>
      </div>
   </section>
@endsection
@push('page_scripts')
<script src="{{ asset('assets/vendor_components/apexcharts-bundle/dist/apexcharts.js') }}"></script>
<script>
   $(document).ready(function() {
      var chartMonths = {!! json_encode(array_column($activityData,'month')) !!};
      var appointmentTotals = {!! json_encode(array_column($activityData,'total')) !!};
      var earningsTotals = {!! json_encode(array_column($activityData,'earnings')) !!};

      var chartOptions = {
         series: [{
            name: 'Appointments',
            type: 'area',
            data: appointmentTotals
         }, {
            name: 'Earnings (₹)',
            type: 'line',
            data: earningsTotals
         }],
         chart: {
            height: 350,
            type: 'line',
            toolbar: { show: false }
         },
         stroke: {
            curve: 'smooth',
            width: [2, 4]
         },
         fill: {
            type: ['gradient', 'solid'],
         },
         xaxis: {
            categories: chartMonths
         },
         yaxis: [
            { 
               title: { text: 'Appointments' } 
            },
            { 
               opposite: true, 
               title: { text: 'Earnings' },
               labels: {
                     formatter: function (value) {
                        return "₹" + value.toLocaleString('en-IN');
                     }
               }
            }
         ],
         tooltip: {
            y: {
               formatter: function (val) {
                     return "₹" + val.toLocaleString('en-IN');
               }
            }
         }
      };

      var myHealingChart = new ApexCharts(document.querySelector("#healing-hub-main-chart"), chartOptions);
      myHealingChart.render();

      $('.filter-chart').on('click', function(e) {
         e.preventDefault();
         
         var range = $(this).data('range');
         var rangeText = $(this).text();
      
         $('#current-range-text').text(rangeText);
         $.ajax({
            url: "{{ url('chart-data') }}/" + range,
            method: 'GET',
            success: function(response) {
               console.log(response);
               myHealingChart.updateOptions({
                  xaxis: {
                        categories: response.labels
                  },
                  series: [{
                        name: 'Appointments',
                        data: response.appointments
                  }, {
                        name: 'Earnings (₹)',
                        data: response.earnings
                  }]
               });
            }
         });
      });
   });
</script>
@endpush