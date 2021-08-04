<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Landover Aviation Business School Learning management system">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{asset('css/icon_fonts/css/all_icons.min.css')}}" rel="stylesheet">
    <link href="{{asset('img/favicon.png')}}" rel="shortcut icon" />
    <link href="{{asset('css/all.css')}}" rel="stylesheet">
     @if ($App && $App->app_title)
      <title>{{$App->app_title}}</title>
    @else
      <title>Landover</title>
    @endif

    <style>
      @if ($App && $App->app_banner)
        #hero_in.courses:before{
          background-image: url(/storage/{{ $App->app_banner }})
        }
      @endif
    </style>
</head>

<body>

	<div id="app">
            <header class="header menu_2">
                    <!-- /Preload -->
                    <div id="logo">
                        <a href="/"><img src="img/logo.png" width="220" height="56.4" data-retina="true" alt=""></a>
                    </div>
                    <ul id="top_menu">
                            @if (Route::has('login'))
                                @auth
                                    <li class="dropdown">
                                        <span class="nav-link  dropdown-toggle" data-toggle="dropdown" v-on:click="showTopDropdown = !showTopDropdown">Hello, {{Auth::User()->first_name}} <i class="caret"></i></span>
                                            <ul class="dropdown-menu topDropDown" v-show="showTopDropdown">
                                                @if (!empty(Auth::User()->student->id) || Auth::User()->role === 2 || Auth::User()->role === 3)
                                                    <li  class="dropdown-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                                                @endif

                                              <li  class="dropdown-item"><a href="{{route('logout')}}">Logout</a></li>
                                            </ul>
                                    </li>
                                @else
                                <li><a href="{{ route('login') }}" class="login">Login</a></li>

                                    @if (Route::has('register'))
                                    <li class="hidden_tablet top-0"><a href="{{ route('register') }}" class="btn_1 rounded">Register</a></li>
                                    @endif
                                @endauth
                        @endif
                        {{-- <li><a href="#0" class="search-overlay-menu-btn">Search</a></li> --}}

                    </ul>

                    <!-- Search Menu -->
                    {{-- <div class="search-overlay-menu">
                        <span class="search-overlay-close"><span class="closebt"><i class="ti-close"></i></span></span>
                        <form role="search" id="searchform" method="get">
                            <input value="" name="q" type="search" placeholder="Search..." />
                            <button type="submit"><i class="icon_search"></i>
                            </button>
                        </form>
                    </div><!-- End Search Menu --> --}}
                </header>
    <router-view>

    </router-view>
    <CourseModal></CourseModal>
	</div>
	<!-- page -->


    <script src="{{asset('js/app.js')}}"></script>
    <script src="{{asset('js/common_scripts.js')}}"></script>
    <script src="{{asset('js/main.js')}}"></script>

</body>
</html>
