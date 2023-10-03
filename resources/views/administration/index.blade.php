@extends('layouts.app')

@section('page-title', trans('app.dashboard'))

@section('content')

<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
			Administration Dashboard
			<div class="pull-right">
				<ol class="breadcrumb">
					<li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
					<li class="active">Administration Dashboard</li>
				</ol>
			</div>
		</h1>
	</div>
</div>

@include('partials.messages')

<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				Administration Dashboard
			</div>
			<div class="panel-body">
				This section of the tools allows for back end admnistration, mainly for recon directors to manage the recon dataset & other tools.<br>
				Each module is detailed with a brief description of the functionality.
			</div>
		</div>
	</div>
	
	<div class="col-md-12">
		@permission('augswarm.tracking')
		<div class="col-md-2">
			<a href="{{ route('augswarm.index') }}" class="panel-link" data-toggle="tooltip">
				<div class="panel panel-default dashboard-panel">
					<div class="panel-body">
						<div class="icon">
							<i class="fa fa-space-shuttle"></i>
						</div>
						<p class="lead">Augswarm Management</p>
						<small class="text-muted text-center">

							<p>This module allows for the management of Augswarms</p>

							<p></p>
						</small>
					</div>
				</div>
			</a>
		</div>
		@endpermission
	</div>
</div>




@stop

@section('styles')
{{ Html::style('css/intel.css') }}
@stop

