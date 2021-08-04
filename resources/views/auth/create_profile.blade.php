@extends('auth.master')
@section('page-section')
@include('errors')
<section class="signup">
        <!-- <img src="images/signup-bg.jpg" alt=""> -->
        <div class="container">
            <div class="signup-content">
                <form method="POST" action="/create/profile" id="signup-form" class="signup-form" enctype="multipart/form-data">
                    @csrf
                    <h2 class="form-title"><img src="{{asset('img/overland-logo.png')}}" /></h2>
                    <h2 class="form-title">Create a student profile</h2>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                            Your email <strong>{{Auth::user()->email}}</strong> has been verified, complete
                            profile to create a student account
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                    <div class="form-group">
                        <label> Your date of birth</label>
                        <input type="date" class="form-input" name="date_of_birth" id="date_of_birth" placeholder="select your profile"  picture required/>
                    </div>

                    <div class="form-group">
                        <label> Select your profile picture</label>
                        <input type="file" class="form-input" name="profile_picture" id="profile_picture" placeholder="select your profile picture"  required/>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Male <input class="form-input" type="radio" name="sex" value="male" /></label>
                        <label class="form-label">Female <input class="form-input" type="radio" name="sex" value="female" /></label>
                    </div>
                    <div class="form-group">
                        <input type="checkbox" name="agree-term" id="agree-term" class="agree-term" required />
                        <label for="agree-term" class="label-agree-term"><span><span></span></span>I agree to Overland airways <a href="#" class="term-service">Terms of service</a></label>
                    </div>
                    <div class="form-group">
                        <input type="submit" name="submit" id="submit" class="form-submit" value="create profile"/>
                    </div>
                </form>
                <p class="loginhere">
                    Have a student account already ? <a href="{{ route('register') }}" class="loginhere-link">Login here</a>
                </p>
            </div>
        </div>
    </section>
@endsection

