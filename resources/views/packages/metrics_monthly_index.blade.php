@extends('layouts.app')

@section('page-title', 'Package Manager | Index')

@section('content')

<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
			Package Manager Report
			<small> - monthly report for {!! $month_year !!}</small>
			<div class="pull-right">
				<ol class="breadcrumb">
					<li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
					<li><a href="{{ route('package_manager.monthly_index') }}">Package Manager</a></li>
					<li class="active">{!! $month_year !!}</li>
				</ol>
			</div>

		</h1>
	</div>
</div>

@include('partials.messages')

@permission('package.monthly.report.manage')
@if(isset($is_paid))
<div class="row col-md-12">
	<div class="alert alert-success" role="alert">
		<b>{!! $month_year !!}</b> has been marked as paid.
		

	</div>
</div>
@else
<div class="row col-md-12">
	<div class="alert alert-warning" role="alert">
		<b>{!! $month_year !!}</b> has not been paid.
		<a href="{{ route('package_manager.mark_month_as_paid', $month_year) }}" class="pull-right" id="paid">
			Mark Month as Paid
		</a>
	</div>	
</div>
@endif
@endpermission


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
		<div class="panel-heading">Package Tracker Monthly Report for {!! $month_year !!}
			@permission('package.monthly.report.manage')
			<a href="{{ route('package_manager.export_monthly_stats', $month_year) }}" class="pull-right" id="export_csv">
				Export to Excel
			</a>
			@endpermission
		</div>
		<div class="panel-body">


			<div class="table-responsive top-border-table" id="location-table-wrapper">

				<table class="table" id="monthly-reports">
					<thead>
						<th>Username</th>
						<th>Delivered</th>
						<th>Removed</th>
						<th>Reward</th>
					</thead>

					<tbody>

						@if (isset($results))              
						@foreach($results as $username)

						<tr>
							<td style="vertical-align: middle"><a href="{{ route('package_manager.month_year_user_view', [$month_year, $username->at_username]) }}">{!! $username->at_username !!}</a></td>
							<td style="vertical-align: middle">{!! $username->packages_delivered !!}</td>
							<td style="vertical-align: middle">{!! $username->packages_removed !!}</td>
							<td style="vertical-align: middle">{!! number_format(($username->packages_delivered - $username->packages_removed) * 3000000,2) !!}</td>
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