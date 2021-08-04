<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{asset('img/favicon.png')}}" rel="shortcut icon" />
    <title>Landover LABS</title>

    <!-- Font Icon -->
    <link rel="stylesheet" href="{{asset('fonts/material-icon/css/material-design-iconic-font.min.css')}}">

    <link rel="stylesheet" href="{{asset('css/app.css')}}" >

    <!-- Main css -->
    <link rel="stylesheet" href="{{asset('css/login.css')}}">
</head>
<body style="max-width:100%;">

    <div class="main">

        @yield('page-section')

    </div>

    <!-- JS -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script>
(function($) {

$(".toggle-password").click(function() {

    $(this).toggleClass("zmdi-eye zmdi-eye-off");
    var input = $($(this).attr("toggle"));
    if (input.attr("type") == "password") {
      input.attr("type", "text");
    } else {
      input.attr("type", "password");
    }
  });

})(jQuery);
    </script>
    <script src="{{asset('js/token_refresh.js')}}"></script>
</body>
</html>
