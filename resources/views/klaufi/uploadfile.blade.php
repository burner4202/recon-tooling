@extends('layouts.app')

@section('page-title', 'Klaufi')

@section('content')

<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
			Klaufi
			<small>Ship Log File Upload</small>
			<div class="pull-right">
				<ol class="breadcrumb">
					<li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
					<li class="active">Klaufi Upload</li>
				</ol>
			</div>
		</h1>
	</div>
</div>


@include('partials.messages')

	<div class="row">
		<div class="col-lg-12">
		<div class="card">
			<div class="card-header"></div>

			<div class="card-body">
				@if ($message = Session::get('success'))

				<div class="alert alert-success alert-block">

					<button type="button" class="close" data-dismiss="alert">×</button>

					<strong>{{ $message }}</strong>

				</div>

				@endif

				@if (count($errors) > 0)
				<div class="alert alert-danger">
					<strong>Whoops!</strong> There were some problems with your input.<br><br>
					<ul>
						@foreach ($errors->all() as $error)
						<li>{{ $error }}</li>
						@endforeach
					</ul>
				</div>
				@endif


				<form action="/klaufi/uploadfile" method="post" enctype="multipart/form-data">
					{{ csrf_field() }} 
					<div class="form-group">
						<input type="file" class="form-control-file" name="fileToUpload" id="exampleInputFile" aria-describedby="fileHelp">
						<small id="fileHelp" class="form-text text-muted">Please upload ship CSV log.</small>
					</div>
					<button type="submit" class="btn btn-primary">Submit</button>
				</form>
			</div>
		</div>
	</div>
</div>




@stop