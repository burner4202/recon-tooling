@extends('layouts.app')

@section('page-title', 'Price History')

@section('content')

<div class="row">
	<div class="col-lg-12">
		<h2 class="page-header">
			<img class="img-circle" src="https://image.eveonline.com/Type/{{ $moduleInformation->upm_type_id }}_32.png"> {{ $moduleInformation->upm_name }}
			<small> - 6 months of pricing history. </small>
			<div class="pull-right">
				<ol class="breadcrumb">
					<li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
					<li><a href="{{ route('upwell.modules') }}">Upwell Modules</a></li>
					<li class="active">{{ $moduleInformation->upm_name }}</li>
				</ol>
			</div>
		</h2>
	</div>
</div>


<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default salvage-chart">
			<div class="panel-heading">6 Month History for {{ $moduleInformation->upm_name }}</div>
			<div class="panel-body chart">
				<div>
					<canvas id="myChart" height="500"></canvas>
				</div>
			</div>
		</div>
	</div>
</div>

@stop

@section('styles')
<style>
	.salvage-chart .chart {
		zoom: 1.235;
	}
</style>
@stop

@section('scripts')
<script>
	var labels = {!! json_encode(array_keys($historyLowest)) !!};
	var lowest = {!! json_encode(array_values($historyLowest)) !!};
	var average = {!! json_encode(array_values($historyAverage)) !!};
	var highest = {!! json_encode(array_values($historyHighest)) !!};
	var volume = {!! json_encode(array_values($historyVolume)) !!};
</script>
{!! HTML::script('assets/js/chart.min.js') !!}
{!! HTML::script('assets/js/as/salvage.history.js') !!}
@stop