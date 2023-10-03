@extends('layouts.app')

@section('page-title', 'Moons | moons')

@section('content')

<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
			2017 Regional Report
			<small> - summary of new eden moons</small>
			<div class="pull-right">
				<ol class="breadcrumb">
					<li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
					<li class="active">2017 Regional Report</li>
				</ol>
			</div>

		</h1>
	</div>
</div>

@include('partials.messages')

<div class="col-md-12">
	<div class="panel panel-default regional-report-chart">

		<div class="panel-heading">
			2017 Regional Report - Goo per Region.

		</div>
		<div class="panel-body chart">

			<div>
				<canvas id="myChart" height="500"></canvas>
			</div>

		</div>
	</div>
</div>

<div class="col-md-12">
	<div class="panel panel-default">
		<div class="panel-heading">Regional Report 2017</div>
		<div class="panel-body">
			<div class="table-responsive top-border-table" id="location-table-wrapper">

				<table class="table" id="regional_stats">
					<thead>
						<th>Region Name</th>
						<th>Total Moons</th>
						<th>Scanned Moons</th>
						<th>R64</th>
						<th>R32</th>
						<th>R16</th>
						<th>R8</th>
						<th>R4</th>
						<th>Regional Value (24 Hour)</th>
						<th>Regional Value (7 Day)</th>
						<th>Regional Value (30 Days)</th>
					</thead>

					<tbody>

						@if (isset($region_stats))              
						@foreach($region_stats as $region)

						<tr>
							<td style="vertical-align: middle"><a href="{{ route('moons.regional_old_view', $region->moon_region_id )}}">{!! $region->moon_region_name !!}</a></td>
							<td style="vertical-align: middle">{!! $region->total_moons !!}</td>
							<td style="vertical-align: middle">{!! $region->scanned_moons !!}</td>
							<td style="vertical-align: middle; text-align: center"><b>{!! $region->r64 !!}</b><br>

								@foreach ($chart_stacked as $region_name => $types_of_goo)
								@if($region->moon_region_name == $region_name)
								@foreach($types_of_goo as $goo_id => $goo_amount)
								@if($goo_id == "16650" || $goo_id == "16651" || $goo_id == "16652"  || $goo_id == "16653")
								<a href="#" target="_blank" title="{!! $goo_amount !!}" data-toggle="tooltip" data-placement="right"><img class="img-circle" src="https://imageserver.eveonline.com/Type/{!! $goo_id !!}_32.png"></a>
								@endif
								@endforeach
								@endif
								@endforeach

							</td>
							<td style="vertical-align: middle; text-align: center"><b>{!! $region->r32 !!}</b><br>

								@foreach ($chart_stacked as $region_name => $types_of_goo)
								@if($region->moon_region_name == $region_name)
								@foreach($types_of_goo as $goo_id => $goo_amount)
								@if($goo_id == "16647" || $goo_id == "16648" || $goo_id == "16646"  || $goo_id == "16649")
								<a href="#" target="_blank" title="{!! $goo_amount !!}" data-toggle="tooltip" data-placement="right"><img class="img-circle" src="https://imageserver.eveonline.com/Type/{!! $goo_id !!}_32.png"></a>
								@endif
								@endforeach
								@endif
								@endforeach

							</td>
							<td style="vertical-align: middle; text-align: center"><b>{!! $region->r16 !!}</b><br>

								@foreach ($chart_stacked as $region_name => $types_of_goo)
								@if($region->moon_region_name == $region_name)
								@foreach($types_of_goo as $goo_id => $goo_amount)
								@if($goo_id == "16643" || $goo_id == "16641" || $goo_id == "16644"  || $goo_id == "16642")
								<a href="#" target="_blank" title="{!! $goo_amount !!}" data-toggle="tooltip" data-placement="right"><img class="img-circle" src="https://imageserver.eveonline.com/Type/{!! $goo_id !!}_32.png"></a>
								@endif
								@endforeach
								@endif
								@endforeach

							</td>
							<td style="vertical-align: middle; text-align: center"><b>{!! $region->r8 !!}</b><br>

								@foreach ($chart_stacked as $region_name => $types_of_goo)
								@if($region->moon_region_name == $region_name)
								@foreach($types_of_goo as $goo_id => $goo_amount)
								@if($goo_id == "16640" || $goo_id == "16639" || $goo_id == "16638"  || $goo_id == "16637")
								<a href="#" target="_blank" title="{!! $goo_amount !!}" data-toggle="tooltip" data-placement="right"><img class="img-circle" src="https://imageserver.eveonline.com/Type/{!! $goo_id !!}_32.png"></a>
								@endif
								@endforeach
								@endif
								@endforeach

							</td>
							<td style="vertical-align: middle; text-align: center"><b>{!! $region->r4 !!}</b><br>

								@foreach ($chart_stacked as $region_name => $types_of_goo)
								@if($region->moon_region_name == $region_name)
								@foreach($types_of_goo as $goo_id => $goo_amount)
								@if($goo_id == "16634" || $goo_id == "16635" || $goo_id == "16633"  || $goo_id == "16636")
								<a href="#" target="_blank" title="{!! $goo_amount !!}" data-toggle="tooltip" data-placement="right"><img class="img-circle" src="https://imageserver.eveonline.com/Type/{!! $goo_id !!}_32.png"></a>
								@endif
								@endforeach
								@endif
								@endforeach

							</td>
							<td style="vertical-align: middle">{!! number_format($region->regional_value_24_hour,2) !!}</td>
							<td style="vertical-align: middle">{!! number_format($region->regional_value_7_day,2) !!}</td>
							<td style="vertical-align: middle">{!! number_format($region->regional_value_30_day,2) !!}</td>
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

