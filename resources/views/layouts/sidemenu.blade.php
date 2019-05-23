<ul class="navbar-nav navbar-sidenav" id="exampleAccordion">
	<li class="nav-item @if($controller === 'dashboard') active @endif" data-toggle="tooltip" data-placement="right" title="Dashboard">
		<a class="nav-link" href="{{url('home')}}">
			<i class="fa fa-fw fa-dashboard"></i>
			<span class="nav-link-text">Dashboard</span>
		</a>
	</li>
	<li class="nav-item" data-toggle="tooltip" data-placement="right" title="Profile">
		<a class="nav-link" href="{{url('admin/user/'.Auth::user()->id.'/edit')}}">
			<i class="fa fa-fw fa-user"></i>
			<span class="nav-link-text">Profile</span>
		</a>
	</li>
	
	<li class="nav-item @if($controller == 'user') a @endif" data-toggle="tooltip" data-placement="right" title="Master Settings">
		<a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseComponents" data-parent="#exampleAccordion">
			<i class="fa fa-fw fa-wrench"></i>
			<span class="nav-link-text">User Management</span>
		</a>
		<ul class="sidenav-second-level @if($controller != 'user') collapse @endif" id="collapseComponents">
			<li @if($controller == 'user' and $action == 'index') class="active" @endif>
                 <a href="{{url('/admin/user')}}"><i class="fa fa-fw fa-users"></i> Customer</a>
			</li>
            <li @if($controller == 'user'  and $action == 'service_providers') class="active" @endif>
                 <a href="{{url('/admin/user/service_providers')}}"><i class="fa fa-fw fa-users"></i> Service Providers</a>
			</li>
            <li @if($controller == 'user'  and $action == 'general_contractor') class="active" @endif>
                 <a href="{{url('/admin/user/general_contractor')}}"><i class="fa fa-fw fa-users"></i> General Contractor</a>
			</li>
		</ul>
	</li>	<li class="nav-item" data-toggle="tooltip" data-placement="right" title="Master Settings">		<a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseRequests" data-parent="#exampleAccordion">			<i class="fa fa-fw fa-download"></i>			<span class="nav-link-text">Requests</span>		</a>		<ul class="sidenav-second-level collapse" id="collapseRequests">			<li>                 <a href=""><i class="fa fa-fw fa-users"></i> Customer Requests</a>			</li>		</ul>	</li>
	<li class="nav-item" data-toggle="tooltip" data-placement="right" title="Example Pages">
		<a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseExamplePages" data-parent="#exampleAccordion">
			<i class="fa fa-fw fa-file"></i>
			<span class="nav-link-text">Example Pages</span>
		</a>
		<ul class="sidenav-second-level collapse" id="collapseExamplePages">
			<li>
			  <a href="#">Login Page</a>
			</li>
			<li>
			  <a href="#">Registration Page</a>
			</li>
			<li>
			  <a href="#">Forgot Password Page</a>
			</li>
			<li>
			  <a href="#">Blank Page</a>
			</li>
		</ul>
	</li>
</ul>