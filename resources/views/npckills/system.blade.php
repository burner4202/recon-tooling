@extends('layouts.app')

@section('page-title', 'NPC Kills Intel | Regions')

@section('content')

<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
			{{ $systemDetails->ss_system_name }}
			<small> - NPC Kills Information</small>
			<div class="pull-right">
				<ol class="breadcrumb">
					<li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
					<li><a href="{{ route('intelligence.index') }}">Intelligence Dashboard</a></li>
					<li><a href="{{ route('npc_kills.regions') }}">Regions</a></li>
					<li><a href="{{ route('npc_kills.region', $systemDetails->ss_region_id) }}">{{ $systemDetails->ss_region_name }}</a></li>
					<li class="active">{{ $systemDetails->ss_system_name }}</li>
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
			<div class="panel-heading">Ratting Activity(Last 24 Hours)</div>
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

	<div class="col-md-3">
		<div class="panel panel-default">
			<div class="panel-heading">NPC Information</div>
			<div class="panel-body">
				<div class="table-responsive top-border-table" id="srp-table-wrapper">

					<table class="table" id="systems">
						<thead>
							<th>Date/Time</th>
							<th>NPCs Killed (1h)</th>
						</thead>
						<tbody>

							@if (isset($systems))              
							@foreach($systems as $system)

							<tr>
								<td>{{ $system->updated_at }}</td>
								<td>{{ number_format($system->npc_kills,2) }}</td>	

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
			</div>
		</div>
	</div>

	
</div>



@stop


@section('scripts')
<script>
	$(document).ready(function(){
		$('#systems').DataTable( {
			"paging":   false,
			"pageLength": 500,
			"searching": false,
			"order": [[ 0, "DESC" ]],
		}
		);

	});

	$(document).ready(function(){
		$('#structures').DataTable( {
			"paging":   true,
			"pageLength": 10,
			"searching": false,
		}
		);

	});


	var labels = {!! json_encode(array_keys($systemMetrics24Hours)) !!};
	var npcs = {!! json_encode(array_values($systemMetrics24Hours)) !!};
	
</script>
{!! HTML::script('assets/js/chart.min.js') !!}
{!! HTML::script('assets/js/as/system.ratting.per.day.js') !!}

@stop