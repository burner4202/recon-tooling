@extends('layouts.auth')

@section('page-title', 'Security Checker Tool')

@section('content')

<div class="form-wrap col-md-12 auth-form" id="login">
	<div style="text-align: center; margin-bottom: 25px;">
		<img src="{{ url('assets/img/vanguard-logo.png') }}" alt="{{ settings('app_name') }}">
	</div>

	{{-- This will simply include partials/messages.blade.php view here --}}
	@include('partials/messages')

	<div style="text-align: center;">
		<h1>FAIL ESI Security Checking Tool</h1>
		<h4>Our recruiters need to check your ingame data for security reasons via EVE ESI.</h4>
		<h4>Please click the button below and select your main character, followed by your alts.</h4>
		<h4>Once redirected, the website will give you a link to give to the recruiter.</h4>
		<h4>The link has a 1 hour expiry.</h4>
		<br>

	</div>

	<form role="form" action="#" method="#" id="login-form" autocomplete="off">
		<input type="hidden" value="<?= csrf_token() ?>" name="_token">

		<div class="form-group">
			<button type="submit" class="btn btn-custom btn-lg btn-block" id="btn-login">
				Login to EVE Online & Submit Your ESI Token.
			</button>
		</div>

	</form>

	<br><br>


	<div style="text-align: center;">
		<a href="https://www.eveonline.com/article/introducing-esi/#tldr" target="_blank">What is ESI, is my data safe?</a>
	</div>
	

	

</div>

@stop

@section('scripts')
{!! HTML::script('assets/js/as/login.js') !!}
{!! JsValidator::formRequest('Vanguard\Http\Requests\Auth\LoginRequest', '#login-form') !!}
@stop