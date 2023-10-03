@extends('layouts.app')

@section('page-title', 'NPC Kills Intel | Regions')

@section('content')

<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
			{{ $region->ss_region_name }}
			<small> - NPC Kills Information</small>
			<div class="pull-right">
				<ol class="breadcrumb">
					<li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
					<li><a href="{{ route('intelligence.index') }}">Intelligence Dashboard</a></li>
					<li><a href="{{ route('npc_kills.regions') }}">Regions</a></li>

					<li class="active">{{ $region->ss_region_name }}</li>
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
	<div class="col-md-9">
		<div class="panel panel-default">
			<div class="panel-heading">Region Ratting per Hour (Last 24 Hours)</div>
			<div class="panel-body">
				<div>
					<canvas id="myChart" height="400"></canvas>
				</div>
			</div>
		</div>
	</div>

	<div class="col-md-3">
		<div class="panel panel-default">
			<div class="panel-heading">Top Ratting Times (3 Days)</div>
			<div class="panel-body">
				@if (count($rattingSchedule))
				<div class="list-group">
					@foreach ($rattingSchedule as $time)
					<a href="#" class="list-group-item">
						{{ $time->updated_at }}
						<span class="list-time ">
							{{ $time->npc_kills}}<br>
						</span>
					</a>
					@endforeach
				</div>
				@else
				<p class="text-muted">@lang('app.no_records_found')</p>
				@endif
			</div>
		</div>
	</div>
</div>

<div class="table-responsive top-border-table" id="srp-table-wrapper">

	<table class="table" id="systems">
		<thead>
			<th>System</th>
			<th>Constellation</th>
			<th>Region</th>
			<th>NPCs Killed (1h)</th>
			<th>NPCs Killed (24h)</th>
		</thead>
		<tbody>

			@if (isset($allSystems))              
			@foreach($allSystems as $system)

			<tr>
				<td><a href="{{ route('npc_kills.system', $system['system_id']) }}">{{ $system['system_name'] }}</a></td>
				<td><a href="http://evemaps.dotlan.net/map/{{ $system['region_id']}}/{{$system['constellation_name']}}/" target="_blank">{{ $system['constellation_name'] }}</td>
					<td><a href="http://evemaps.dotlan.net/map/{{ $system['region_id']}}/" target="_blank">{{ $system['region_name'] }}</td>
						<td>{{ number_format($system['kills_past_hour'],2) }}</td>
						<td>{{ number_format($system['kills_past_24_hours'],2) }}</td>	

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


		@section('scripts')
		<script>
			$(document).ready(function(){
				$('#systems').DataTable( {
					"paging":   false,
					"searching": true,
					"pageLength": 500,
					"order": [[ 3, "DESC" ]],
				}
				);

			});


			var labels = {!! json_encode(array_keys($regionMetrics24Hours)) !!};
			var npcs = {!! json_encode(array_values($regionMetrics24Hours)) !!};
			var npcs48hour = {!! json_encode(array_values($regionMetrics48Hours)) !!};
		</script>
		{!! HTML::script('assets/js/chart.min.js') !!}
		{!! HTML::script('assets/js/as/ratting.7.day.js') !!}

		@stop