<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title>@yield('title', 'EPOS - Electronic Purchase Order System')</title>
	 
	<meta name="description" content="">
	<meta name="viewport" content="width=device-width">

	@section('style')
	<!-- Styling -->
		<link href='http://fonts.googleapis.com/css?family=Lato:400,700,900' rel='stylesheet' type='text/css'>
		<!--[if lte IE 8]>
		    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Lato:400" /> 
		    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Lato:700" /> 
		    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Lato:900" />
		<![endif]-->
		<link rel="stylesheet" href="{{ asset('components/bootstrap/dist/css/bootstrap.min.css') }}">
		<link rel="stylesheet" href="{{ asset('components/font-awesome/css/font-awesome.min.css') }}">
		<!--[if IE 7]>
		  <link rel="stylesheet" href="{{ asset('components/font-awesome/css/font-awesome-ie7.min.css') }}">
		<![endif]-->
		<link rel="stylesheet" href="{{ asset('components/bootstrap-datepicker/css/datepicker.css') }}">
		<link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
	@show
	
	<!-- App -->
	<script>
		window.App      = window.App || {};
		App.siteURL     = '{{ URL::to("/") }}';
		App.currentURL  = '{{ URL::current() }}';
		App.fullURL     = '{{ URL::full() }}';
		App.apiURL      = '{{ URL::to("api") }}';
		App.assetURL    = '{{ URL::to("assets") }}';
	</script>
	 
	<!-- jQuery and Modernizr -->
	<script src="{{ URL::asset('components/modernizr/modernizr.js') }}"></script>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
	<script>window.jQuery || document.write('<script src="{{ URL::asset("components/jquery/jquery.min.js") }}"><\/script>')</script>

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->

	@yield('script.header')
	 
</head>
<body id="{{ SiteHelper::bodyId() }}" class="{{ SiteHelper::bodyClass() }}">

	@include('layouts.default.header')

	@yield('content')

	@section('script.footer')
		<script src="{{ URL::asset('components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
		<script src="{{ URL::asset('components/bootstrap-datepicker/js/bootstrap-datepicker.js') }}"></script>
		<script src="{{ URL::asset('assets/js/main.js') }}"></script>
	@show

	<script>
	@section('script.embedded.footer')
		
	@show
	</script>
 
</body>
</html>