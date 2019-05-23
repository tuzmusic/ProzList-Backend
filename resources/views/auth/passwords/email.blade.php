@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="card card-login mx-auto mt-5">
      <div class="card-header">Reset Password</div>
      <div class="card-body">
        <div class="text-center mt-4 mb-5">
          <h4>Forgot your password?</h4>
          <p>Enter your email address and we will send you instructions on how to reset your password.</p>
        </div>
		<form class="form-horizontal" method="POST" action="{{ route('password.email') }}">
			{{ csrf_field() }}
			<div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
				<input class="form-control" id="email" type="email" name="email" value="{{ old('email') }}" required aria-describedby="emailHelp" placeholder="Enter email address">
				@if ($errors->has('email'))
					<span class="help-block">
						<strong>{{ $errors->first('email') }}</strong>
					</span>
				@endif
			</div>
			<button type="submit" class="btn btn-primary btn-block">Reset Password</button>
        </form>
		@if (session('status'))
			<div class="alert alert-success">
				{{ session('status') }}
			</div>
		@endif
        <div class="text-center">
          <a class="d-block small" href="{{url('login')}}">Login Page</a>
        </div>
      </div>
    </div>
  </div>
@endsection