@section('styles')
<style>
	.regional-report-chart .chart {
		zoom: 1.235;
	}
</style>
@stop

@section('scripts')

<script>
	var labels = {!! json_encode(array_keys($chart_stacked)) !!};
	var atmo_gases = {!! json_encode(array_column($chart_stacked, '16634')) !!};
	var cadmium = {!! json_encode(array_column($chart_stacked, '16643')) !!};
	var caesium = {!! json_encode(array_column($chart_stacked, '16647')) !!};
	var chromium = {!! json_encode(array_column($chart_stacked, '16641')) !!};
	var cobalt = {!! json_encode(array_column($chart_stacked, '16640')) !!};
	var dysprosium = {!! json_encode(array_column($chart_stacked, '16650')) !!};
	var eva_depo = {!! json_encode(array_column($chart_stacked, '16635')) !!};
	var hafnium = {!! json_encode(array_column($chart_stacked, '16648')) !!};
	var hydrocarbons = {!! json_encode(array_column($chart_stacked, '16633')) !!};
	var mercury = {!! json_encode(array_column($chart_stacked, '16646')) !!};
	var neodymium = {!! json_encode(array_column($chart_stacked, '16651')) !!};
	var platinum = {!! json_encode(array_column($chart_stacked, '16644')) !!};
	var promethium = {!! json_encode(array_column($chart_stacked, '16652')) !!};
	var scandium = {!! json_encode(array_column($chart_stacked, '16639')) !!};
	var silicates = {!! json_encode(array_column($chart_stacked, '16636')) !!};
	var technetium = {!! json_encode(array_column($chart_stacked, '16649')) !!};
	var thulium = {!! json_encode(array_column($chart_stacked, '16653')) !!};
	var titanium = {!! json_encode(array_column($chart_stacked, '16638')) !!};
	var tungsten = {!! json_encode(array_column($chart_stacked, '16637')) !!};
	var vanadium = {!! json_encode(array_column($chart_stacked, '16642')) !!};
</script>
{!! HTML::script('assets/js/chart.min.js') !!}
{!! HTML::script('assets/js/as/regional_stacked.js') !!}
<script>
	$(document).ready(function(){
		$('#regional_stats').DataTable( {
			"paging":   false,
			"searching": true,
			"pageLength": 500,
		}
		);

	});
</script>
@stop