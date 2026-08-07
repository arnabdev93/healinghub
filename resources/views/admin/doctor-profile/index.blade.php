@extends('layouts.app')
@section('page_title')

@endsection
@section('content')
    <div class="content-header">
        <div class="d-flex align-items-center">
            <div class="me-auto healbreadcrumb">
                <h4 class="page-title">Doctor profile</h4>
                <div class="d-inline-block align-items-center">
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home') }}"><i class="mdi mdi-home-outline"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Doctor profile</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="row">
            {{-- LEFT COLUMN --}}
            <div class="col-xl-8 col-12">

                {{-- Doctor info card --}}
                <div class="box">
                    <div class="box-body text-end min-h-150"
                         style="background-image:url({{ asset('images/gallery/landscape14.jpg') }}); background-repeat: no-repeat; background-position: center; background-size: cover;">
                        <div class="bg-success rounded10 p-15 fs-18 d-inline">
                            <i class="fa fa-stethoscope"></i> {{ $doctor->details->specialist }}
                        </div>
                    </div>

                    <div class="box-body wed-up position-relative">
                        <div class="d-md-flex align-items-end">
                            <img src="{{ $doctor->details->image ? asset('storage/'.$doctor->details->image) : asset('images/avatar/avatar-1.png') }}"
                                 class="bg-success-light rounded10 me-20"
                                 style="width:130px; height:130px; object-fit:cover;"
                                 alt="">
                            <div>
                                <h4 class="mb-1">Dr. {{ $doctor->name }}</h4>
                                <p class="mb-0"><i class="fa fa-clock-o"></i> Join on {{ date('d M Y', strtotime($doctor->created_at)) }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="box-body">
                        <h4>Biography</h4>
                        <p>{{ $doctor->details->about ?? 'Doctor biography not available.' }}</p>
                    </div>
                </div>

                {{-- DataTable card --}}
                <div class="box">
                    <div class="box-header">
                        <h4 class="box-title">Appointment history</h4>
                    </div>
                    <div class="box-body">
                        <div class="row mb-3 align-items-end">
                            <div class="col-md-3">
                                <label class="form-label"><strong>Filter by Date</strong></label>
                                <input type="date" id="booking_date_filter" class="form-control">
                            </div>
                            <div class="col-md-auto">
                                <button class="btn btn-primary me-2" id="date_filter_btn">Search</button>
                                <button class="btn btn-secondary" id="clear_date_filter">Clear</button>
                            </div>
                        </div>

                        <div class="table-responsive rounded card-table">
                            {!! $dataTable->table(['class' => 'text-fade table table-bordered'], true) !!}
                        </div>
                    </div>
                </div>
            </div>

            {{-- RIGHT COLUMN --}}
            <div class="col-xl-4 col-12">

                {{-- Upcoming appointments --}}
                <div class="box">
                    <div class="box-header bg-warning">
                        <h4 class="box-title">Upcoming appointments</h4>
                    </div>
                    <div class="box-body">
                        <div id="paginator1"></div>
                    </div>
                    <div class="box-body" style="max-height: 280px; overflow-y: auto;">
                        <div class="inner-user-div4">
                            @forelse ($upcomingAppointments as $appointment)
                                <div>
                                    <div class="d-flex align-items-center mb-10">
                                        <div class="me-15">
                                            <img src="{{ $appointment->user->details->image ? asset('storage/'.$appointment->user->details->image) : asset('images/avatar/avatar-1.png') }}"
                                                 class="avatar avatar-lg rounded10 bg-primary-light" alt="">
                                        </div>
                                        <div class="d-flex flex-column flex-grow-1 fw-500">
                                            <p class="hover-primary text-fade mb-1 fs-14">{{ $appointment->user->name }}</p>
                                            <span class="text-dark fs-16">{{ $appointment->user->name }}</span>
                                        </div>
                                        <div>
                                            <a href="#" class="waves-effect waves-circle btn btn-circle btn-primary-light btn-sm">
                                                @if ($appointment->appointment_type === 'audio')
                                                    <i class="fa fa-phone"></i>
                                                @else
                                                    <i class="fa-solid fa-video"></i>
                                                @endif
                                            </a>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-end mb-15 py-10 bb-dashed border-bottom">
                                        <p class="mb-0 text-muted">
                                            <i class="fa-regular fa-clock"></i> {{ $appointment->booking_time }}
                                            <span class="mx-20">$ {{ $appointment->amount }}</span>
                                            <span class="mx-20"><i class="fa-regular fa-calendar"></i> {{ $appointment->booking_date }}</span>
                                        </p>
                                    </div>
                                </div>
                            @empty
                                <p class="text-muted text-center mb-0">No upcoming appointments.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Completed appointments --}}
                <div class="box">
                    <div class="box-header bg-success">
                        <h4 class="box-title">Completed appointments</h4>
                    </div>
                    <div class="box-body">
                        <div id="paginator2"></div>
                    </div>
                    <div class="box-body" style="max-height: 280px; overflow-y: auto;">
                        <div class="inner-user-div4">
                            @forelse ($completedAppointments as $appointment)
                                <div>
                                    <div class="d-flex align-items-center mb-10">
                                        <div class="me-15">
                                            <img src="{{ $appointment->user->details->image ? asset('storage/'.$appointment->user->details->image) : asset('images/avatar/avatar-1.png') }}"
                                                 class="avatar avatar-lg rounded10 bg-primary-light" alt="">
                                        </div>
                                        <div class="d-flex flex-column flex-grow-1 fw-500">
                                            <p class="hover-primary text-fade mb-1 fs-14">{{ $appointment->user->name }}</p>
                                            <span class="text-dark fs-16">{{ $appointment->user->name }}</span>
                                        </div>
                                        <div>
                                            <a href="#" class="waves-effect waves-circle btn btn-circle btn-primary-light btn-sm">
                                                <i class="fa fa-phone"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-end mb-15 py-10 bb-dashed border-bottom">
                                        <p class="mb-0 text-muted">
                                            @if ($appointment->appointment_type === 'audio')
                                                <i class="fa fa-phone"></i>
                                            @else
                                                <i class="fa-solid fa-video"></i>
                                            @endif
                                            {{ ucfirst($appointment->appointment_type) }}
                                            <span class="mx-20">$ {{ $appointment->amount }}</span>
                                            <span class="mx-20"><i class="fa-regular fa-calendar"></i> {{ $appointment->booking_date }}</span>
                                        </p>
                                    </div>
                                </div>
                            @empty
                                <p class="text-muted text-center mb-0">No completed appointments.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Cancelled appointments --}}
                <div class="box">
                    <div class="box-header bg-danger">
                        <h4 class="box-title">Cancled appointments</h4>
                    </div>
                    <div class="box-body">
                        <div id="paginator3"></div>
                    </div>
                    <div class="box-body" style="max-height: 280px; overflow-y: auto;">
                        <div class="inner-user-div4">
                            @forelse ($cancelledAppointments as $appointment)
                                <div>
                                    <div class="d-flex align-items-center mb-10">
                                        <div class="me-15">
                                            <img src="{{ $appointment->user->details->image_path ? asset('storage/'.$appointment->user->details->image_path) : asset('images/avatar/avatar-1.png') }}"
                                                 class="avatar avatar-lg rounded10 bg-primary-light" alt="">
                                        </div>
                                        <div class="d-flex flex-column flex-grow-1 fw-500">
                                            <p class="hover-primary text-fade mb-1 fs-14">{{ $appointment->user->name }}</p>
                                            <span class="text-dark fs-16">{{ $appointment->user->name }}</span>
                                        </div>
                                        <div>
                                            <a href="#" class="waves-effect waves-circle btn btn-circle btn-primary-light btn-sm">
                                                <i class="fa fa-phone"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-end mb-15 py-10 bb-dashed border-bottom">
                                        <p class="mb-0 text-muted">
                                            <i class="fa-regular fa-clock"></i> {{ $appointment->booking_time }}
                                            <span class="mx-20">$ {{ $appointment->amount }}</span>
                                            <span class="mx-20"><i class="fa-regular fa-calendar"></i> {{ $appointment->booking_date }}</span>
                                        </p>
                                    </div>
                                </div>
                            @empty
                                <p class="text-muted text-center mb-0">No cancelled appointments.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection

@push('page_scripts')
    {!! $dataTable->scripts() !!}
    <script>
    $(function () {
        var table = $('.dataTable').DataTable();

        $('#date_filter_btn').on('click', function () {
            table.draw();
        });

        $('#clear_date_filter').on('click', function () {
            $('#booking_date_filter').val('');
            table.draw();
        });
    });
</script>
@endpush
