<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>{{ config('app.name') }}</title>
		<!-- Style -->
		<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
		<link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
		<link rel="stylesheet" href="{{ asset('template/css/vendors_css.css') }}">
		<link rel="stylesheet" href="{{ asset('template/css/style.css') }}">
		<link rel="stylesheet" href="{{ asset('template/css/skin_color.css') }}">
		<link rel="stylesheet" href="{{ asset('template/css/custom.css') }}">
	</head>

	<body class="hold-transition light-skin sidebar-mini theme-primary fixed">

		<div class="wrapper">
			<div id="loader"></div>

			<header class="main-header">
				<div class="d-flex align-items-center logo-box justify-content-start">
					<!-- Logo -->
					<a href="{{route('home')}}" class="logo">
						<!-- logo-->
						<div class="logo-mini w-40">
							<span class="light-logo"><img src="{{ asset('images/logo-letter.png') }}" alt="logo"></span>
							<span class="dark-logo"><img src="{{ asset('images/logo-white-letter.png') }}" alt="logo"></span>
						</div>
						<div class="logo-lg">
							<span class="light-logo"><img src="{{ asset('images/logo-light-text.png') }}" alt="logo"></span>
							<span class="dark-logo"><img src="{{ asset('images/logo-text.png') }}" alt="logo"></span>
						</div>
					</a>
				</div>
				<!-- Header Navbar -->
				<nav class="navbar navbar-static-top">
	                <!-- Sidebar toggle button-->
	                <div class="app-menu">
	                    <ul class="header-megamenu nav">
	                        <li class="btn-group nav-item">
	                            <a href="#" class="waves-effect waves-light nav-link push-btn btn-primary-light" data-toggle="push-menu" role="button">
								<i data-feather="menu"></i>
								</a>
	                        </li>
	                    </ul>
	                </div>
	                <div class="navbar-custom-menu r-side">
	                    <ul class="nav navbar-nav">
	                        <li class="btn-group d-xl-inline-flex d-none">
	                            <a href="#" class="waves-effect waves-light nav-link btn-primary-light svg-bt-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="true">
								<img class="rounded" src="{{asset('images/avatar/avatar-13.png')}}" alt="">
							</a>
	                            <div class="dropdown-menu" data-bs-popper="none">
	                                <a class="dropdown-item my-5" href="{{ route('profile') }}"> Profile [{{ Auth::user()->name }}]</a>
	                                <a class="dropdown-item my-5" href="{{route('logout')}}"> Logout</a>
	                            </div>
	                        </li>
	                    </ul>
	                </div>
	            </nav>
			</header>
			@include('layouts.sidebar')

			<!-- Content Wrapper. Contains page content -->
				<div class="content-wrapper">
					<div class="container-full">
						<!-- Main content -->
						@yield('content')
						<!-- /.content -->
					</div>
				</div>
			<!-- /.content-wrapper -->
			<!-- Footer Section START -->
			<!-- Footer Section END -->

		</div>
		<!-- ./wrapper -->

		<!-- Vendor JS -->
        <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
		<script src="{{ asset('template/js/vendors.min.js') }}"></script>

		<script src="{{ asset('assets/icons/feather-icons/feather.min.js') }}"></script>
		<script src="{{ asset('template/js/pages/chat-popup.js') }}"></script>

		<!-- <script src="{{ asset('assets/vendor_components/echarts/dist/echarts-en.min.js') }}"></script> -->
		<script src="{{ asset('assets/vendor_components/jquery-knob/js/jquery.knob.js') }}"></script>
		<script src="{{ asset('assets/vendor_components/raphael/raphael.min.js') }}"></script>
		<script src="{{ asset('assets/vendor_components/morris.js/morris.min.js') }}"></script>
		<script src="{{ asset('assets/vendor_components/datatable/datatables.min.js') }}"></script>

		<!-- <script src="{{ asset('template/js/demo.js') }}"></script> -->
		<script src="{{ asset('template/js/template.js') }}"></script>
		<script src="{{ asset('assets/vendor_components/select2/dist/js/select2.full.js') }}"></script>
		<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
		<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
		<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
		<script type="text/javascript">
			var Toast = Swal.mixin({
		      toast: true,
		      position: 'top-end',
		      showConfirmButton: false,
		      timer: 5000
		    });
		    @if(Session::has('success'))
		        Toast.fire({
		            icon: 'success',
		            title: "{{ session('success') }}"
		        })
		    @endif
		    @if(Session::has('error'))
		        Toast.fire({
		            icon: 'error',
		            title: "{{ session('error') }}"
		        })
		    @endif
		    @error('error')
		        Toast.fire({
		            icon: 'error',
		            title: "{{ $message }}"
		        })
		    @endif
		    $('.select2').select2();
			let selectedFiles = [];
		    function renderPreviews() {
		       const $previewContainer = $('#productImagePreview');
		       $previewContainer.html('');
		       selectedFiles.forEach((file, index) => {
		           const imgURL = URL.createObjectURL(file);
		           const previewHTML = `
		                           <div class="image-wrapper" data-index="${index}">
		                           <span class="delete-icon itemImageDelete" data-url="">&times;</span>
		                           <img src="${imgURL}" alt="preview" >
		                           </div>
		                           `;
		           $previewContainer.append(previewHTML);
		       });
		       updateFileInput();
		   	}
		   	function updateFileInput() {
		       const dataTransfer = new DataTransfer();
		       selectedFiles.forEach(file => dataTransfer.items.add(file));
		       document.querySelector('.browse-multiple-btn').files = dataTransfer.files;
		   	}
		   	function removeFileFromInput(index) {
			    // Remove file from selectedFiles array by index
			    selectedFiles.splice(index, 1);
			    // Re-render previews with correct indices
			    renderPreviews();
			}
			$(document).on('change','.browse-btn', function(){
				$(this).parent().find('.preview').remove();
				console.log("jfhgjfd");
				// Loop through all selected files
			    // $.each(this.files, function(i, file) {
			        var output = document.createElement('img');
			        output.src = window.URL.createObjectURL(this.files[0]);
			        output.setAttribute('height', '100px');
			        output.setAttribute('width', '100px');
			        output.setAttribute('class', 'preview');
			        $(this).parent().append(output);
			    // });
		    });

			// Date picker filter final

			$(function () {

				const table = window.LaravelDataTables["dataTableBuilder"];

				function reloadTable() {
					if (table) {
						table.draw();
					}
				}

				const $dateInput = $('#datefilter');

				if ($dateInput.length) {

					$dateInput.daterangepicker({
						autoUpdateInput: true,
						locale: {
							cancelLabel: 'Clear',
							format: 'YYYY-MM-DD'
						}
					});

					$('#filter_btn_appointment').on('click', function(e) {
						e.preventDefault();

						var picker = $('#datefilter').data('daterangepicker');
						var dateValue = $('#datefilter').val();

						if (dateValue !== "") {
							var d1 = picker.startDate.format('YYYY-MM-DD');
							var d2 = picker.endDate.format('YYYY-MM-DD');

							$('#start_date_value').val(d1);
							$('#end_date_value').val(d2);
						} else {

							$('#start_date_value').val('');
							$('#end_date_value').val('');
						}

						reloadTable();
					});

				} else {
					console.warn('No Date picker found!');
				}

				$('#patient_filter').on('change', function () {
					let filters = {
						patient_id: $('#patient_filter').val(),
					};
					localStorage.setItem('product_filters', JSON.stringify(filters));
					reloadTable();
				});

				// $('#category_filter').on('change', function () {
				// 	let filters = {
				// 		category_id: $('#category_filter').val(),
				// 	};
				// 	localStorage.setItem('product_filters', JSON.stringify(filters));
				// 	reloadTable();
				// });
                $('#category_filter').on('change', function () {
                    let filters = {
                        patient_id: $('#patient_filter').val(),
                        category_id: $('#category_filter').val(),
                    };
                    localStorage.setItem('product_filters', JSON.stringify(filters));
                    reloadTable();
                });

				$('#clear_filter_appointment').on('click', function (e) {
					e.preventDefault();

					localStorage.removeItem('product_filters');
					$('#datefilter').val('').trigger('change');
					$('#start_date_value').val('');
					$('#end_date_value').val('');

					$('#patient_filter').val('').trigger('change');
					$('#category_filter').val('').trigger('change');
                    $('#status').val('').trigger('change');
					table.search('').draw();
					reloadTable();
				});

			});

			let dataTable;

			$(document).ready(function () {

				let filters = localStorage.getItem('product_filters');

				if (filters) {
					filters = JSON.parse(filters);

					if(filters.category_id){
						$('#category_filter').val(filters.category_id);
					}

					if(filters.patient_id){
						$('#patient_filter').val(filters.patient_id);
					}

				}

				dataTable = window.LaravelDataTables["dataTableBuilder"];

				dataTable.ajax.reload();
			});

			// date picker filter test end

			$(document).ready(function(){

				$('#category_filter').select2({
					placeholder:'Search Category',
					allowClear:true
				});
                $('#status_changes').select2({
					placeholder:'Search Status',
					allowClear:false
				});

				$('#patient_filter').select2({
					placeholder:'Search name',
					allowClear:true
				});

				$('#doctor_id_filter').select2({
					placeholder:'Search Doctor',
					allowClear:true
				});

				$('#appointment_type_filter').select2({
					placeholder:'Search type',
					allowClear:true
				});

				$('#appointment_status_filter').select2({
					placeholder:'Search status',
					allowClear:true
				});

				$('#filter_btn').click(function(){
					window.LaravelDataTables["dataTableBuilder"].draw();
				});

				$('#clear_filter').click(function(){
					$('#category_filter').val(null).trigger('change');
					window.LaravelDataTables["dataTableBuilder"].draw();
				});

				// $('#date_filter_btn').click(function () {

				// 	window.LaravelDataTables["dataTableBuilder"].draw();

				// });

				// $('#clear_date_filter').click(function () {

				// 	$('#start_date_filter').val('');
				// 	$('#end_date_filter').val('');
				// 	$('#start_time_filter').val('');
				// 	$('#end_time_filter').val('');

				// 	window.LaravelDataTables["dataTableBuilder"].draw();

				// });

				$('#filterBtn').click(function () {
					window.LaravelDataTables["dataTableBuilder"].draw();
				});

				$('#clearFilter').click(function () {

					$('#start_date').val('');
					$('#end_date').val('');

					window.LaravelDataTables["dataTableBuilder"].draw();

				});

				$('#searchBtn').click(function () {
					window.LaravelDataTables["dataTableBuilder"].draw();
				});

				$('#clearSearch').click(function () {
					$('#doctor_search').val('');
					window.LaravelDataTables["dataTableBuilder"].draw();
				});

			});

			$(document).ready(function(){
				$('#date_filter_btn').click(function () {
					window.LaravelDataTables["dataTableBuilder"].draw();
				});

				$('#clear_date_filter').click(function () {
					$('#start_date_filter').val('');
					$('#end_date_filter').val('');
					$('#appointment_type_filter').val('');
					$('#appointment_status_filter').val('');
					window.LaravelDataTables["dataTableBuilder"].draw();
				});

			});

			$(document).ready(function () {
				setTimeout(function () {
					$('#listTableDataTableId_filter').appendTo('#customSearch');
				}, 200);

				$('#apply_filter').click(function(){
					window.LaravelDataTables["dataTableBuilder"].ajax.reload();
				});

				$('#clear_filter').click(function(){
					$('#parent_filter').val('');
					window.LaravelDataTables["dataTableBuilder"].ajax.reload();
				});
			});


		    $(document).on('change','.browse-multiple-btn', function(){
		    	const newFiles = Array.from(this.files);
       			const totalFiles = selectedFiles.length + newFiles.length;
       			if (totalFiles > 5) {
		           const allowedFiles = newFiles.slice(0, 5 - selectedFiles.length);
		           selectedFiles = selectedFiles.concat(allowedFiles);
		           Toast.fire({
		               icon: 'error',
		               title: 'You can only upload a maximum of 5 images.'
		           });
		       	} else {
		           selectedFiles = selectedFiles.concat(newFiles);
		       	}
       			renderPreviews();
		    });
		   	$(document).on('click','.itemImageDelete',function(){
		   		var ele = $(this);
   				var url = $(this).data('url');
   				const index = $(this).closest('.image-wrapper').data('index');
   				if(url){
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
		                   	ele.attr('disabled', true);
		                   	$.ajax({
		                       	url: url,
		                       	type: "DELETE",
		                       	data: {
		                           '_token': "{{ csrf_token() }}"
		                       	},
		                       	success: function(response) {
		                           $('.loader').hide()
		                           if (response.status) {
		                               icon = 'success';
		                               Toast.fire({
		                                   icon: icon,
		                                   title: response.message
		                               });
		                               ele.parent().remove();
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
		                               title: 'Status:' + xhr.status + ' Message:' + xhr
		                                   .responseText
		                           });
		                       	}
		                   	});
		               	}
		           	});
   				}else{
   					ele.parent().remove();
   					removeFileFromInput(index);
   				}
		   	});
		    $(document).on('click','.delete_item_from_list',function(){
		        var delete_url = $(this).data('url');
		        var ele = $(this);
		        Swal.fire({
		            title: 'Do you want to delete?',
		            text: "You won't be able to revert all related records!",
		            showCancelButton: true,
		            confirmButtonText: 'Delete',
		            confirmButtonColor: '#3085d6',
		            cancelButtonColor: '#d33',
		        }).then((result) => {
		            if (result.isConfirmed) {
		                $.ajax({
		                    url:delete_url,
		                    method:"DELETE",
		                    data:{
		                        '_token': '{{ csrf_token() }}'
		                    },
		                    success:function(data){
		                        if(data.status){
		                            icon = 'success';
		                            ele.closest('tr').remove();
		                        }else{
		                            icon = 'error';
		                        }
		                        Toast.fire({
		                            icon: icon,
		                            title: data.message
		                        });
		                    }
		                });
		            }
		        });
		    });
		    $(document).on('click', '.listStatusUpdate', function () {
		        var status = $(this).data('status');
		        var itemid = $(this).data('itemid');
		        var statusUrl = $(this).data('url');
		        var ele = $(this);
		        $.ajax({
		            url:statusUrl,
		            method:"POST",
		            data:{
		                'status': status,
		                'id':itemid,
		                '_token': '{{ csrf_token() }}'
		            },
		            success:function(data){
		                var statusHtml = '';
		                if(status==0){
		                	statusHtml = `<button type="button" class="btn btn-sm btn-toggle listStatusUpdate" data-bs-toggle="button" aria-pressed="false" data-status="1" data-itemid="${itemid}" data-url="${statusUrl}">
                                <span class="handle"></span>
                               </button>`
		                }else{
		                   statusHtml = `<button type="button" class="btn btn-sm btn-toggle active listStatusUpdate" data-bs-toggle="button" aria-pressed="true" data-status="0" data-itemid="${itemid}" data-url="${statusUrl}">
                                <span class="handle"></span>`
		                }
		                ele.parent().html(statusHtml);
		                Toast.fire({
		                    icon: 'success',
		                    title: data.message
		                });
		            }
		        });
		   	});

			$(document).on('click','.viewAppointment',function(){

					var id = $(this).data('id');

					$.ajax({

						url: "appointments/"+id+"/show",
						type: "GET",

						success:function(response){

							if(response.status){

								$('#patient_name').text(response.data.user.name);
								$('#doctor_name').text(response.data.doctor.name);
								$('#booking_date').text(response.data.booking_date);
								$('#booking_time').text(response.data.booking_time);
								$('#appointment_type').text(response.data.appointment_type);
								$('#amount').text(response.data.amount);
								$('#status').text(response.data.status);

								$('#appointmentModal').modal('show');
							}

						}

					});

				});

				$('#filterBtn').click(function(){
					$('#dataTableBuilder').DataTable().ajax.reload();
				});

				$('#clearFilter').click(function(){
					$('#filter_type').val('').trigger('change');
					$('#start_date').val('');
					$('#end_date').val('');
					$('#dataTableBuilder').DataTable().ajax.reload();
				});
            //booking status
             $(document).on('click', '#updateStatusBtn', function () {
                var $btn = $(this);
                var url = $btn.data('url');
                var status = $('#statusSelect').val();

                if (!status) {
                    Toast.fire({ icon: 'warning', title: 'Please select a status first' });
                    return;
                }

                $btn.attr('disabled', true).text('Updating...');

                $.ajax({
                    url: url,
                    method: "POST",
                    data: {
                        'status': status,
                        '_token': '{{ csrf_token() }}'
                    },
                    success: function (data) {
                        $btn.attr('disabled', false).text('Update Status');

                        if (!data.success) {
                            Toast.fire({ icon: 'error', title: data.message });
                            return;
                        }

                        var colors = {completed: 'success', cancelled: 'danger' };
                        var color = colors[data.log.new_status] || 'secondary';

                        var logHtml = `
                            <div class="position-relative mb-3">
                                <div class="d-flex align-items-center p-2 border rounded shadow-sm bg-white" style="border-left: 4px solid var(--bs-${color}) !important;">
                                    <div class="flex-grow-1 ms-2">
                                        <div class="d-flex justify-content-between">
                                            <h6 class="mb-0 fw-bold">${data.log.user_name}</h6>
                                            <small class="text-muted">${data.log.changed_at}</small>
                                        </div>
                                        <div class="d-flex align-items-center mt-1">
                                            <span class="badge bg-${color}-light text-${color} border-${color} py-1 px-2" style="font-size: 0.7rem; border: 1px solid;">
                                                ${data.log.new_status.toUpperCase()}
                                            </span>
                                            <small class="text-muted ms-3">#${data.log.appointment_no}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>`;

                        $('#appointmentLogsBody').append(logHtml);

                        Toast.fire({ icon: 'success', title: data.message });
                    },
                    error: function (xhr) {
                        $btn.attr('disabled', false).text('Update Status');
                        Toast.fire({
                            icon: 'error',
                            title: xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Something went wrong'
                        });
                    }
                });
            });
		</script>
		@stack('page_scripts')
	</body>
</html>
