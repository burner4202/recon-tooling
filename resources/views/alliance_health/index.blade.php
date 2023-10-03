@extends('layouts.app')

@section('page-title', 'Intelligence | Alliance Health Index')

@section('content')

<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
			Alliance Health Index
			<div class="pull-right">
				<ol class="breadcrumb">
					<li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
					<li><a href="{{ route('intelligence.index') }}">Intelligence Dashboard</a></li>
					<li class="active">Alliance Health Index</li>
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
				Alliance Health
			</div>
			<div class="panel-body">
				The information below shows the “realistic” relationship between an alliance and the space it owns as a function of overextension and condensation or otherwise defined as Empire Sprawl.<p>
				Each alliance's health index is calculated daily and shown under the alliance tab</p>
			</div>
		</div>
	</div>
</div>


<div class="row tab-search">
	<div class="col-md-5"></div>
</div>

<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">Alliance Report</div>
			<div class="panel-body">
				<div class="table-responsive top-border-table" id="location-table-wrapper">

					<table class="table" id="alliance_health">
						<thead>
							<th>Alliance Name</th>
							<th>Health</th>
							<th>Average ADM</th>
							<th>Infrastructure Hubs</th>
						</thead>

						<tbody>

							@if (count($health))     

							@foreach($health as $alliance)
							
							<tr>
								<td style="vertical-align: middle"><a href="{{ route('alliance_health.view', $alliance->alliance_id)}}"><img class="img-circle" src="https://images.evetech.net/alliances/{{ $alliance->alliance_id }}/logo?size=32">&nbsp;{{ $alliance->alliance_name }} ({{ $alliance->alliance_ticker }})</a></td>
								<td style="vertical-align: middle">{{ number_format($alliance->health,2) }}%</td>
								<td style="vertical-align: middle">{{ number_format($alliance->average_adm,2) }}</td>
								<td style="vertical-align: middle">{{ $alliance->ihub_count }}</td>
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
		$('#alliance_health').DataTable( {
			"paging":   false,
			"searching": true,
			"pageLength": 500,
		}
		);

	});
</script>
@stop

