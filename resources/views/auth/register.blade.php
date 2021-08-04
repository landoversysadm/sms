
<?php
$emailError = 0;
$firstNameError = 0;
$lastNameError = 0;
$passwordError = 0;
?>

@extends('auth.master')

@section('page-section')
<section class="signup">
        <!-- <img src="images/signup-bg.jpg" alt=""> -->
        <div class="container">
            <div class="signup-content">
                <form method="POST" action="/register" id="signup-form" class="signup-form">
                    @csrf
                    @include('errors')
                    <h2 class="form-title"><img src="{{asset('img/labs-logo-s.png')}}" /></h2>
                    <h2 class="form-title">Create user account</h2>
                    <div class="form-group">
                    <input type="text" class="form-input <?php if(count($errors->get('first_name'))>0) echo'danger'; ?> " name="first_name" id="name" placeholder="First Name" value="{{ old('first_name') }}" required/>
                    </div>
                    <div class="form-group">
                            <input type="text" class="form-input <?php if(count($errors->get('last_name'))>0) echo'danger'; ?> " name="last_name" id="name" placeholder="Last Name" value="{{ old('last_name') }}" required/>
                        </div>
                    <div class="form-group">
                        <input type="email" class="form-input @<?php if(count($errors->get('email'))>0) echo'danger'; ?>" name="email" id="email" placeholder="Your Email" value="{{ old('email') }}" required/>
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-input <?php if(count($errors->get('password'))>0) echo'danger'; ?>" name="password" id="password" placeholder="Password" required/>
                        <span toggle="#password" class="zmdi zmdi-eye field-icon toggle-password"></span>
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-input" name="password_confirmation" id="password_confirmation" placeholder="Repeat your password" required/>
                    </div>
                    <div class="form-group">
                        <input type="submit" name="submit" id="submit" class="form-submit" value="Sign up"/>
                    </div>
                </form>
                <p class="loginhere">
                    Have already an account ? <a href="{{ url('/login', []) }}" class="loginhere-link">Login here</a>
                </p>
            </div>
        </div>
    </section>
@endsection


