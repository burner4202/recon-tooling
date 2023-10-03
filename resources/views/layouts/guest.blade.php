<!doctype html>
<!-- 
/*
 * Goonswarm Federation Recon Tools
 *
 * Developed by scopehone <scopeh@gmail.com>
 * In conjuction with Natalya Spaghet & Mindstar Technology 
 *
 */
-->
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<meta name="csrf-token" content="{{ csrf_token() }}">

	<title>@yield('page-title') | {{ settings('app_name') }}</title>

	<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">

	<link rel="apple-touch-icon-precomposed" sizes="144x144" href="{{ url('assets/img/icons/apple-touch-icon-144x144.png') }}" />
	<link rel="apple-touch-icon-precomposed" sizes="152x152" href="{{ url('assets/img/icons/apple-touch-icon-152x152.png') }}" />
	<link rel="icon" type="image/png" href="{{ url('assets/img/icons/favicon-32x32.png') }}" sizes="32x32" />
	<link rel="icon" type="image/png" href="{{ url('assets/img/icons/favicon-16x16.png') }}" sizes="16x16" />
	<meta name="application-name" content="{{ settings('app_name') }}"/>
	<meta name="msapplication-TileColor" content="#FFFFFF" />
	<meta name="msapplication-TileImage" content="{{ url('assets/img/icons/mstile-144x144.png') }}" />

	{{-- For production, it is recommended to combine following styles into one. --}}
	{!! HTML::style('assets/css/bootstrap.min.css') !!}
	{!! HTML::style('assets/css/font-awesome.min.css') !!}
	{!! HTML::style('assets/css/metisMenu.css') !!}
	{!! HTML::style('assets/css/sweetalert.css') !!}
	{!! HTML::style('assets/css/bootstrap-social.css') !!}
	{!! HTML::style('assets/css/app.css') !!}
	{!! HTML::style('assets/css/dataTables.bootstrap.min.css') !!}

	@yield('styles')
</head>

{{-- For production, it is recommended to combine following scripts into one. --}}
	{!! HTML::script('assets/js/jquery-2.1.4.min.js') !!}
	{!! HTML::script('assets/js/bootstrap.min.js') !!}
	{!! HTML::script('assets/js/metisMenu.min.js') !!}
	{!! HTML::script('assets/js/sweetalert.min.js') !!}
	{!! HTML::script('assets/js/delete.handler.js') !!}
	{!! HTML::script('assets/js/jquery.dataTables.min.js') !!}
	{!! HTML::script('assets/plugins/js-cookie/js.cookie.js') !!}
	{!! HTML::script('assets/js/jquery.countdown.min.js') !!}
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.min.js"></script>
	<script type="text/javascript">
		$.ajaxSetup({
			headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
		});
	</script>
	{!! HTML::script('vendor/jsvalidation/js/jsvalidation.js') !!}
	{!! HTML::script('assets/js/as/app.js') !!}
	@yield('scripts')
</body>
</html>