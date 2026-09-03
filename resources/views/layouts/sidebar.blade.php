<aside class="main-sidebar">
	<!-- sidebar-->
	<section class="sidebar position-relative">
		<div class="multinav">
			<div class="multinav-scroll" style="height: 99%;">
				<!-- sidebar menu-->
				<ul class="sidebar-menu" data-widget="tree">
					<li class="{{ Route::is('home') ? 'active' : '' }}">
						<a href="{{ route('home') }}">
							<i data-feather="home"></i>
							<span>Dashboard</span>
						</a>
					</li>
					<li class="{{ Route::is('banner.index') ? 'active' : (Route::is('banner.create') ? 'active' : (Route::is('banner.edit') ? 'active' : '')) }}">
						<a href="{{route('banner.index')}}">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-banner">
							  <!-- Banner 1: Flag Banner -->
							  <svg width="24" height="24" viewBox="0 0 24 24">
							    <path d="M4 15s1-1 5-1 5.998 1 8 1c1.657 0 2.357-.649 2.75-1.025V4c-.504.501-1.363 1.003-2.75 1.003C13.998 5 10 4 5 4 1 4 0 8 0 11.667c0 4 1 6.585 4 7.585"></path>
							  </svg>
							</svg>
							<span>Banners</span>
						</a>
					</li>
					<li class="{{ Route::is('categories.index') ? 'active' : (Route::is('categories.create') ? 'active' : (Route::is('categories.edit') ? 'active' : '')) }}">
						<a href="{{route('categories.index')}}">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-categories">
							    <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
							    <circle cx="12" cy="13" r="3"></circle>
							</svg>
							<span>Categories</span>
						</a>
					</li>
					<li class="{{ Route::is('trending-categories.index') ? 'active' : (Route::is('trending-categories.create') ? 'active' : (Route::is('trending-categories.edit') ? 'active' : '')) }}">
						<a href="{{route('trending-categories.index')}}">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							  <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
							  <path d="M12 9l.94 2.88h3.03l-2.45 1.78.94 2.88L12 15.54l-2.45 1.78.94-2.88-2.45-1.78h3.03z"></path>
							</svg>
							<span>Trending Categories</span>
						</a>
					</li>
					<li class="{{ Route::is('products.index') ? 'active' : (Route::is('products.create') ? 'active' : (Route::is('products.edit') ? 'active' : '')) }}">
						<a href="{{route('products.index')}}">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							  <path d="M12 2L2 7v10l10 5 10-5V7l-10-5z"></path>
							  <path d="M2 7l10 5 10-5"></path>
							  <path d="M12 12v10"></path>
							</svg>
							<span>Products</span>
						</a>
					</li>
                    {{-- <li class="{{ Route::is('customer.index')}}"> --}}
                    <li class="{{ Route::is('customer.index') ? 'active' : ''}}">
						<a href="{{route('customer.index')}}">
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users">
								<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
								<circle cx="9" cy="7" r="4"></circle>
								<path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
								<path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
							</svg>
							<span>Users</span>
						</a>
					</li>
					<li class="{{ Route::is('users.index') ? 'active' : (Route::is('users.create') ? 'active' : (Route::is('users.edit') ? 'active' : '')) }}">
						<a href="{{route('users.index')}}">
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users">
								<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
								<circle cx="9" cy="7" r="4"></circle>
								<path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
								<path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
							</svg>
							<span>Doctors</span>
						</a>
					</li>
					<li class="treeview {{
                        Route::is('cart-orders') || Route::is('prescription-orders') || Route::is('prescription-orders.show')
                        ? 'menu-open' : '' }}">
					   <a href="#">
					      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					         <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
					      </svg>
					      <span>Orders</span>
					      <span class="pull-right-container">
					      	<i class="fa fa-angle-right pull-right"></i>
					      </span>
					   </a>
					   <ul class="treeview-menu {{
                            Route::is('cart-orders') || Route::is('prescription-orders') || Route::is('prescription-orders.show')
                            ? 'active' : '' }}"
                        style="{{Route::is('cart-orders') || Route::is('prescription-orders') || Route::is('prescription-orders.show') ? 'display:block;' : ''}}">
							<li class="{{ Route::is('cart-orders') ? 'active' : '' }}">
								<a href="{{route('cart-orders')}}">
									<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
										<circle cx="9" cy="21" r="1"></circle>
										<circle cx="20" cy="21" r="1"></circle>
										<path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
										<path d="M11 9.5l3 1.5l3-1.5M11 9.5v3l3 1.5v-3M17 9.5v3l-3 1.5v-3"></path>
										<path d="M14 8v3l-3-1.5V6.5L14 8zM14 8l3-1.5V4.5L14 3v3zM14 3v3l-3 1.5"></path>
									</svg>
									&nbsp;&nbsp;&nbsp;&nbsp;
									<span class="">Cart Orders</span>
								</a>
							</li>
					      	<li class="{{ Route::is('prescription-orders') || Route::is('prescription-orders.show') ? 'active' : '' }}">
								<a href="{{route('prescription-orders')}}">
									<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
										<circle cx="9" cy="21" r="1"></circle>
										<circle cx="20" cy="21" r="1"></circle>
										<path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
										<line x1="11" y1="8" x2="17" y2="8"></line>
										<line x1="11" y1="11" x2="17" y2="11"></line>
										<line x1="11" y1="14" x2="17" y2="14"></line>
									</svg>
									&nbsp;&nbsp;&nbsp;&nbsp;
									<span class="">Prescription Orders</span>
								</a>
							</li>
					   </ul>
					</li>

					<li class="{{ Route::is('appointments.index') ? 'active' : (Route::is('appointments.index') ? 'active' : (Route::is('appointments.index') ? 'active' : '')) }}">
						<a href="{{route('appointments.index')}}">
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
								<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
								<polyline points="14 2 14 8 20 8"></polyline>
								<path d="M16 13a4 4 0 1 1-4-4h1a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1h-1"></path>
								<path d="M12 17v2"></path>
								<path d="M12 7v2"></path>
							</svg>
							<span>Booking appoinment</span>
						</a>
					</li>

					{{-- <li class="{{ Route::is('doctor.earnings') ? 'active' : (Route::is('doctor.earnings') ? 'active' : (Route::is('doctor.earnings') ? 'active' : '')) }}">
						<a href="{{route('doctor.earnings')}}">
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
								<path d="M12 2v20"></path>
								<path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
							</svg>
							<span>Total Earnings</span>
						</a>
					</li> --}}
                    <li class="{{ Route::is('setting-manage') ? 'active' : (Route::is('setting-manage') ? 'active' : (Route::is('setting-manage') ? 'active' : '')) }}">
						<a href="{{route('setting-manage')}}">
							 <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="3"></circle>
                                    <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                                </svg>
							<span>Settings</span>
						</a>
					</li>

				</ul>
			</div>
		</div>
	</section>
</aside>
