@extends('layouts.app')

@section('content')
<!-- ////////////////////////////////////////////////////////////////////////////-->
<div class="robust-content content container-fluid">
  <div class="content-wrapper">
    <div class="content-header row">
    </div>
    <div class="content-body">
      <section class="flexbox-container">
<div class="col-md-4 offset-md-4 col-xs-10 offset-xs-1  box-shadow-2 p-0">
    <div class="card border-grey border-lighten-3 m-0">
        <div class="card-header no-border">
            <div class="card-title text-xs-center">
                <div class="p-1">
                <img src="{{ getSettings('general-settings','logo_dark') }}" width="100%" alt="branding logo">
                </div>
            </div>
            <h6 class="card-subtitle line-on-side text-muted text-xs-center font-small-3 pt-2"><span>Please login first</span></h6>
        </div>
        <div class="card-body collapse in">
            <div class="card-block">
                <form class="form-horizontal form-simple" role="form" method="POST" action="{{ route('login') }}">
                    {{ csrf_field() }}
                    <fieldset class="form-group{{ $errors->has('email') ? ' has-error' : '' }} has-feedback has-icon-left mb-0">
                      <input id="email" type="email" class="form-control form-control-lg input-lg" placeholder="Your Email" name="email" value="{{ old('email') }}" required autofocus>
                        <div class="form-control-position">
                            <i class="icon-head"></i>
                        </div>
                    </fieldset>

                    <fieldset class="form-group{{ $errors->has('password') ? ' has-error' : '' }} has-feedback has-icon-left">
                        <input type="password" class="form-control form-control-lg input-lg" name="password" id="password" placeholder="Enter Password" required>
                        <div class="form-control-position">
                            <i class="icon-key3"></i>
                        </div>
                    </fieldset>
                    @if ($errors->has('email'))
                        <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                    @if ($errors->has('password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                    <fieldset class="form-group row">
                        <div class="col-md-6 col-xs-12 text-xs-center text-md-left">
                            <fieldset>
                              <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }} class="chk-remember" id='remember'>
                                <label for="remember-me"> Remember Me</label>
                            </fieldset>
                        </div>
                        <div class="col-md-6 col-xs-12 text-xs-center text-md-right"><a href="{{ route('password.request') }}" class="card-link">Forgot Password?</a></div>
                    </fieldset>
                    <button type="submit" class="btn btn-primary btn-lg btn-block"><i class="icon-unlock2"></i> Login</button>
                </form>
            </div>
        </div>
        <div class="card-footer">
            <div class="">
                <p class="float-sm-left text-xs-center m-0">Copyright Indowebdeveloper.com</p>
            </div>
        </div>
    </div>
</div>
</section>

    </div>
  </div>
</div>
<!-- ////////////////////////////////////////////////////////////////////////////-->
@endsection
<!-- Here we use for include js -->
@section('jses')
<script src="/assets/js/login.js" type="text/javascript"></script>
@endsection
