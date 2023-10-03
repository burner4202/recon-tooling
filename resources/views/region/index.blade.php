@extends('layouts.app')

@section('page-title', 'Regional Reports')

@section('content')


<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
			Regions 
			<small>- select a region for its report.</small>
			<div class="pull-right">
				<ol class="breadcrumb">
					<li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
					<li class="active">Region Report Index</li>
				</ol>
			</div>
		</h1>
	</div>
</div>


@include('partials.messages')


<div class="row tab-search">
	<div class="col-md-5"></div>
</div>

<div class="row col-md-12">

	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">Regions</div>
			<div class="panel-body">




				@if (isset($regions))              
				@foreach($regions as $region)


				<div class="col-md-2">
					<a href="{{ route('regional.report.view', $region->str_region_name)}}" class="panel-link" data-toggle="tooltip">
						<div class="panel panel-default dashboard-panel">
							<div class="panel-body">
								<p class="lead">{!! $region->str_region_name !!}</p>
							</div>
						</div>
					</a>
				</div>

				@endforeach
				@endif





			</div>
		</div>
	</div>
</div>







@stop


