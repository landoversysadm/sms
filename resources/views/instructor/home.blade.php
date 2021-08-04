<html>
<meta charset="utf-8">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Overland Learning management system">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{asset('css/icon_fonts/css/all_icons.min.css')}}" rel="stylesheet">
    <link href="{{asset('vendor/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('vendor/datatables/dataTables.bootstrap4.css')}}" rel="stylesheet">
    <link href="{{asset('css/all.css')}}" rel="stylesheet">
    <link href="{{asset('img/favicon.png')}}" rel="shortcut icon" />

    <title>Landover LABS | My dashboard</title>
</head>


<body class="">
    <div id="app">
        {{-- <div class="loader-container" >
            <div class="loader"><span>Loading...</span></div>
        </div> --}}
        <div class="busy" v-if="isBusy">
                <div class="spinner">
                        <div class="dot1"></div>
                        <div class="dot2"></div>
                </div>
        </div>
        <div id="full-container" >
        <nav class="navbar navbar-expand-lg navbar-dark bg-white fixed-top" id="mainNav">
                <a class="navbar-brand" href="/"><img src="{{asset('img/labs-logo.jpg')}}" data-retina="true" alt="LABS Logo" width="160" height="40"></a>
                <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarResponsive">

                <ul class="navbar-nav navbar-sidenav" id="exampleAccordion">
                    <li class="nav-item" data-toggle="tooltip" data-placement="right" title="">
                    <router-link class="nav-link" to="/instructor/dashboard">
                        <i class="fa fa-fw fa-home"></i>
                        <span class="nav-link-text">Dashboard</span>
                    </router-link>
                    </li>

                    <li class="nav-item" data-toggle="tooltip" data-placement="right" title="">
                      <router-link class="nav-link" to="/instructor/students">
                          <i class="fa fa-fw fa-book"></i>
                          <span class="nav-link-text">Students </span>
                      </router-link>
                      </li>
                      <li class="nav-item" data-toggle="tooltip" data-placement="right" title="">
                        <router-link class="nav-link" to="/instructor/courses">
                            <i class="fa fa-fw fa-book"></i>
                            <span class="nav-link-text">Courses </span>
                        </router-link>
                        </li>
                    {{-- <li class="nav-item" data-toggle="tooltip" data-placement="right" title="">
                    <router-link class="nav-link" to="/instructor/notifications">
                        <i class="fa fa-fw fa-envelope-open"></i>
                        <span class="nav-link-text">Notifications </span>
                    </router-link>
                    </li> --}}
                    <li class="nav-item" data-toggle="tooltip" data-placement="right" title="">
                    <router-link class="nav-link"  to="/instructor/assessment">
                        <i class="fa fa-fw fa-table"></i>
                        <span class="nav-link-text">Assessment</span>
                    </router-link>
                    </li>
                    <li class="nav-item" data-toggle="tooltip" data-placement="right" title="">
                    <router-link class="nav-link "  to="/instructor/settings">
                        <i class="fa fa-fw fa-cogs"></i>
                        <span class="nav-link-text">Settings</span>
                    </router-link>
                    </li>
                </ul>
                <ul class="navbar-nav sidenav-toggler">
                    <li class="nav-item">
                    <a class="nav-link text-center" id="sidenavToggler">
                        <i class="fa fa-fw fa-angle-left"></i>
                    </a>
                    </li>
                </ul>
                <ul class="navbar-nav ml-auto">


                    {{-- <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle mr-lg-2" id="alertsDropdown" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <div class="nav-account-image"><img src="" width="30px" height="30px"/></div>
                                    <span class="d-lg-none">Account</span>
                                    <span class="indicator text-warning d-none d-lg-block">

                                    </span>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="alertsDropdown" id="accountDropdown">
                                   <div class="dropdown-item-image" style="min-height:50px;">
                                    <img src="" width="100%" height="auto" />
                                    <span>{{ Auth::user()->first_name .' '. Auth::user()->last_name }}</span>
                                   </div>
                                   <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="#">

                                        <div class="dropdown-message">Profile</div>
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="#">
                                        <div class="dropdown-message">Purchase history</div>
                                    </a>
                                    <div class="dropdown-divider">U</div>
                                    <a class="dropdown-item" href="#">
                                        <div class="dropdown-message">Account settings</div>
                                    </a>
                                    <div class="dropdown-divider">U</div>
                                    <a class="dropdown-item" href="#">
                                    <div class="dropdown-message">Change password</div>
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item small  text-center" href="{{ route('logout') }}"><i class="fa fa-power-off" aria-hidden="true"> </i>  logout</a>
                                </div>



                            </li> --}}
                            <li class="nav-item dropdown">
                              <a class="nav-link dropdown-toggle mr-lg-2" id="alertsDropdown" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Welcome, {{Auth::User()->first_name}}</a>
                                <div class="dropdown-menu" aria-labelledby="alertsDropdown" id="" style="left:0;">
                                        <a class="dropdown-item small  text-center" href="{{ route('logout') }}"><i class="fa fa-power-off" aria-hidden="true"> </i>  logout</a>
                                </div>
                            </li>
                </ul>
                </div>
            </nav>
        <div class="content-wrapper">
            <keep-alive>
                <router-view class="mb-16">
                </router-view>
            </keep-alive>
        </div>
        <footer class="sticky-footer page-footer position-fixed">
                <div class="container">
                  <div class="">
                    <span class="float-left"><small>Copyright © <b id="dateHolder"></b> LABS</small></span>
                    <span class="float-right"><small>Made with <i class="fa fa-heart"></i> by <a target="_blank" href="https://penciledge.net">Penciledge</a> </small></span>
                  </div>
                </div>
              </footer>
        </div>

    </div>

</body>
<script src="{{asset('js/app.js')}}"></script>
<script src="{{asset('vendor/jquery.selectbox-0.2.js')}}"></script>
<script src="{{asset('vendor/jquery.magnific-popup.min.js')}}"></script>
<script src="{{asset('js/dashboard.js')}}"></script>
<script src="{{asset('js/page.js')}}"></script>
<script src="{{asset('js/bootstrap.js')}}"></script>
<script>
$(".custom-file-input").on("change", function() {
	var fileName = $(this).val().split("\\").pop();
	$(this).siblings(".custom-file-label").addClass("selected").html(fileName);
  });

  $(document).ready(()=>{
      let date = new Date().getFullYear()
      $("#dateHolder").text(date);
  })

</script>

</html>
