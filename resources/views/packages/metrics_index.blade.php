@extends('layouts.app')

@section('page-title', 'Package Manager | Index')

@section('content')

<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
			Package Manager Metrics
			<small> - summary of package activity, monthly reports</small>
			<div class="pull-right">
				<ol class="breadcrumb">
					<li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
					<li class="active">Package Manager</li>
					<li class="active">Metrics</li>
				</ol>
			</div>

		</h1>
	</div>
</div>

@include('partials.messages')


<div class="row col-md-12">
	<div class="col-md-4">
		<div class="panel panel-default">
			<div class="panel-heading">Package Tracker, Monthly Reports</div>
			<div class="panel-body">


				<div class="table-responsive top-border-table" id="location-table-wrapper">

					<table class="table" id="monthly-reports">
						<thead>
							<th>Year - Month</th>
							<th>Delivered</th>
							<th>Removed</th>
							<th>Paid</th>
							<th>Amount</th>

						</thead>

						<tbody>

							@if (isset($months))              
							@foreach($months as $month)

							<tr>
								<td style="vertical-align: middle"><a href="{{ route('package_manager.month_year_view', $month->month . "-" . $month->year) }}">{!! $month->year !!} - {!! $month->month !!}</a></td>
								<td style="vertical-align: middle">{!! $month->packages_delivered !!}</td>
								<td style="vertical-align: middle">{!! $month->packages_removed !!}</td>

								<td style="vertical-align: middle">
									@foreach($paid as $p) 
									@if($p->month_year == ($month->month . "-" . $month->year))
																	
									
									@if($p->paid == 1)
									<a href="#" class="label label-success" data-toggle="tooltip" data-placement="top">
										<span>Paid</span>
									</a>
									@else
									<a href="#" class="label label-warning" data-toggle="tooltip" data-placement="top">
										<span >Outstanding</span>
									</a>
									@endif

								

									@endif
									@endforeach
								</td>
								<td style="vertical-align: middle">{!! number_format(($month->packages_delivered - $month->packages_removed) * 3000000,2) !!}</td>


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
	var packages_delivered = {!! json_encode(array_column($chart, 'packages_delivered')) !!};
	var packages_removed = {!! json_encode(array_column($chart, 'packages_removed')) !!};
</script>
{!! HTML::script('assets/js/chart.min.js') !!}
{!! HTML::script('assets/js/as/package_monthly_stacked.js') !!}
@stop