@extends('layouts.app')

@section('page-title', 'Observation')

@section('content')

<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
			Observation
			<small> - see something, say something. Submit observations for review. </small>
			<div class="pull-right">
				<ol class="breadcrumb">
					<li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
					<li class="active">Observation</li>
				</ol>
			</div>

		</h1>
	</div>
</div>

@include('partials.messages')
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">Submit Observation
				@permission('observation.manage')
				<div class="pull-right">
					<a href="{{ route('observation.list')}}">Manage</a>
				</div>
				@endpermission

			</div>
			<div class="panel-body">

				See something, say something, this module is used to report anything, cynos/jump freighters/fleets and anything you can think off, if a scout reports something, as recon please drop it in here.


			</div>					
		</div>
	</div>
</div>



<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">Add a submission, as much detail as possible.</div>
			<div class="panel-body">

				<form method="post" action="{{ route('observation.create') }}" enctype="multipart/form-data">
					{{ csrf_field() }}
					<div class="panel-body" >
						<div class="col-md-12">
							<div class="form-group">
								<textarea name="notes" type="text" class="form-control" id="notes" placeholder="Notes" rows="15"></textarea>
							</div>

						</div>
					</div>

					<div class="form-group row">
						<div style="text-align: center;">
							<button type="submit" class="btn btn-success">Submit Observation</button>
						</div>
					</div>
				</form>
			</div>					
		</div>
	</div>
</div>

@stop


@section('scripts')
<script src="/vendor/unisharp/laravel-ckeditor/ckeditor.js"></script>
<script>
	CKEDITOR.replace( 'notes' );
	CKEDITOR.config.width="100%";
	CKEDITOR.config.height="500px"
</script>
@stop