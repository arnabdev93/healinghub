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
					<li class="{{ Route::is('users.index') ? 'active' : (Route::is('users.create') ? 'active' : (Route::is('users.edit') ? 'active' : '')) }}">
						<a href="{{route('users.index')}}">
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
							<span>Doctors</span>
						</a>
					</li>
					<li class="treeview {{ Route::is('prescription-orders') ? 'menu-open' : (Route::is('prescription-orders.show') ? 'menu-open' : '') }}">
					   <a href="#">
					      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-activity">
					         <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
					      </svg>
					      <span>Orders</span>
					      <span class="pull-right-container">
					      <i class="fa fa-angle-right pull-right"></i>
					      </span>
					   </a>
					   <ul class="treeview-menu {{ Route::is('prescription-orders') ? 'active' : (Route::is('prescription-orders.show') ? 'active' : '') }}" style="{{ Route::is('prescription-orders') ? 'display:block;' : (Route::is('prescription-orders.show') ? 'display:block;' : '') }}">
					      <li><a href="doctor_list.html"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Cart Orders</a></li>
					      <li><a href="{{route('prescription-orders')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Prescription Orders</a></li>
					   </ul>
					</li>

				</ul>
			</div>
		</div>
	</section>
</aside>