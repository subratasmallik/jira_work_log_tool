<?php
$loginData = Session::get('loginData');
?>
<!doctype html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="site-url" content="{{ url('/') }}" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title')Jira Work Log - Easy to work log</title>
    <link rel="manifest" href="../manifest.json">
    <link rel="stylesheet" href="{{asset('../resources/asset/css/default.css')}}">
    <link rel="stylesheet" href="{{asset('../resources/asset/css/style.css')}}">
    <script src="{{asset('../resources/asset/js/jquery-3.2.1.min.js')}}" type="text/javascript"></script>

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-112920123-10"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-112920123-10');
    </script>


</head>

<body class="body">
    <div class="pageContent">
        @include('layouts/flash-message')
        @yield('content')
    </div>
    <div class="fixedFooter">

    </div>
    <div id="pageLoader">
        <div class="center-center"><img src="{{asset('../resources/images/loader.gif')}}" alt="" /></div>
    </div>
    <!-- FontAwesome-->
    <script defer src="//use.fontawesome.com/releases/v5.1.1/js/all.js"></script>
    <!-- Bootstrap -->
    <link rel="stylesheet" href="//stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <script src="//cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="//stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>

    <!-- // jquery confirm-->
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>

    <!-- // Common js-->
    <script src="{{asset('../resources/asset/js/script.js')}}" type="text/javascript"></script>
    @yield('script')
</body>

</html>