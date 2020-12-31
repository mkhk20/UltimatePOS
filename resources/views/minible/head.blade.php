@yield('css')
<!-- Bootstrap Css -->
<link href="{{ URL::asset('assets/css/bootstrap.min.css')}}" id="bootstrap-style" rel="stylesheet" type="text/css" />
<!-- Icons Css -->
<link href="{{ URL::asset('assets/css/icons.min.css')}}" id="icons-style" rel="stylesheet" type="text/css" />
<!-- App Css-->
<link href="@if ( app()->getLocale() == 'ar' ) {{ URL::asset('assets/css/app-rtl.min.css')}} @else {{ URL::asset('assets/css/app.min.css') }} @endif " id="app-style" rel="stylesheet" type="text/css" />

<link href="{{ URL::asset('custom.css')}}" id="custom-style" rel="stylesheet" type="text/css" />
