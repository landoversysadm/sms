@extends('auth.master')
@section('page-section')
<section class="signup">

        <!-- <img src="images/signup-bg.jpg" alt=""> -->
        <div class="container signin-container">
            <div class="signup-content">
                <form method="POST" action="/login" id="signup-form" class="signup-form">
                  @include('errors')
                    @csrf
                    <h2 class="form-title"><img src="{{asset('img/labs-logo-s.png')}}" /></h2>
                    <h2 class="form-title">Login to account</h2>
                    <div class="form-group">
                        <input type="email" class="form-input  {!! $errors->has('email')?'danger':'' !!}" name="email" id="email" placeholder="Your Email" required value="{{old('email')}}"/>
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-input {!! $errors->has('password')?'danger':'' !!}" name="password" id="password" placeholder="Password" required/>
                        <span toggle="#password" class="zmdi zmdi-eye field-icon toggle-password"></span>
                    </div>
                    <div class="form-group">
                        <input type="checkbox" name="agree-term" id="agree-term" class="agree-term" />
                        <label for="agree-term" class="label-agree-term"><span><span></span></span>Remember Me</label>
                    </div>
                    <div class="form-group">
                        <input type="submit" name="submit" id="submit" class="form-submit" value="Login"/>
                    </div>
                </form>
                <p class="loginhere">
                    Dont have an account yet ? <a href="{{ route('register') }}" class="loginhere-link">Register here</a>
                </p>
                <p class="text-center">
                  @if (Route::has('password.request'))
                      <a class="btn btn-link loginhere-link" href="{{ route('password.request') }}">
                          {{ __('Forgot Your Password?') }}
                      </a>
                  @endif
                </p>

            </div>
        </div>
    </section>
@endsection

