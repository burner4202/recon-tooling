@extends('layouts.app')

@section('page-title', 'Universe ')

@section('content')

<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
			Regions
			<small> - all regions</small>
			<div class="pull-right">
				<ol class="breadcrumb">
					<li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
					<li class="active">Regions</li>
				</ol>
			</div>

		</h1>
	</div>
</div>

@include('partials.messages')

<div class="col-md-12">
	@foreach($regions as $region)
	<div class="col-md-2">
		<div class="panel-heading"><a href="{{ route('moons.systems', $region->moon_region_id )}}">{{ $region->moon_region_name }}</a></div>
		
	</div>
	@endforeach
</div>
@stop

