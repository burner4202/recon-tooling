@extends('layouts.app')

@section('page-title', 'Meta Data Dump')

@section('content')

<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
			Meta Data Dump
			<small> - slapdash for Bro</small>
			<div class="pull-right">
				<ol class="breadcrumb">
					<li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
					<li class="active">Structure Data Meta Dump</li>

				</ol>
			</div>
		</h1>
	</div>
</div>

@include('partials.messages')

	<div class="row col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">Paste Meta Data
				<div class="pull-right" style="vertical-align:middle;">
					<span class="glyphicon glyphicon-info-sign fa-1x" data-toggle="tooltip" title="Dump Structure Data" data-placement="left"></span>
				</div>

			</div>
			<div class="panel-body">


				<form method="post" action="/metadata/dump" enctype="multipart/form-data">
					{{ csrf_field() }}
					<div class="form-group row">
						<div class="col-sm-12">
							<textarea name="title" type="text" class="form-control" id="data" placeholder="Go on... throw it on in.." rows="30"></textarea>
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


