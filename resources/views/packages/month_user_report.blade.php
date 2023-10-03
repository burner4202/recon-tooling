@extends('layouts.app')

@section('page-title', 'Package Manager | Index')

@section('content')

<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
			Package Manager Report
			<small> - monthly report for {!! $month_year !!} for {!! $at_username !!}</small>
			<div class="pull-right">
				<ol class="breadcrumb">
					<li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
					<li><a href="{{ route('package_manager.monthly_index') }}">Package Manager</a></li>
					<li><a href="{{ route('package_manager.month_year_view', $month_year) }}">{!! $month_year !!}</a></li>
					<li class="active">{!! $at_username !!}</li>
				</ol>
			</div>

		</h1>
	</div>
</div>

@include('partials.messages')

<div class="row col-md-12">
	<div class="panel panel-default monthly-report-chart">
		<div class="panel-heading">
			Monthly Report Chart
		</div>
		<div class="panel-body chart">
			<div>
				<canvas id="myChart" height="300"></canvas>
			</div>

		</div>
	</div>
</div>


<div class="row col-md-12">
	<div class="panel panel-default">
		<div class="panel-heading">Package Tracker Monthly Report : {!! $at_username !!} for {!! $month_year !!}</div>
		<div class="panel-body">


			<div class="table-responsive top-border-table" id="location-table-wrapper">

				<table class="table" id="monthly-reports">
					<thead>
						<th>Day</th>
						<th>Delivered</th>
						<th>Removed</th>
						<th>Payment Due</th>
					</thead>

					<tbody>

						@if (isset($results))              
						@foreach($results as $username)

						<tr>
							<td style="vertical-align: middle">{!! $username->day !!}-{!!$month_year!!}</a></td>
							<td style="vertical-align: middle">{!! $username->packages_delivered !!}</td>
							<td style="vertical-align: middle">{!! $username->packages_removed !!}</td>
							<td style="vertical-align: middle">{!! number_format(($username->packages_delivered - $username->packages_removed) * 3000000,2) !!}</td>
							<td></td>
							<td></td>

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
	.monthly-report-chart .chart {
		zoom: 1.235;
	}
</style>
@stop

@section('scripts')

<script>
	var labels = {!! json_encode(array_keys($chart)) !!};
	var packages_delivered = {!! json_encode(array_column($chart, 'packages_delivered')) !!};
	var packages_removed = {!! json_encode(array_column($chart, 'packages_removed')) !!};
</script>
{!! HTML::script('assets/js/chart.min.js') !!}
{!! HTML::script('assets/js/as/package_monthly_stacked.js') !!}
@stop