<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ config('app.name') }}</title>
	<!-- Style-->  
	<link rel="stylesheet" href="{{ asset('template/css/style.css') }}">
	<link rel="stylesheet" href="{{ asset('template/css/skin_color.css') }}">
	<link rel="stylesheet" href="{{ asset('template/css/vendors_css.css') }}">
	<link rel="stylesheet" href="{{ asset('template/css/custom.css') }}">
</head>
	
<body class="hold-transition theme-primary bg-img" style="background-image: url('{{ asset('images/bg-16.jpg') }}')">
	
	<div class="container h-p100">
		<div class="row align-items-center justify-content-md-center h-p100">	
			
			<div class="col-12">
				<div class="row justify-content-center g-0">
					<div class="col-lg-5 col-md-5 col-12">
						<div class="bg-white rounded10 shadow-lg">
							<div class="content-top-agile p-20 pb-0">
								<h2 class="text-primary fw-600">Let's Get Started</h2>
								<p class="mb-0 text-fade">Register a new membership</p>							
							</div>
							<div class="p-40">
								<form action="{{ route('register.store') }}" method="post">
									@csrf
									<div class="form-group @error('name') error @endif">
										<div class="input-group mb-3">
											<span class="input-group-text bg-transparent"><i class="text-fade ti-user"></i></span>
											<input type="text" name="name" value="{{old('name')}}" class="form-control ps-15 bg-transparent" placeholder="Full Name" @error('name') aria-invalid="true" @endif>
										</div>
										@error('name')
											<div class="help-block"><ul role="alert"><li>{{$message}}</li></ul></div>
										@endif
									</div>
									<div class="form-group @error('email') error @endif">
										<div class="input-group mb-3">
											<span class="input-group-text bg-transparent"><i class="text-fade ti-email"></i></span>
											<input type="email" name="email" value="{{old('email')}}" class="form-control ps-15 bg-transparent" placeholder="Email" @error('email') aria-invalid="true" @endif>
										</div>
										@error('email')
											<div class="help-block"><ul role="alert"><li>{{$message}}</li></ul></div>
										@endif
									</div>
									<div class="form-group @error('password') error @endif">
										<div class="input-group mb-3">
											<span class="input-group-text bg-transparent"><i class="text-fade ti-lock"></i></span>
											<input type="password" name="password" class="form-control ps-15 bg-transparent" placeholder="Password" @error('password') aria-invalid="true" @endif>
										</div>
										@error('password')
											<div class="help-block"><ul role="alert"><li>{{$message}}</li></ul></div>
										@endif
									</div>
									<div class="form-group @error('password_confirmation') error @endif">
										<div class="input-group mb-3">
											<span class="input-group-text bg-transparent"><i class="text-fade ti-lock"></i></span>
											<input type="password" name="password_confirmation" class="form-control ps-15 bg-transparent" placeholder="Retype Password" @error('password_confirmation') aria-invalid="true" @endif>
										</div>
										@error('password_confirmation')
											<div class="help-block"><ul role="alert"><li>{{$message}}</li></ul></div>
										@endif
									</div>
									  <div class="row">
										<div class="col-12">
										  <div class="checkbox">
											<input type="checkbox" id="basic_checkbox_1">
											<label for="basic_checkbox_1">I agree to the <a href="#" class="text-primary">Terms</a></label>
										  </div>
										</div>
										<!-- /.col -->
										<div class="col-12 text-center">
											<button type="submit" class="btn btn-primary w-p100 mt-10">REGISTER</button>
										</div>
										<!-- /.col -->
									  </div>
								</form>				
								<div class="text-center">
									<p class="mt-15 mb-0 text-fade">Already have an account?<a href="{{ route('login') }}" class="text-primary ms-5">Sign In</a></p>
								</div>
								
							</div>						
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>


	<!-- Vendor JS -->
	<script src="{{ asset('template/js/vendors.min.js') }}"></script>

</body>
</html>
