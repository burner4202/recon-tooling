@extends('layouts.app')

@section('page-title', 'Activity Structure Report | Index')

@section('content')

<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
			Activity Structure Report
			<small> - summary of activity, monthly reports</small>
			<div class="pull-right">
				<ol class="breadcrumb">
					<li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
					<li class="active">Activity Structure Report</li>
				</ol>
			</div>

		</h1>
	</div>
</div>

@include('partials.messages')


<div class="row col-md-12">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">Activity Tracker, Monthly Reports</div>
			<div class="panel-body">


				<div class="table-responsive top-border-table" id="location-table-wrapper">

					<table class="table" id="monthly-reports">
						<thead>

							<th>Year - Month</th>
							<th>Total Activity</th>
							<th>Stored Meta Data</th>
							<th>Destroyed</th>
							<th>Has Fit</th>
							<th>Stored Fitting</th>
							<th>No Fitting</th>
							<th>Reinforced Armor</th>
							<th>Reinforced Hull</th>
							<th>Anchoring</th>
							<th>High Power</th>
							<th>Low Power</th>
							<th>Reinforced</th>
							<th>Unanchoring</th>
							<th>Cleared</th>
							<th>Delivered Package</th>
							<th>Removed Package</th>

						</thead>

						<tbody>

							@if (isset($months))              
							@foreach($months as $month)

							<tr>
								<td style="vertical-align: middle"><a href="{{ route('activitytracker.metrics_monthly_index', $month->month . "-" . $month->year) }}">{!! $month->year !!} - {!! $month->month !!}</a></td>
								<td style="vertical-align: middle">{!! $month->activity !!}</td>
								<td style="vertical-align: middle">{!! $month->structure_meta_data_added !!}</td>
								<td style="vertical-align: middle">{!! $month->structure_destroyed !!}</td>
								<td style="vertical-align: middle">{!! $month->structure_has_fit !!}</td>
								<td style="vertical-align: middle">{!! $month->structure_fitting_stored !!}</td>
								<td style="vertical-align: middle">{!! $month->structure_has_no_ftting !!}</td>
								<td style="vertical-align: middle">{!! $month->structure_reinforced_armor !!}</td>
								<td style="vertical-align: middle">{!! $month->structure_reinforced_hull !!}</td>
								<td style="vertical-align: middle">{!! $month->structure_anchoring !!}</td>
								<td style="vertical-align: middle">{!! $month->structure_high_power !!}</td>
								<td style="vertical-align: middle">{!! $month->structure_low_power !!}</td>
								<td style="vertical-align: middle">{!! $month->structure_reinforced !!}</td>
								<td style="vertical-align: middle">{!! $month->structure_unanchoring !!}</td>
								<td style="vertical-align: middle">{!! $month->structure_status_clear !!}</td>
								<td style="vertical-align: middle">{!! $month->packages_delivered !!}</td>
								<td style="vertical-align: middle">{!! $month->packages_removed !!}</td>

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

	<div class="col-md-12">
		<div class="panel panel-default monthly-report-chart">

			<div class="panel-heading">
				Monthly Report Chart

			</div>
			<div class="panel-body chart">

				<div>
					<canvas id="myChart" height="400"></canvas>
				</div>

			</div>

		</div>

	</div>
</div>



@stop

@section('styles')
<style>
	.monthly-report-chart .chart {
		zoom: 1.235;
	}
</style>
@stop

@section('scripts')

<script>
	var labels = {!! json_encode(array_keys($chart)) !!};
	var structure_meta_data_added = {!! json_encode(array_column($chart, 'structure_meta_data_added')) !!};
	var structure_destroyed = {!! json_encode(array_column($chart, 'structure_destroyed')) !!};
	var structure_has_fit = {!! json_encode(array_column($chart, 'structure_has_fit')) !!};
	var structure_fitting_stored = {!! json_encode(array_column($chart, 'structure_fitting_stored')) !!};
	var structure_has_no_ftting = {!! json_encode(array_column($chart, 'structure_has_no_ftting')) !!};
	var structure_reinforced_armor = {!! json_encode(array_column($chart, 'structure_reinforced_armor')) !!};
	var structure_reinforced_hull = {!! json_encode(array_column($chart, 'structure_reinforced_hull')) !!};
	var structure_anchoring = {!! json_encode(array_column($chart, 'structure_anchoring')) !!};
	var structure_high_power = {!! json_encode(array_column($chart, 'structure_high_power')) !!};
	var structure_low_power = {!! json_encode(array_column($chart, 'structure_low_power')) !!};
	var structure_reinforced = {!! json_encode(array_column($chart, 'structure_reinforced')) !!};
	var structure_unanchoring = {!! json_encode(array_column($chart, 'structure_unanchoring')) !!};
	var structure_status_clear = {!! json_encode(array_column($chart, 'structure_status_clear')) !!};
	var packages_delivered = {!! json_encode(array_column($chart, 'packages_delivered')) !!};
	var packages_removed = {!! json_encode(array_column($chart, 'packages_removed')) !!};
</script>
{!! HTML::script('assets/js/chart.min.js') !!}
{!! HTML::script('assets/js/as/activity_monthly_stacked.js') !!}
@stop

			