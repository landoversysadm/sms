@auth
@extends('auth.master')
@section('page-section')
<section class="signup">
        <!-- <img src="images/signup-bg.jpg" alt=""> -->
        <div class="container signin-container">
            <div class="signup-content">
                <form method="POST" action="/register/verify" id="signup-form" class="signup-form">
                    @csrf
                    <h2 class="form-title"><img src="{{asset('img/labs-logo-s.png')}}" /></h2>
                    <h2 class="form-title">Verify email</h2>
                    @include('errors')
                    <small class="help-text">A verification token was sent to <b>{{Auth::user()->email}}</b> kindly
                        enter it below.
                    </small>
                    <div class="form-group">
                        <input type="text" class="form-input <?php if(null !== $errors && @count($errors->get('token'))>0) echo'danger'; ?>" name="activation_token" id="password" placeholder="Verification token" required/>
                        <span toggle="#password" class="zmdi zmdi-eye field-icon toggle-password"></span>
                    </div>
                    <div class="form-group">
                        <input type="submit" name="submit" id="submit" class="form-submit" value="VErify email"/>
                    </div>
                </form>

                <p class="loginhere margin-bottom-30">
                    I didnt receive the token <a href="{{ route('resend_verify') }}" class="loginhere-link">Resend to me</a>
                </p>
            </div>
        </div>
    </section>
@endsection
@endauth

