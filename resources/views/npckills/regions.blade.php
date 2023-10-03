@extends('layouts.app')

@section('page-title', 'NPC Kills Intel | Region')

@section('content')

<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
			NPC Kills 
			<small> - all regions</small>
			<div class="pull-right">
				<ol class="breadcrumb">
					<li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
					<li><a href="{{ route('intelligence.index') }}">Intelligence</a></li>
					<li class="active">NPC Kills</li>
				</ol>
			</div>

		</h1>
	</div>
</div>

@include('partials.messages')


<div class="row tab-search">
	<div class="col-md-5"></div>
</div>

<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default weekly-report-chart">
			<div class="panel-heading">NPC Rats Killed Per Region, Last 24 Hours.</div>
			<div class="panel-body chart">
				<div>
					<canvas id="myChart" height="400"></canvas>
				</div>
			</div>
		</div>
	</div>
</div>




<div class="table-responsive top-border-table" id="srp-table-wrapper">

	<table class="table" id="regions">
		<thead>
			<th>Region</th>
			<th>NPCs Killed (1h)</th>
			<th>NPCs Killed (24h)</th>
		</thead>
		<tbody>

			@if (isset($allRegions))              
			@foreach($allRegions as $region)

			<tr>
				<td><a href="{{ route('npc_kills.region', $region['region_id']) }}">{{ $region['region_name'] }}</a></td>
				<td>{{ number_format($region['kills_past_hour'],2) }}</td>
				<td>{{ number_format($region['kills_past_24_hours'],2) }}</td>	
				
			</tr>

			@endforeach
			@else

			<tr>
				<td colspan="6"><em>No Records Found</em></td>
			</tr>

			@endif

		</tbody>
	</table>
</div>
@stop

@section('styles')
<style>
	.regional-report-chart .chart {
		zoom: 1.235;
	}
</style>
@stop


@section('scripts')
<script>
	$(document).ready(function(){
		$('#regions').DataTable( {
			"paging":   false,
			"searching": true,
			"pageLength": 500,
			"order": [[ 1, "DESC" ]],
		}
		);

	});


	var labels = {!! json_encode(array_keys($allRegionMetrics)) !!};
	var npcs = {!! json_encode(array_values($allRegionMetrics)) !!};
</script>
{!! HTML::script('assets/js/chart.min.js') !!}
{!! HTML::script('assets/js/as/ratting.per.hour.js') !!}

@stop

