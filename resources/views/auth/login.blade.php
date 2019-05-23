@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="card card-login mx-auto mt-5">
      <div class="card-header">Login</div>
      <div class="card-body">
       <form class="form-horizontal" method="POST" action="{{ route('login') }}">
	   {{ csrf_field() }}
          <div class="form-group">
            <label for="exampleInputEmail1">Email address</label>
            <input class="form-control" id="email" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="Enter email">
			
          </div>
          <div class="form-group">
            <label for="exampleInputPassword1">Password</label>
            <input class="form-control" id="password" type="password" name="password" required placeholder="Password">
			 
          </div>
          <div class="form-group">
            <div class="form-check">
              <label class="form-check-label">
                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
            </div>
          </div>
		  @if ($errors->has('email'))
			 <div class="form-group">	
			<span class="error help-block">
					! {{ $errors->first('email') }}
				</span>
				</div>
			@endif
			@if ($errors->has('password'))
				 <div class="form-group">
				<span class="error help-block">
					! {{ $errors->first('password') }}
				</span>
				</div>
			@endif
		   <button type="submit" class="btn btn-primary btn-block">Login</button>
        </form>
        <div class="text-center">
          <a class="d-block small" href="{{ route('password.request') }}">Forgot Password?</a>
        </div>
      </div>
    </div>
  </div>
@endsection
