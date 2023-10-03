@extends('layouts.app')

@section('page-title', $region_name)

@section('content')
<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
			2017 : {{ $region_name }}
			<small> - moon goo ore products, regional metrics</small>
			<div class="pull-right">
				<ol class="breadcrumb">
					<li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
					<li><a href="{{ route('moons.regional_report') }}">2017 Regional Report</a></li>
					<li class="active"> {{ $region_name }}</li>
				</ol>
			</div>

		</h1>
	</div>
</div>

<div class="row col-md-12">
	<div class="col-md-3">
		<div id="edit-user-panel" class="panel panel-default">
			<div class="panel-heading">
				2017 Region Information
			</div>
			<div class="panel-body panel-profile">
				<br>
				<table class="table table-hover table-details">
					<thead>
						<tr>
							<th colspan="3">Information</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>Region</td>
							<td><a href="{{ route('moons.old_moons')}}?search=&system=&constellation=&region={{ $region->moon_region_name }}" data-toggle="tooltip" title="Click Here to See Moon Data" data-placement="right" target="_blank">{!! $region->moon_region_name !!}</a></td>

						</tr>

						<tr>
							<td>Scanned Moons</td>
							<td>{!! $region->scanned_moons !!} / {!! $region->total_moons !!}</td>

						</tr>
						<tr>
							<td>Progress</td>
							<td>{!! number_format($region->scanned_moons / $region->total_moons * 100,2) !!}%</td>

						</tr>


					</tbody>

					<thead>
						<tr>
							<th colspan="3">Moon Rarity Distribution</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>R64</td>
							<td>{{ number_format($region->r64 / $region->total_moons * 100,2) }}% -   {!! $region->r64 !!}</td>
						</tr>
						<tr>
							<td>R32</td>
							<td>{{ number_format($region->r32 / $region->total_moons * 100,2) }}% -   {!! $region->r32 !!}</td>
						</tr>

						<tr>
							<td>R16</td>
							<td>{{ number_format($region->r16 / $region->total_moons * 100,2) }}% -   {!! $region->r16 !!}</td>
						</tr>

						<tr>
							<td>R8</td>
							<td>{{ number_format($region->r8 / $region->total_moons * 100,2) }}% -   {!! $region->r8 !!}</td>
						</tr>

						<tr>
							<td>R4</td>
							<td>{{ number_format($region->r4 / $region->total_moons * 100,2) }}% -   {!! $region->r4 !!}</td>
						</tr>
					</tbody>

					<thead>
						<tr>
							<th colspan="3">Moon Rarity Distribution Value (56) Full Frack</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>R64</td>
							<td>{!! number_format($rarity_value['r64_56_day_value'],2) !!}</td>
						</tr>
						<tr>
							<td>R32</td>
							<td>{!! number_format($rarity_value['r32_56_day_value'],2) !!}</td>
						</tr>

						<tr>
							<td>R16</td>
							<td>{!! number_format($rarity_value['r16_56_day_value'],2) !!}</td>
						</tr>

						<tr>
							<td>R8</td>
						<td>{!! number_format($rarity_value['r8_56_day_value'],2) !!}</td>
						</tr>

						<tr>
							<td>R4</td>
							<td>{!! number_format($rarity_value['r4_56_day_value'],2) !!}</td>
						</tr>
					</tbody>


					<thead>
						<tr>
							<th colspan="3">Regional Extraction Value</th>
						</tr>
					</thead>


					<tbody>
						<tr>
							<td>24 Hours</td>
							<td>{!! number_format($region->regional_value_24_hour,2) !!}</td>
						</tr>

						<tr>
							<td>7 Days</td>
							<td>{!! number_format($region->regional_value_7_day,2) !!}</td>
						</tr>

						<tr>
							<td>30 Days</td>
							<td>{!! number_format($region->regional_value_30_day,2) !!}</td>
						</tr>
						<tr>
							<td>56 Days</td>
							<td>{!! number_format($region->regional_value_24_hour * 56 ,2)  !!}</td>
						</tr>

					</tbody>
				</table>
			</div>
		</div>
	</div>

	<div class="col-md-3">
		<div class="panel panel-default r64_dist_chart">

			<div class="panel-heading">
				R64 Distribution<br>
			</div>
			<div class="panel-body chart">

				<div>
					<canvas id="r64_dist_chart" height="280"></canvas>
				</div>

			</div>
		</div>
	</div>

	<div class="col-md-3">
		<div class="panel panel-default r32_dist_chart">

			<div class="panel-heading">
				R32 Distribution<br>
			</div>
			<div class="panel-body chart">

				<div>
					<canvas id="r32_dist_chart" height="280"></canvas>
				</div>

			</div>
		</div>
	</div>

	<div class="col-md-3">
		<div class="panel panel-default r16_dist_chart">

			<div class="panel-heading">
				R16 Distribution<br>
			</div>
			<div class="panel-body chart">

				<div>
					<canvas id="r16_dist_chart" height="280"></canvas>
				</div>

			</div>
		</div>
	</div>

	<div class="col-md-3">
		<div class="panel panel-default r8_dist_chart">

			<div class="panel-heading">
				R8 Distribution<br>
			</div>
			<div class="panel-body chart">

				<div>
					<canvas id="r8_dist_chart" height="280"></canvas>
				</div>

			</div>
		</div>
	</div>

	<div class="col-md-3">
		<div class="panel panel-default r4_dist_chart">

			<div class="panel-heading">
				R4 Distribution<br>
			</div>
			<div class="panel-body chart">

				<div>
					<canvas id="r4_dist_chart" height="280"></canvas>
				</div>

			</div>
		</div>
	</div>

	<div class="col-md-3">

	</div>


	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">Top Value Moons In the Region (20)</div>
			<div class="panel-body">


				<div class="table-responsive top-border-table" id="location-table-wrapper">

					<table class="table" id="moons">
						<thead>
							<th> @sortablelink('moon_name', 'Moon')</th>
							<th> @sortablelink('moon_constellation_name', 'Constellation')</th>
							<th> @sortablelink('moon_r_rating', 'Rarity')</th>
							<th> Composition</th>
							<th> Percentage</th>
							<th> 56 Day Value</th>
						</thead>

						<tbody>

							@if (isset($top_20_value_2020))              
							@foreach($top_20_value_2020 as $moon)

							<tr>


								<td style="vertical-align: middle"><a href="{{ route('moons.view_moon', $moon->moon_id)}}" title="Click here for a full breakdown of the moon." data-toggle="tooltip" data-placement="top">{{ $moon->moon_name }}</a></td>
								<td style="vertical-align: middle">{!! $moon->moon_constellation_name !!}</a></td>

								@if($moon->moon_r_rating < 4)
								<td></td>

								@else
								<td style="vertical-align: middle">R{!! $moon->moon_r_rating !!}</a></td>
								<td></td>
									<td></td>
									<td></td>
									<td></td>
								@endif
								
								@foreach (collect(json_decode($moon->moon_dist_ore)) as $type_id => $product)

								<tr>
									<td></td>
									<td></td>
									<td></td>
									<td style="vertical-align: middle">
										<a href="#" title="{!! $product->name !!} : {!! $product->distribution * 100 !!}%" data-toggle="tooltip" data-placement="right"><img class="img-circle" src="https://imageserver.eveonline.com/Type/{!! $type_id !!}_32.png"></a>{!! $product->name !!}
									</td>
										<td style="vertical-align: middle">{!! $product->distribution * 100 !!}%</td>		
										<td></td>			
								</tr>
								@endforeach

								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>

								<td style="vertical-align: middle"><b>{!! number_format($moon->moon_value_24_hour * 56,2) !!}</b></a></td>

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
	.r64_dist_chart .chart {
		zoom: 1.235;
	}



	.r32_dist_chart .chart {
		zoom: 1.235;
	}



	.r16_dist_chart .chart {
		zoom: 1.235;
	}



	.r8_dist_chart .chart {
		zoom: 1.235;
	}



	.r4_dist_chart .chart {
		zoom: 1.235;
	}
