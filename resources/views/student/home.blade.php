<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Overland Learning management system">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{asset('css/icon_fonts/css/all_icons.min.css')}}" rel="stylesheet">
    <link href="{{asset('vendor/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('vendor/datatables/dataTables.bootstrap4.css')}}" rel="stylesheet">
    <link href="{{asset('css/all.css')}}" rel="stylesheet">
    <link href="{{asset('img/favicon.png')}}" rel="shortcut icon" />
    <link href="https://unpkg.com/nprogress@0.2.0/nprogress.css" rel="stylesheet" />
<script src="https://unpkg.com/nprogress@0.2.0/nprogress.js"></script>


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
                <a class="navbar-brand" href="{{ url('/', []) }}"><img src="{{asset('img/labs-logo.jpg')}}" data-retina="true" alt="Labs Logo" width="160" height="40"></a>
                <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarResponsive">

                <ul class="navbar-nav navbar-sidenav" id="exampleAccordion">
                    <li class="nav-item" data-toggle="tooltip" data-placement="right" title="">
                    <router-link class="nav-link" to="/student/dashboard">
                        <i class="fa fa-fw fa-home"></i>
                        <span class="nav-link-text">Dashboard</span>
                    </router-link>
                    </li>
                    <li class="nav-item" data-toggle="tooltip" data-placement="right" title="">
                    <router-link class="nav-link" to="/student/notifications">
                        <i class="fa fa-fw fa-envelope-open"></i>
                        <span class="nav-link-text">Notifications <span class="badge badge-pill badge-primary">{{count(Auth::user()->unreadNotifications)}} New</span></span>
                    </router-link>
                    </li>
                    <li class="nav-item" data-toggle="tooltip" data-placement="right" title="">
                    <router-link class="nav-link" to="/student/courses">
                        {{-- <i class="fa fa-fw fa-archive"></i> --}}
                        <i class="fa fa-fw fa-book"></i>
                        <span class="nav-link-text">Courses </span>
                    </router-link>
                    </li>
                    <li class="nav-item" data-toggle="tooltip" data-placement="right" title="">
                    <router-link class="nav-link"  to="/student/assessment">
                        <i class="fa fa-fw fa-table"></i>
                        <span class="nav-link-text">Assessment</span>
                    </router-link>
                    </li>
                    {{-- <li class="nav-item" data-toggle="tooltip" data-placement="right" title="">
                    <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseProfile" data-parent="#exampleAccordion">
                        <i class="fa fa-fw fa-wrench"></i>
                        <span class="nav-link-text">Account</span>
                    </a>
                    <ul class="sidenav-second-level collapse" id="collapseProfile">
                        <li>
                        <router-link to="/student/profile/change-password">Change password</router-link>
                        </li>
                        <li>
                        <router-link to="/student/profile/update">Update profile</router-link>
                        </li>
                    </ul>
                    </li> --}}
                    <li class="nav-item" data-toggle="tooltip" data-placement="right" title="">
                    <router-link class="nav-link" data-toggle="collapse" to="/student/payments">
                        <i class="fa fa-fw fa-history"></i>
                        <span class="nav-link-text">Payment</span>
                    </router-link>
                    </li>
                    <li class="nav-item" data-toggle="tooltip" data-placement="right" title="">
                        <router-link class="nav-link" data-toggle="collapse" to="/student/profile">
                            <i class="fa fa-fw fa-wrench"></i>
                            <span class="nav-link-text">Account</span>
                        </router-link>
                        </li>
                    <li class="nav-item" data-toggle="tooltip" data-placement="right" title="">
                        <a class="nav-link"   href="{{ route('logout') }}"><i class="fa fa-power-off" aria-hidden="true"> </i>
                          <span class="nav-link-text">Logout</span>
                      </a>
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
                    <li class="nav-item dropdown d-none d-sm-none d-lg-block ">
                    <a class="nav-link dropdown-toggle mr-lg-2" id="messagesDropdown" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-fw fa-envelope"></i>
                        <span class="d-lg-none">Notifications
                        <span class="badge badge-pill badge-primary">{{count(Auth::user()->unreadNotifications)}} New</span>
                        </span>
                        <span class="indicator d-none d-lg-block">
                            @if (count(Auth::user()->unreadNotifications) > 0)
                                <i class="fa fa-fw fa-circle" style="color:red;"></i>
                            @else
                                <i class="fa fa-fw fa-circle"></i>
                            @endif

                        </span>
                    </a>

                        @if (count(Auth::user()->unreadNotifications)>0)
                        <div class="dropdown-menu" aria-labelledby="messagesDropdown" style="margin-left:-15em;">

                            <h6 class="dropdown-header">New Notifications</h6>
                        <div class="dropdown-divider"></div>
                        @foreach (Auth::user()->unreadNotifications as $notification)
                            <router-link class="dropdown-item" to="/student/notifications">
                                <span class="text-{{$notification->data["type"]}}">
                                    <strong>{{$notification->data["title"]}}</strong>
                                </span>
                                <span class="small float-right text-muted"></span>
                                <div class="dropdown-message small">{{$notification->data["body"]}}</div>
                            </router-link>
                        @endforeach
                        </div>
                        @else
                        <div class="dropdown-menu" aria-labelledby="messagesDropdown" style="left:-100px">
                            <router-link class="dropdown-item small" to="/student/notifications">No new notifications</router-link>
                        </div>

                        @endif

                    </li>

                    <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle mr-lg-2" id="alertsDropdown" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{-- <div class="nav-account-image"><img src="https://s3-penciledge-dev.s3.eu-north-1.amazonaws.com/public{{ str_replace('public','',Auth::user()->student->profile_picture_url.' ')}}" width="30px" height="30px"/></div> --}}
                                    <div class="nav-account-image"><img src="/storage/{{ str_replace('public','',Auth::user()->student->profile_picture_url.' ')}}" width="30px" height="30px"/></div>

                                    <span class="d-lg-none">Account</span>
                                    <span class="indicator text-warning d-none d-lg-block">
                                    {{-- <i class="fa fa-fw fa-user"></i> --}}

                                    </span>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="alertsDropdown" id="accountDropdown">
                                   <div class="dropdown-item-image" style="min-height:100px;">
                                    <img style="min-height:100px;" src="/storage/{{ str_replace('public','',Auth::user()->student->profile_picture_url.' ')}}" width="100%" height="auto" />
                                    <span>{{ Auth::user()->first_name .' '. Auth::user()->last_name }}</span>
                                   </div>
                                   <div class="dropdown-divider"></div>
                                    <router-link class="dropdown-item" to="/student/profile">

                                        <div class="dropdown-message">Profile</div>
                                    </router-link>
                                    <div class="dropdown-divider"></div>
                                    <router-link class="dropdown-item" to="/student/payments">
                                        <div class="dropdown-message">Payment history</div>
                                    </router-link>
                                    <div class="dropdown-divider"></div>
                                    <router-link class="dropdown-item" to="/student/profile">
                                        <div class="dropdown-message">Account settings</div>
                                    </router-link>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item small  text-center" href="{{ route('logout') }}"><i class="fa fa-power-off" aria-hidden="true"> </i>  logout</a>
                                </div>
                    </li>
                </ul>
                </div>
            </nav>
        <div class="content-wrapper pb-4">
                @if (!empty($new_enrollment))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <h4 class="alert-heading">Enrollment successful!</h4>
                        <p>A notification would be sent to you when admin approves it</p>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                </div>
            @endif
                <keep-alive>
                  <router-view class="mb-16">
                  </router-view>
                </keep-alive>

        </div>
        <footer class="sticky-footer page-footer position-fixed">
            <div class="container">
              <div class="">
                <span class="float-left"><small>Copyright © <b id="dateHolder"></b> LABS</small></span>
                <span class="float-right hide-print"><small>Made with <i class="fa fa-heart"></i> by <a target="_blank" href="https://penciledge.net">Penciledge</a> </small></span>
              </div>
            </div>
          </footer>
        </div>

    </div>
