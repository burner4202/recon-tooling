@extends('layouts.app')

@section('page-title', 'API Log | Index')

@section('content')

<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
			API Calls Metrics
			<small> - summary of api activity, monthly reports</small>
			<div class="pull-right">
				<ol class="breadcrumb">
					<li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
					<li class="active">API Log</li>
				</ol>
			</div>

		</h1>
	</div>
</div>

@include('partials.messages')


<div class="row col-md-12">
	<div class="col-md-4">
		<div class="panel panel-default">
			<div class="panel-heading">API Calls, Monthly Reports</div>
			<div class="panel-body">


				<div class="table-responsive top-border-table" id="location-table-wrapper">

					<table class="table" id="monthly-reports">
						<thead>
							<th>Year - Month</th>
							<th>API Calls</th>
							<th>Success</th>
							<th>Error</th>
							<th>Unauthorized</th>

						</thead>
						<tbody>

							@if (isset($months))              
							@foreach($months as $month)

							<tr>
								<td style="vertical-align: middle"><a href="#">{!! $month->year !!} - {!! $month->month !!}</a></td>
								<td style="vertical-align: middle">{!! $month->calls !!}</td>
								<td style="vertical-align: middle">{!! $month->success !!}</td>
								<td style="vertical-align: middle">{!! $month->error !!}</td>
								<td style="vertical-align: middle">{!! $month->unauthorized !!}</td>
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

	<div class="col-md-8">
		<div class="panel panel-default monthly-report-chart">

			<div class="panel-heading">
				Monthly API Report

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
	var calls = {!! json_encode(array_column($chart, 'calls')) !!};
	var success = {!! json_encode(array_column($chart, 'success')) !!};
	var error = {!! json_encode(array_column($chart, 'error')) !!};
	var unauthorized = {!! json_encode(array_column($chart, 'unauthorized')) !!};
</script>
{!! HTML::script('assets/js/chart.min.js') !!}
{!! HTML::script('assets/js/as/api_calls_stacked.js') !!}
@stop