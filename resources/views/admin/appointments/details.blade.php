@extends('layouts.app')
@section('page_title')
   Booked appoinment Details
@endsection
@section('content')
   <div class="content-header">
      <div class="d-flex align-items-center">
         <div class="me-auto healbreadcrumb">
            <h4 class="page-title">Booked appoinment Details</h4>
            <div class="d-inline-block align-items-center">
               <nav>
                  <ol class="breadcrumb">
                     <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="mdi mdi-home-outline"></i></a></li>
                     <li class="breadcrumb-item"><a href="{{route('appointments.index')}}">
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
         <div class="col-xl-8 col-lg-7 col-12">
            <div class="box">
            <div class="bg-banner"></div>
            <div class="box-body position-relative">
                <div class="d-md-flex align-items-end justify-content-between">

                    <!-- LEFT: avatar + patient info -->
                    <div class="d-md-flex align-items-end">
                        <div class="text-center text-md-start me-md-4">
                        <img src="{{ ($patient->details->image !== null) ? asset('storage/'.$patient->details->image) : 'https://i.pravatar.cc/150?u=patient' }}"
                                class="patient-profile-img shadow rounded-circle"
                                style="width:110px; height:110px; object-fit:cover;"
                                alt="Patient">
                        </div>
                        <div class="mt-3 mt-md-0">
                        <h3 class="fw-bold mb-1">{{ $patient->name }}</h3>
                        <p class="text-muted mb-2">Appoinment ID: {{ $appointment->appointment_no }}</p>
                        <p class="mb-0 text-primary d-flex align-items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="custom-svg me-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:18px; height:18px; flex-shrink:0;">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                                <circle cx="16" cy="16" r="4"></circle>
                                <polyline points="16 14 16 16 17 17"></polyline>
                            </svg>
                            Appointment on {{ \Carbon\Carbon::parse($appointment->booking_date)->format('d M Y') }}, &nbsp;&nbsp;<i class="fa-regular fa-clock"></i> {{ \Carbon\Carbon::parse($appointment->booking_time)->format('h:i A') }}
                        </p>
                        <p class="mt-2 text-primary d-flex justify-content-between">
                            <span>@if($appointment->appointment_type === 'audio') <i class="fa fa-phone"></i> @else <i class="fa-solid fa-video"></i> @endif {{ ucfirst($appointment->appointment_type) }} appointment</span>
                            <span class="ml-2"><i class="fa-solid fa-money-bill"></i> ${{ $appointment->amount }}</span>
                        </p>
                        </div>
                    </div>

                    <!-- RIGHT: meeting link + status update -->
                    <div class="text-md-end mt-3 mt-md-0">
                        <h6 class="fw-bold text-muted mb-2">Meeting</h6>
                        @if($appointment->appointment_type === 'video' && $appointment->meeting_link)
                            <div class="mb-2">
                                <a href="{{ $appointment->meeting_link }}" target="_blank" class="btn btn-sm btn-success">
                                    </i> Join Meeting
                                </a>
                            </div>
                        @endif
                        <h6 class="fw-bold text-muted mb-2">Status</h6>
                        @if($appointment->status === 'upcoming')
                            <div class="d-flex align-items-center gap-2 justify-content-md-end">
                                @csrf
                                <select name="status" id="statusSelect" class="form-select form-select-sm" style="width:auto;">
                                    <option value="" selected disabled>Upcoming</option>
                                    <option value="completed">Completed</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                                <button type="button" id="updateStatusBtn"
                                        data-url="{{ route('appointments.updateStatus', $appointment->id) }}"
                                        class="btn btn-sm btn-primary">
                                    Update Status
                                </button>
                            </div>
                        @else
                            @php
                                $statusColor = $appointment->status === 'completed' ? 'success' : ($appointment->status === 'cancelled' ? 'danger' : 'secondary');
                            @endphp
                            <span class="badge bg-{{ $statusColor }}-light text-{{ $statusColor }} border-{{ $statusColor }} py-1 px-2" style="font-size: 0.75rem; border: 1px solid;">
                                {{ strtoupper($appointment->status) }}
                            </span>
                        @endif
                    </div>

                </div>
            </div>
            <hr class="mx-4 my-0">
            </div>

            <div class="row">
               <div class="col-md-12">
                  <div class="row">
                     <div class="col-md-6">
                        <div class="box">
                           <div class="box-body">
                              <h5 class="fw-bold mb-4">Contact Information</h5>
                              <div class="mb-3">
                                 <small class="text-muted d-block">Email</small>
                                 <strong>{{ $patient->email }}</strong>
                              </div>
                              <div class="mb-3">
                                 <small class="text-muted d-block">Phone</small>
                                 <strong>{{ $patient->mobile }}</strong>
                              </div>
                              <div class="mb-3">
                                 <small class="text-muted d-block">Address</small>
                                 {{-- <strong>{{ $patient->mobile }}</strong> --}}
                                 <strong>
                                        @if($address)
                                        @if($address->building)
                                            {{ $address->building }},
                                        @endif
                                        {{ $address->address }}, {{ $address->city }}, {{ $address->state }} - {{ $address->pincode }}, {{ $address->country }}
                                        @else
                                        N/A
                                        @endif
                                </strong>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="box">
                           <div class="box-body">
                              <div class="d-flex justify-content-between align-items-center mb-4">
                                 <h5 class="fw-bold mb-0">Current Vitals</h5>
                                 <svg xmlns="http://www.w3.org/2000/svg" class="text-success" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4.5 3v2a3.5 3.5 0 0 0 7 0V3"></path><path d="M11.5 5V9a4 4 0 0 0 4 4h1"></path><circle cx="19" cy="13" r="3"></circle><line x1="8" y1="12" x2="8" y2="21"></line><path d="M11 14.5a2.5 2.5 0 0 0-2.5-2.5H7.5a2.5 2.5 0 0 0 0 5h1a2.5 2.5 0 0 1 0 5H7a2.5 2.5 0 0 1-2.5-2.5"></path></svg>
                              </div>
                              <div class="row g-3 text-center mb-4">
                                 <div class="col-4">
                                    <img src="https://cdn-icons-png.flaticon.com/512/3034/3034882.png"
                                         class="vitals-icon mb-2"
                                         style="width:36px; height:36px; object-fit:contain;"
                                         alt="Weight">
                                    <small class="text-muted d-block">Age</small>
                                    <strong>{{$patient->details->age}}</strong>
                                 </div>
                                 <div class="col-4 border-start border-end">
                                    <img src="https://cdn-icons-png.flaticon.com/128/865/865969.png"
                                         class="vitals-icon mb-2"
                                         style="width:36px; height:36px; object-fit:contain;"
                                         alt="Heart Rate">
                                    <small class="text-muted d-block">Heart Rate</small>
                                    <strong>{{$patient->details->heart_rate}}bpm</strong>
                                 </div>
                                 <div class="col-4">
                                    <img src="https://cdn-icons-png.flaticon.com/128/4813/4813807.png"
                                         class="vitals-icon mb-2"
                                         style="width:36px; height:36px; object-fit:contain;"
                                         alt="BMI">
                                    <small class="text-muted d-block">Weight</small>
                                    <strong>{{$patient->details->weight}}</strong>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  @if($appointment->status == 'completed' && count($prescriptions))
                     <div class="box mt-4">
                        <div class="box-body">
                           <h5 class="fw-bold mb-3">Prescription</h5>
                           <div class="row">
                              @foreach($prescriptions as $prescription)
                              <div class="col-md-4 mb-3">
                                 <img src="{{ asset('storage/'.$prescription->image) }}"
                                      class="img-fluid rounded shadow"
                                      style="width:100%; height:160px; object-fit:cover;">
                              </div>
                              @endforeach
                           </div>
                        </div>
                     </div>
                  @endif
               </div>
            </div>
         </div>
         <div class="col-xl-4 col-lg-5 col-12">
            <div class="box">
               <div class="box-body">
                  <h5 class="fw-bold mb-4">Assigned Doctor</h5>
                  <div class="d-flex align-items-center">
                        <img src="{{ ($doctor->details->image !== null) ? asset('storage/'.$doctor->details->image) : 'https://i.pravatar.cc/150?u=doctor' }}"
                             class="rounded shadow-sm me-3"
                             style="width:80px; height:80px; object-fit:cover;"
                             alt="Doctor">
                        <div>
                           <h5 class="mb-1">Dr. {{ $doctor->name }}</h5>
                           <p class="text-muted small mb-1">{{ $doctor->details->specialist ?? 'Doctor' }}</p>
                        </div>
                  </div>
                  {{-- <div class="mt-4 d-flex gap-2">
                        <a href="" class="btn btn-sm btn-primary flex-grow-1">View Profile</a>
                  </div> --}}
               </div>
            </div>
            <div class="box">
               <div class="box-body">
                  <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0">Appointment Logs</h5>
                        <svg xmlns="http://www.w3.org/2000/svg" class="text-muted" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                           <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                           <polyline points="14 2 14 8 20 8"></polyline>
                           <circle cx="16" cy="16" r="4"></circle>
                           <polyline points="16 14 16 16 17 17"></polyline>
                        </svg>
                  </div>

                  @foreach($logs as $log)
                        @php
                           $statusColor = 'secondary';
                           if($log->new_status == 'upcoming') $statusColor = 'warning';
                           elseif($log->new_status == 'completed') $statusColor = 'success';
                           elseif($log->new_status == 'cancelled') $statusColor = 'danger';
                           elseif($log->new_status == 'in_progress') $statusColor = 'primary';
                        @endphp

                        <div class="position-relative mb-3">
                           @if($loop->iteration == 2 && count($logs) >= 2)
                              <div class="d-flex align-items-center mb-1">
                                    <span class="badge badge-dot bg-success me-2"></span>
                                    <small class="text-success fw-bold text-uppercase">Latest Update</small>
                              </div>
                           @endif

                           <div class="d-flex align-items-center p-2 border rounded shadow-sm bg-white" style="border-left: 4px solid var(--bs-{{ $statusColor }}) !important;">
                              <div class="flex-grow-1 ms-2">
                                    <div class="d-flex justify-content-between">
                                       <h6 class="mb-0 fw-bold">{{ $log->user->name ?? 'System' }}</h6>
                                       <small class="text-muted">{{ \Carbon\Carbon::parse($log->changed_at)->format('d M, h:i A') }}</small>
                                    </div>
                                    <div class="d-flex align-items-center mt-1">
                                       <span class="badge bg-{{ $statusColor }}-light text-{{ $statusColor }} border-{{ $statusColor }} py-1 px-2" style="font-size: 0.7rem; border: 1px solid;">
                                          {{ strtoupper($log->new_status) }}
                                       </span>
                                       <small class="text-muted ms-3">#{{ $appointment->appointment_no }}</small>
                                    </div>
                              </div>
                           </div>
                        </div>
                  @endforeach
               </div>
            </div>
            {{-- <div class="box">
               <div class="box-header bg-success">
                  <h4 class="box-title">Payment History</h4>
               </div>
               <div class="box-body">
                  <div class="table-responsive">
                     <table class="table table-bordered">
                        <thead>
                           <tr>
                              <th>#</th>
                              <th>Transaction ID</th>
                              <th>Payment Method</th>
                              <th>Amount</th>
                              <th>Status</th>
                              <th>Date</th>
                           </tr>
                        </thead>
                        <tbody>
                           @forelse($payments as $payment)
                           <tr>
                              <td>{{ $loop->iteration }}</td>
                              <td>{{ $payment->transaction_id ?? '-' }}</td>
                              <td>{{ $payment->method->display_name ?? '-' }}</td>
                              <td>₹ {{ $payment->amount }}</td>
                              <td>
                                 @if($payment->status=='paid')
                                 <span class="badge bg-success">Paid</span>
                                 @endif
                                 @if($payment->status=='pending')
                                 <span class="badge bg-warning">Pending</span>
                                 @endif
                                 @if($payment->status=='failed')
                                 <span class="badge bg-danger">Failed</span>
                                 @endif
                              </td>
                              <td>{{ $payment->paid_at }}</td>
                           </tr>
                           @empty
                           <tr>
                              <td colspan="6">No data found</td>
                           </tr>
                           @endforelse
                        </tbody>
                     </table>
                  </div>
               </div>
            </div> --}}
            <div class="box">
               <div class="box-body">
                  <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0">Payment History</h5>
                        <svg xmlns="http://www.w3.org/2000/svg" class="text-muted" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                           <path d="M12 2v20"></path>
                           <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                        </svg>
                  </div>

                  @forelse($payments as $payment)
                        @php
                           $statusColor = 'secondary';
                           if($payment->status == 'paid') $statusColor = 'success';
                           elseif($payment->status == 'pending') $statusColor = 'warning';
                           elseif($payment->status == 'failed') $statusColor = 'danger';
                        @endphp

                        <div class="position-relative mb-2">
                           <div class="d-flex align-items-center p-2 border rounded shadow-sm bg-white" style="border-left: 4px solid var(--bs-{{ $statusColor }}) !important;">
                              <div class="flex-grow-1 ms-2">
                                    <div class="d-flex justify-content-between align-items-start">
                                       <div>
                                          <h6 class="mb-0 fw-bold">₹ {{ number_format($payment->amount, 2) }}</h6>
                                          <small class="text-muted" style="font-size: 0.75rem;">{{ $payment->transaction_id ?? 'No Trans ID' }}</small>
                                       </div>
                                       <div class="text-end">
                                          <span class="badge bg-{{ $statusColor }}-light text-{{ $statusColor }} py-1 px-2 mb-1" style="font-size: 0.65rem; border: 1px solid;">
                                                {{ strtoupper($payment->status) }}
                                          </span>
                                          <div class="text-muted" style="font-size: 0.7rem;">
                                                {{ \Carbon\Carbon::parse($payment->paid_at)->format('d M, y') }}
                                          </div>
                                       </div>
                                    </div>
                                    <div class="d-flex justify-content-between mt-1">
                                       <small class="text-dark fw-medium" style="font-size: 0.75rem;">
                                          <i class="fa fa-credit-card-alt me-1 text-muted"></i> {{ $payment->method->display_name ?? 'Unknown' }}
                                       </small>
                                       <small class="text-muted" style="font-size: 0.7rem;">#{{ $loop->iteration }}</small>
                                    </div>
                              </div>
                           </div>
                        </div>
                  @empty
                        <div class="text-center p-4 border rounded bg-light">
                           <p class="mb-0 text-muted">No payment data found</p>
                        </div>
                  @endforelse
               </div>
            </div>
         </div>
      </div>
   </section>
@endsection
