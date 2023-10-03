@extends('layouts.app')

@section('page-title', 'Capital Tracking')

@section('content')


<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
			Capital Tracking
			<small>- parse killmails to find titan/supers/capitals, each hull is allocated to that character and updated weekly, to allow for alliance changes.</small>
			<div class="pull-right">
				<ol class="breadcrumb">
					<li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
					<li class="active">Capital Tracking</li>
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

	<div class="col-md-3">
		<div class="panel panel-default">

			<div class="panel-heading">
				Summary of Stats
			</div>
			<div class="panel-body">
				<table class="table" id="statistics-details">
					<thead>
						<tr>
							<th>Statistics</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>Total No of Parsed Killmails</td>
							<td style="vertical-align: middle">{!! count($killmails) !!}</td>
						</tr>
						<tr>
							<td>Titan Pilots</td>
							<td style="vertical-align: middle">{!! $characters->where('titan', 1)->count() !!}</td>
						</tr>
						<tr>
							<td>Faction Titan Pilots</td>
							<td style="vertical-align: middle">{!! $characters->where('faction_titan', 1)->count() !!}</td>
						</tr>
						<tr>
							<td>Supercarrier Pilots</td>
							<td style="vertical-align: middle">{!! $characters->where('super', 1)->count() !!}</td>
						</tr>
						<tr>
							<td>Faction Supercarrier Pilots</td>
							<td style="vertical-align: middle">{!! $characters->where('faction_super', 1)->count() !!}</td>
						</tr>
						<tr>
							<td>Carrier Pilots</td>
							<td style="vertical-align: middle">{!! $characters->where('carrier', 1)->count() !!}</td>
						</tr>
						<tr>
							<td>Force Axuillary Pilots</td>
							<td style="vertical-align: middle">{!! $characters->where('fax', 1)->count() !!}</td>
						</tr>

						<tr>
							<td>Dreadnought Pilots</td>
							<td style="vertical-align: middle">{!! $characters->where('dread', 1)->count() !!}</td>
						</tr>
						<tr>
							<td>Faction Dreadnought Pilots</td>
							<td style="vertical-align: middle">{!! $characters->where('faction_dread', 1)->count() !!}</td>
						</tr>

						<tr>
							<td>Monitor</td>
							<td style="vertical-align: middle">{!! $characters->where('monitor', 1)->count() !!}</td>
						</tr>



					</tbody>
				</table>
			</div>
		</div>


	</div>

	<div class="col-md-3">
		<div class="panel panel-default">
			<div class="panel-heading">Previously Submitted Killmails.</div>
			<div class="panel-body">
				<div class="col-md-12">

					<div class="table-responsive top-border-table" id="location-table-wrapper">

						<table class="table" id="previous-kills">
							<thead>
								<th> Killmail ID</th>
								<th> Added</th>
							</thead>

							<tbody>

								@if (isset($previous_killmails))              
								@foreach($previous_killmails as $killmail)

								<tr>
									<td style="vertical-align: middle"><a href="https://zkillboard.com/kill/{!!  $killmail->killmail_id !!}" target="_blank">{!! $killmail->killmail_id !!}</a></td>

									<td style="vertical-align: middle">{!! \Carbon\Carbon::parse($killmail->created_at)->diffForHumans() !!} </td>

								</tr>

								@endforeach
								@else

								<tr>
									<td colspan="6"><em>No Records Found</em></td>
								</tr>

								@endif


								{!! $previous_killmails->render() !!}

							</tbody>

						</table>

					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-3">
		<div class="panel panel-default">
			<div class="panel-heading">Parse it & Find Titan/Super Pilots.</div>
			<div class="panel-body">
				<div class="col-md-12">

					<form method="post" action="/intelligence/capital/killmail/post" enctype="multipart/form-data">
						{{ csrf_field() }}
						<div class="form-group row">
							<div class="col-sm-12">
								<label for="killmail">Killmail Link</label>
								<input name="killmail_link" type="text" class="form-control" id="killmail_link" placeholder="Paste It Baby" autocomplete="false"></input>
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
</div>

<div class="col-md-12">
	<div class="panel panel-default hull-chart">

		<div class="panel-heading">
			Hull Distribution per Alliance
		</div>
		<div class="panel-body chart">

			<div>
				<canvas id="myChart" height="460"></canvas>
			</div>

		</div>
	</div>
</div>

<div class="col-md-12">
	<div class="panel panel-default">
		<div class="panel-heading">Alliance Report</div>
		<div class="panel-body">
			<div class="table-responsive top-border-table" id="location-table-wrapper">

				<table class="table" id="hull_stats">
					<thead>
						<th>Alliance Name</th>
						<th>Titans</th>
						<th>Faction Titans</th>
						<th>Supercarriers</th>
						<th>Faction Supercarriers</th>
						<th>Carriers</th>
						<th>Force Auxillaries</th>
						<th>Dreadnoughts</th>
						<th>Faction Dreadnoughts</th>
						<th>Monitor</th>
					</thead>

					<tbody>

						@if (isset($hull_count))     

						@foreach($hull_count as $hull)
				

						<tr>
							@if($hull->character_alliance_name == "")
							<td style="vertical-align: middle">AAA Not Part of Any Alliance</a></td>
							@else
							<td style="vertical-align: middle"><a href="{{ route('killmail.view_alliance', $hull->character_alliance_name) }}">{!! $hull->character_alliance_name !!}</a></td>
							@endif
							<td style="vertical-align: middle">{!! $hull->titan !!}</a></td>
							<td style="vertical-align: middle">{!! $hull->faction_titan !!}</td>
							<td style="vertical-align: middle">{!! $hull->supercarrier !!}</td>
							<td style="vertical-align: middle">{!! $hull->faction_supercarrier !!}</td>
							<td style="vertical-align: middle">{!! $hull->carrier !!}</td>
							<td style="vertical-align: middle">{!! $hull->fax !!}</td>
							<td style="vertical-align: middle">{!! $hull->dread !!}</td>
							<td style="vertical-align: middle">{!! $hull->faction_dread !!}</td>
							<td style="vertical-align: middle">{!! $hull->monitor !!}</td>
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

@stop

@section('styles')
<style>
	.hull-chart .chart {
		zoom: 1.235;
	}
</style>
@stop

@section('scripts')
<script>
	var labels = {!! json_encode(array_keys($chart_stacked)) !!};
	var titan = {!! json_encode(array_column($chart_stacked, 'titan')) !!};
	var faction_titan = {!! json_encode(array_column($chart_stacked, 'faction_titan')) !!};
	var supercarrier = {!! json_encode(array_column($chart_stacked, 'supercarrier')) !!};
	var faction_supercarrier = {!! json_encode(array_column($chart_stacked, 'faction_supercarrier')) !!};
	var carrier = {!! json_encode(array_column($chart_stacked, 'carrier')) !!};
	var fax = {!! json_encode(array_column($chart_stacked, 'fax')) !!};
	var dread = {!! json_encode(array_column($chart_stacked, 'dread')) !!};
	var faction_dread = {!! json_encode(array_column($chart_stacked, 'faction_dread')) !!};
	var monitor = {!! json_encode(array_column($chart_stacked, 'monitor')) !!};
</script>
{!! HTML::script('assets/js/chart.min.js') !!}
{!! HTML::script('assets/js/as/hull_stacked.js') !!}

<script>
	$(document).ready(function(){
		$('#hull_stats').DataTable( {
			"paging":   false,
			"searching": true,
			"pageLength": 500,
		}
		);

	});
</script>
@stop

