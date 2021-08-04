@extends('auth.master')

@section('page-section')
<section class="signup">
  <div class="container signin-container">
    <div class="signup-content">

                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{url('password/email')}}">
                        @csrf

                        <div class="form-group">
                            <label for="email" class="col-md-12 col-form-label text-center">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-12">
                                <input id="email" type="email" class="form-input @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-12">
                                <input type="submit" name="submit" id="submit" class="form-submit" value="{{ __('Send Password Reset Link') }}"/>
                            </div>
                        </div>
                    </form>
        </div>
    </div>
</section>
<style>
body {background-position: initial;}
.signup{margin-top: 10%;}
</style>
@endsection