<script>
        //bind  global values to window
        const user = {
            fullName : `{{ Auth::user()->first_name.' '.Auth::user()->midlle_name.' '.Auth::user()->last_name }}`,
            profilePictureUrl : `{{ str_replace('public','',Auth::user()->student->profile_picture_url.' ') }}`
        }
        Window.user = user;
</script>
<script src="{{asset('js/app.js')}}"></script>
<script src="{{asset('vendor/jquery-easing/jquery.easing.min.js')}}"></script>
<script src="{{asset('vendor/chart.js/Chart.js')}}"></script>
<script src="{{asset('vendor/datatables/jquery.dataTables.js')}}"></script>
<script src="{{asset('vendor/datatables/dataTables.bootstrap4.js')}}"></script>
<script src="{{asset('vendor/jquery.selectbox-0.2.js')}}"></script>
{{-- <script src="{{asset('vendor/retina-replace.min.js')}}"></script>
<script src="{{asset('js/dashboard-charts.js')}}"></script>
--}}
<script src="{{asset('vendor/jquery.magnific-popup.min.js')}}"></script>
<script src="{{asset('js/dashboard.js')}}"></script>
<script src="{{asset('js/page.js')}}"></script>
<script src="{{asset('js/bootstrap.js')}}"></script>
<script>
$(document).ready(()=>{
      let date = new Date().getFullYear()
      $("#dateHolder").text(date);
  })
</script>

</body>

</html>
