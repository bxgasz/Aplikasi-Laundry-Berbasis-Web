@extends('layouts.auth')

@section('login')
<div class="login-box">
    <div class="login-logo">
      <a href="#"><b>Lunatic</b></a>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
      <p class="login-box-msg">Silakan Login</p>
  
      <form action="{{ route('login') }}" method="post" class="form-login">
        @csrf
        <div class="form-group has-feedback @error('username') has-error @enderror">
          <input type="text" class="form-control" placeholder="Username" name="username">
          <span class="glyphicon glyphicon-user form-control-feedback"></span>
          @error('username')
              <span class="help-block">{{ $message }}</span>
          @enderror
        </div>
        <div class="form-group has-feedback @error('password') has-error @enderror">
          <input type="password" class="form-control" placeholder="Password" name="password">
          <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          @error('password')
              <span class="help-block">{{ $message }}</span>
          @enderror
        </div>
        <div class="row">
          <div class="col-xs-8">
            <div class="checkbox icheck">
              <label>
                <input type="checkbox"> Remember Me
              </label>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-xs-4">
            <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
          </div>
          <!-- /.col -->
        </div>
      </form>
  
    </div>
    <!-- /.login-box-body -->
  </div>
  <!-- /.login-box -->
@endsection