</style>

@stop

@section('scripts')

<script>
	var r64_labels = {!! json_encode(array_keys($r64)) !!};
	var r64_values = {!! json_encode(array_values($r64)) !!};
	var r64_colours = {!! json_encode(array_values($r64_colours)) !!};
	var r32_labels = {!! json_encode(array_keys($r32)) !!};
	var r32_values = {!! json_encode(array_values($r32)) !!};
	var r32_colours = {!! json_encode(array_values($r32_colours)) !!};
	var r16_labels = {!! json_encode(array_keys($r16)) !!};
	var r16_values = {!! json_encode(array_values($r16)) !!};
	var r16_colours = {!! json_encode(array_values($r16_colours)) !!};
	var r8_labels = {!! json_encode(array_keys($r8)) !!};
	var r8_values = {!! json_encode(array_values($r8)) !!};
	var r8_colours = {!! json_encode(array_values($r8_colours)) !!};
	var r4_labels = {!! json_encode(array_keys($r4)) !!};
	var r4_values = {!! json_encode(array_values($r4)) !!};
	var r4_colours = {!! json_encode(array_values($r4_colours)) !!};
</script>
{!! HTML::script('assets/js/chart.min.js') !!}
{!! HTML::script('assets/js/as/piechart_region_view.js') !!}
@stop