@extends('layouts.app')

@section('page-title', 'Post a Dscan')

@section('content')

<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
			dscan me.
			<small> - Post it.</small>
			<div class="pull-right">
				<ol class="breadcrumb">
					<li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
					<li class="active">Dscan</li>
				</ol>
			</div>

		</h1>
	</div>
</div>

@include('partials.messages')


<div class="row tab-search">
	<div class="col-md-5"></div>
</div>


<div class="col-md-12">
	<div class="panel panel-default">
		<div class="panel-heading">Paste a Dscan</div>
		<div class="panel-body">


			<form method="post" action="/dscan/" enctype="multipart/form-data">
				{{ csrf_field() }}
				<div class="form-group row">
					<div class="col-sm-12">
						<textarea name="dscan" type="text" class="form-control" id="dscan" placeholder="Go on... throw it on in.." rows="10"></textarea>
					</div>
				</div>
				<div class="form-group row">
					<div class="offset-sm-3 col-sm-9">
						<button type="submit" class="btn btn-primary">Submit</button>
					</div>
				</div>
			</form>


		</div>
	</div>
</div>


</div>




@stop