@if ($socialProviders)
<?php $colSize = 12 / count($socialProviders); ?>

<div class="divider-wrapper">
	<hr class="or-divider">
</div>

<div class="row">

	@if (in_array('eveonline', $socialProviders))
	<div class="col-md-{{ $colSize }} col-spaced">
		<a href="{{ url('auth/eveonline/login') }}"> 
			<img src="../assets/img/eve-sso-login-white-large.png" align="center">
		</a>
	</div>
	@endif
</div>

@endif