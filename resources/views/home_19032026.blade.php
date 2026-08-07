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
                  <p class="text-white mb-0">You can set your in app preferences here.</p>
               </div>
            </div>
         </div>
         <div class="col-xxl-4 col-xl-5">
            <div class="box">
               <div class="box-body">
                  <div class="row">
                     <div class="col-4">
                        <h1 class="fw-600 text-primary">30</h1>
                        <p class="text-fade mb-2">New Tasks</p>
                     </div>
                     <div class="col-4">
                        <h1 class="fw-600 text-info">50</h1>
                        <p class="text-fade mb-2">New Patients</p>
                     </div>
                     <div class="col-4">
                        <h1 class="fw-600 text-danger">25</h1>
                        <p class="text-fade mb-2">Notification</p>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="col-xxl-12">
            <div class="row">
               <div class="col-xxl-3 col-lg-6 col-12">
                  <div class="box pull-up">
                     <div class="box-body" style="background: url('{{asset('images/svg-icon/medical/1.png')}}');  background-position: center right; background-repeat: no-repeat; background-size: 80px; margin-right: 5px;">
                        <div class="d-flex align-items-center">
                           <div class="me-10 bg-danger w-60 h-60 rounded-circle text-center l-h-70">
                              <i class="fa-solid fa-hospital-user fs-24"></i>
                           </div>
                           <div>
                              <p class="text-fade mb-5">Total Patients</p>
                              <h1 class="my-0 fw-600">2,015</h1>     
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="col-xxl-3 col-lg-6 col-12">
                  <div class="box pull-up">
                     <div class="box-body" style="background: url('{{asset('images/svg-icon/medical/2.png')}}');  background-position: center right; background-repeat: no-repeat; background-size: 75px; margin-right: 5px;">
                        <div class="d-flex align-items-center">
                           <div class="me-10 bg-warning w-60 h-60 rounded-circle text-center l-h-70">
                              <i class="fa-solid fa-user-doctor fs-24"></i>
                           </div>
                           <div>
                              <p class="text-fade mb-5">Total Staffs</p>
                              <h1 class="my-0 fw-600">550</h1>    
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="col-xxl-3 col-lg-6 col-12">
                  <div class="box pull-up">
                     <div class="box-body" style="background: url('{{asset('images/svg-icon/medical/3.png')}}');  background-position: center right; background-repeat: no-repeat; background-size: 70px; margin-right: 5px;">
                        <div class="d-flex align-items-center">
                           <div class="me-10 bg-primary w-60 h-60 rounded-circle text-center l-h-70">
                              <i class="fa-solid fa-hospital-user fs-24"></i>
                           </div>
                           <div>
                              <p class="text-fade mb-5">Total Rooms</p>
                              <h1 class="my-0 fw-600">2000</h1>      
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="col-xxl-3 col-lg-6 col-12">
                  <div class="box pull-up">
                     <div class="box-body" style="background: url('{{asset('images/svg-icon/medical/icon-5.svg')}}');  background-position: center right; background-repeat: no-repeat; background-size: 80px; margin-right: 5px;">
                        <div class="d-flex align-items-center">
                           <div class="me-10 bg-info w-60 h-60 rounded-circle text-center l-h-70">
                              <i class="fa-solid fa-hospital-user fs-24"></i>
                           </div>
                           <div>
                              <p class="text-fade mb-5">Total Cars</p>
                              <h1 class="my-0 fw-600">50</h1>     
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class="row">
         <div class="col-xxl-8 col-xl-7">
             <div class="box">
                 <div class="box-header b-0 pb-0 d-flex justify-content-between align-items-center">
                     <h4 class="box-title">Activity</h4>
                     <div class="dropdown">
                         <a class="px-10 pt-5 dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false">Last 6 Month</a>
                         <div class="dropdown-menu dropdown-menu-end" style="">
                             <a class="dropdown-item" href="#">Last Month</a>
                             <a class="dropdown-item" href="#">Last Year</a>
                         </div>
                     </div>
                 </div>
                 <div class="box-body pt-0">
                     <div class="chart">
                         <div id="apexcharts-area"></div>
                     </div>
                 </div>
             </div>
         </div>
         <div class="col-xxl-4 col-xl-5">
             <div class="box">
                 <div class="box-header b-0 d-flex justify-content-between align-items-center">
                     <h4 class="box-title">Success Stats</h4>
                     <div class="dropdown">
                         <a class="btn btn-outline btn-sm dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false">May 2024</a>
                         <div class="dropdown-menu dropdown-menu-end" style="">
                             <a class="dropdown-item" href="#">May 2023</a>
                             <a class="dropdown-item" href="#">May 2022</a>
                         </div>
                     </div>
                 </div>
                 <div class="box-body pt-0">
                     <div class="table-responsive">
                         <table class="table no-border m-0">
                             <tbody>
                                 <tr>
                                     <td>Anesthtics</td>
                                     <td>
                                         <div class="progress m-0" role="progressbar" aria-label="Example 1px high" aria-valuenow="83" aria-valuemin="0" aria-valuemax="100" style="height: 7px; width: 200px;">
                                             <div class="progress-bar bg-primary" style="width: 83%"></div>
                                         </div>
                                     </td>
                                     <td>80%</td>
                                 </tr>
                                 <tr>
                                     <td>Gynecology</td>
                                     <td>
                                         <div class="progress m-0" role="progressbar" aria-label="Example 1px high" aria-valuenow="95" aria-valuemin="0" aria-valuemax="100" style="height: 7px; width: 200px;">
                                             <div class="progress-bar bg-primary" style="width: 95%"></div>
                                         </div>
                                     </td>
                                     <td>95%</td>
                                 </tr>
                                 <tr>
                                     <td>Nerology</td>
                                     <td>
                                         <div class="progress m-0" role="progressbar" aria-label="Example 1px high" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="height: 7px; width: 200px;">
                                             <div class="progress-bar bg-primary" style="width: 100%"></div>
                                         </div>
                                     </td>
                                     <td>100%</td>
                                 </tr>
                                 <tr>
                                     <td>Oncology</td>
                                     <td>
                                         <div class="progress m-0" role="progressbar" aria-label="Example 1px high" aria-valuenow="89" aria-valuemin="0" aria-valuemax="100" style="height: 7px; width: 200px;">
                                             <div class="progress-bar bg-primary" style="width: 89%"></div>
                                         </div>
                                     </td>
                                     <td>89%</td>
                                 </tr>
                                 <tr>
                                     <td>Orthopedics</td>
                                     <td>
                                         <div class="progress m-0" role="progressbar" aria-label="Example 1px high" aria-valuenow="97" aria-valuemin="0" aria-valuemax="100" style="height: 7px; width: 200px;">
                                             <div class="progress-bar bg-primary" style="width: 97%"></div>
                                         </div>
                                     </td>
                                     <td>97%</td>
                                 </tr>
                                 <tr>
                                     <td>Physiotherapy</td>
                                     <td>
                                         <div class="progress m-0" role="progressbar" aria-label="Example 1px high" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="height: 7px; width: 200px;">
                                             <div class="progress-bar bg-primary" style="width: 100%"></div>
                                         </div>
                                     </td>
                                     <td>100%</td>
                                 </tr>
                             </tbody>
                         </table>
                     </div>
                 </div>
             </div>
         </div>
      </div>
      <div class="row">
         <div class="col-xxl-4 col-xl-4">
            <div class="box">
               <div class="box-header b-0 d-flex justify-content-between align-items-center">
                  <h4 class="box-title">Doctor List</h4>
                  <div class="dropdown">
                     <a class="btn btn-outline btn-sm dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-chevron-right"></i></a>
                  </div>
               </div>
               <div class="box-body pt-0">
                  <div>
                     <div class="d-flex align-items-center mb-15">
                        <div class="me-15">
                           <img src="{{asset('images/avatar/avatar-13.png')}}" class="avatar avatar-lg rounded10 bg-primary-light" alt="">
                        </div>
                        <div class="d-flex flex-column flex-grow-1 fw-500">
                           <a href="#" class="text-dark hover-primary mb-1 fs-14">Dr. Jaylon Stanton</a>
                           <span class="text-fade">Dentist</span>
                        </div>
                        <div class="dropdown">
                           <a class="px-10 pt-5" href="#" data-bs-toggle="dropdown"><i class="ti-more-alt"></i></a>
                           <div class="dropdown-menu dropdown-menu-end">
                             <a class="dropdown-item flexbox" href="#">
                              <span>Inbox</span>
                              <span class="badge badge-pill badge-info">5</span>
                             </a>
                             <a class="dropdown-item" href="#">Sent</a>
                             <a class="dropdown-item" href="#">Spam</a>
                             <div class="dropdown-divider"></div>
                             <a class="dropdown-item flexbox" href="#">
                              <span>Draft</span>
                              <span class="badge badge-pill badge-default">1</span>
                             </a>
                           </div>
                        </div>
                     </div>
                     <div class="d-flex align-items-center mb-15">
                        <div class="me-15">
                           <img src="{{asset('images/avatar/avatar-13.png')}}" class="avatar avatar-lg rounded10 bg-primary-light" alt="">
                        </div>
                        <div class="d-flex flex-column flex-grow-1 fw-500">
                           <a href="#" class="text-dark hover-danger mb-1 fs-14">Dr. Carla Schleifer</a>
                           <span class="text-fade">Oculist</span>
                        </div>
                        <div class="dropdown">
                           <a class="px-10 pt-5" href="#" data-bs-toggle="dropdown"><i class="ti-more-alt"></i></a>
                           <div class="dropdown-menu dropdown-menu-end">
                             <a class="dropdown-item flexbox" href="#">
                              <span>Inbox</span>
                              <span class="badge badge-pill badge-info">5</span>
                             </a>
                             <a class="dropdown-item" href="#">Sent</a>
                             <a class="dropdown-item" href="#">Spam</a>
                             <div class="dropdown-divider"></div>
                             <a class="dropdown-item flexbox" href="#">
                              <span>Draft</span>
                              <span class="badge badge-pill badge-default">1</span>
                             </a>
                           </div>
                        </div>
                     </div>
                     <div class="d-flex align-items-center mb-15">
                        <div class="me-15">
                           <img src="{{asset('images/avatar/avatar-13.png')}}" class="avatar avatar-lg rounded10 bg-primary-light" alt="">
                        </div>
                        <div class="d-flex flex-column flex-grow-1 fw-500">
                           <a href="#" class="text-dark hover-success mb-1 fs-14">Dr. Hanna Geidt</a>
                           <span class="text-fade">Surgeon</span>
                        </div>
                        <div class="dropdown">
                           <a class="px-10 pt-5" href="#" data-bs-toggle="dropdown"><i class="ti-more-alt"></i></a>
                           <div class="dropdown-menu dropdown-menu-end">
                             <a class="dropdown-item flexbox" href="#">
                              <span>Inbox</span>
                              <span class="badge badge-pill badge-info">5</span>
                             </a>
                             <a class="dropdown-item" href="#">Sent</a>
                             <a class="dropdown-item" href="#">Spam</a>
                             <div class="dropdown-divider"></div>
                             <a class="dropdown-item flexbox" href="#">
                              <span>Draft</span>
                              <span class="badge badge-pill badge-default">1</span>
                             </a>
                           </div>
                        </div>
                     </div>
                     <div class="d-flex align-items-center mb-15">
                        <div class="me-15">
                           <img src="{{asset('images/avatar/avatar-13.png')}}" class="avatar avatar-lg rounded10 bg-primary-light" alt="">
                        </div>
                        <div class="d-flex flex-column flex-grow-1 fw-500">
                           <a href="#" class="text-dark hover-info mb-1 fs-14">Dr. Roger George</a>
                           <span class="text-fade">General Practitioners</span>
                        </div>
                        <div class="dropdown">
                           <a class="px-10 pt-5" href="#" data-bs-toggle="dropdown"><i class="ti-more-alt"></i></a>
                           <div class="dropdown-menu dropdown-menu-end">
                             <a class="dropdown-item flexbox" href="#">
                              <span>Inbox</span>
                              <span class="badge badge-pill badge-info">5</span>
                             </a>
                             <a class="dropdown-item" href="#">Sent</a>
                             <a class="dropdown-item" href="#">Spam</a>
                             <div class="dropdown-divider"></div>
                             <a class="dropdown-item flexbox" href="#">
                              <span>Draft</span>
                              <span class="badge badge-pill badge-default">1</span>
                             </a>
                           </div>
                        </div>
                     </div>
                     <div class="d-flex align-items-center">
                        <div class="me-15">
                           <img src="{{asset('images/avatar/avatar-13.png')}}" class="avatar avatar-lg rounded10 bg-primary-light" alt="">
                        </div>
                        <div class="d-flex flex-column flex-grow-1 fw-500">
                           <a href="#" class="text-dark hover-warning mb-1 fs-14">Dr. Natalie doe</a>
                           <span class="text-fade">Physician</span>
                        </div>
                        <div class="dropdown">
                           <a class="px-10 pt-5" href="#" data-bs-toggle="dropdown"><i class="ti-more-alt"></i></a>
                           <div class="dropdown-menu dropdown-menu-end">
                             <a class="dropdown-item flexbox" href="#">
                              <span>Inbox</span>
                              <span class="badge badge-pill badge-info">5</span>
                             </a>
                             <a class="dropdown-item" href="#">Sent</a>
                             <a class="dropdown-item" href="#">Spam</a>
                             <div class="dropdown-divider"></div>
                             <a class="dropdown-item flexbox" href="#">
                              <span>Draft</span>
                              <span class="badge badge-pill badge-default">1</span>
                             </a>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="col-xxl-8 col-xl-8">
            <div class="box">
               <div class="box-header b-0 d-flex justify-content-between align-items-center">
                  <h4 class="box-title">Online Appointment </h4>
                  <div class="dropdown">
                     <a class="px-10 pt-5" href="#">View All</a>
                  </div>
               </div>
               <div class="box-body pt-0">
                  <div class="table-responsive">
                     <table class="table m-0">
                        <thead>
                           <tr><th>No.</th>
                           <th>Name</th>
                           <th>Date &amp; Time</th>
                           <th>Age</th>
                           <th>Gender</th>
                           <th>Appoint for</th>
                           <th class="text-end">Action</th>
                        </tr></thead>
                        <tbody>

                           <tr>
                              <td>01</td>
                              <td>Natiya</td>
                              <td>20 May 5:30pm</td>
                              <td>50</td>
                              <td>Female</td>
                              <td>Dr. Lee</td>
                              <td class="text-end">
                                 <i class="fa-regular fa-pen-to-square text-danger fs-18"> </i>
                                  <i class="fa-regular fa-trash-can fs-18"></i>
                              </td>
                           </tr>
                           <tr>
                              <td>02</td>
                              <td>Vision</td>
                              <td>20 May 6:30pm</td>
                              <td>70</td>
                              <td>Male</td>
                              <td>Dr. Gregory</td>
                              <td class="text-end">
                                 <i class="fa-regular fa-pen-to-square text-danger fs-18"> </i>
                                  <i class="fa-regular fa-trash-can fs-18"></i>
                              </td>
                           </tr>
                           <tr>
                              <td>03</td>
                              <td>Miranda</td>
                              <td>20 May 7:00pm</td>
                              <td>54</td>
                              <td>Male</td>
                              <td>Dr. Bernard</td>
                              <td class="text-end">
                                 <i class="fa-regular fa-pen-to-square text-danger fs-18"> </i>
                                  <i class="fa-regular fa-trash-can fs-18"></i>
                              </td>
                           </tr>
                           <tr>
                              <td>04</td>
                              <td>Olive</td>
                              <td>20 May 8:00pm</td>
                              <td>45</td>
                              <td>Female</td>
                              <td>Dr. Mitchell</td>
                              <td class="text-end">
                                 <i class="fa-regular fa-pen-to-square text-danger fs-18"> </i>
                                  <i class="fa-regular fa-trash-can fs-18"></i>
                              </td>
                           </tr>
                           <tr>
                              <td>05</td>
                              <td>Mishel</td>
                              <td>20 May 8:30pm</td>
                              <td>40</td>
                              <td>Male</td>
                              <td>Dr. Randall</td>
                              <td class="text-end">
                                 <i class="fa-regular fa-pen-to-square text-danger fs-18"> </i>
                                  <i class="fa-regular fa-trash-can fs-18"></i>
                              </td>
                           </tr>
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>
@endsection
@push('page_scripts')
<script src="{{ asset('assets/vendor_components/apexcharts-bundle/dist/apexcharts.js') }}"></script>
<script src="{{ asset('template/js/pages/dashboard.js') }}"></script>
@endpush