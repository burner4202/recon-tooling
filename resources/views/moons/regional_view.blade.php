@extends('layouts.app')

@section('page-title', $region_name)

@section('content')
<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
			2020 : {{ $region_name }}
			<small> - moon goo ore products, regional metrics</small>
			<div class="pull-right">
				<ol class="breadcrumb">
					<li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
					<li><a href="{{ route('moons.regional_report') }}">2020 Regional Report</a></li>
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
				2020 Region Information
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
							<td><a href="{{ route('moons.moons')}}?search=&system=&constellation=&region={{ $region->moon_region_name }}" data-toggle="tooltip" title="Click Here to See Moon Data" data-placement="right" target="_blank">{!! $region->moon_region_name !!}</a></td>

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
			<div class="panel-heading">Regional Breakdown by System</div>
			<div class="panel-body">
				<div class="table-responsive top-border-table" id="location-table-wrapper">

					<table class="table" id="system_stats">
						<thead>
							<th>System Name</th>
							<th>Constellation Name</th>
							<th>Total Moons</th>
							<th>Scanned Moons</th>
							<th>R64</th>
							<th>R32</th>
							<th>R16</th>
							<th>R8</th>
							<th>R4</th>
							<th>Prom</th>
							<th>Dys</th>
							<th>Neo</th>
							<th>Thul</th>
							<th>Caes</th>
							<th>Tech</th>
							<th>Haf</th>
							<th>Merc</th>
							<th>Cad</th>
							<th>Van</th>
							<th>Chrom</th>
							<th>Plat</th>
							<th>Cobalt</th>
							<th>Scand</th>
							<th>Tit</th>
							<th>Tung</th>
							<th>Atmo Gases</th>
							<th>Evap Dep</th>
							<th>Hydro</th>
							<th>Sili</th>
							<th>System Value (30 Days)</th>
						</thead>

						<tbody>

							@if (isset($system_data))              
							@foreach($system_data as $system)

							<tr>
								<td style="vertical-align: middle"><a href="{{ route('moons.moons')}}?search=&system={{ $system->moon_system_name }}" data-toggle="tooltip" title="Click Here to See Moon Data" data-placement="right" target="_blank">{!! $system->moon_system_name !!}</a></td>
								<td style="vertical-align: middle"><a href="{{ route('moons.moons')}}?search=&constellation={{ $system->moon_constellation_name }}" data-toggle="tooltip" title="Click Here to See Moon Data" data-placement="right" target="_blank">{!! $system->moon_constellation_name !!}</td>
								<td style="vertical-align: middle; text-align: center">{!! $system->total_moons !!}</td>
								<td style="vertical-align: middle; text-align: center">{!! $system->scanned_moons !!}</td>
								@if($system->r64 == 0)
								<td style="vertical-align: middle; text-align: center"></td>
								@else
								<td style="vertical-align: middle; text-align: center">{!! $system->r64 !!}</td>
								@endif

								@if($system->r32 == 0)
								<td style="vertical-align: middle; text-align: center"></td>	
								@else
								<td style="vertical-align: middle; text-align: center">{!! $system->r32 !!}</td>
								@endif

								@if($system->r16 == 0)
								<td style="vertical-align: middle; text-align: center"></td>	
								@else
								<td style="vertical-align: middle; text-align: center">{!! $system->r16 !!}</td>
								@endif

								@if($system->r8 == 0)
								<td style="vertical-align: middle; text-align: center"></td>	
								@else
								<td style="vertical-align: middle; text-align: center">{!! $system->r8 !!}</td>
								@endif

								@if($system->r4 == 0)
								<td style="vertical-align: middle; text-align: center"></td>	
								@else
								<td style="vertical-align: middle; text-align: center">{!! $system->r4 !!}</td>
								@endif

								@if($system->promethium == 0)
								<td style="vertical-align: middle; text-align: center"></td>	
								@else
								<td style="vertical-align: middle; text-align: center">{!! $system->promethium !!}</td>
								@endif

								@if($system->dysprosium == 0)
								<td style="vertical-align: middle; text-align: center"></td>	
								@else
								<td style="vertical-align: middle; text-align: center">{!! $system->dysprosium !!}</td>
								@endif


								@if($system->neodymium == 0)
								<td style="vertical-align: middle; text-align: center"></td>	
								@else
								<td style="vertical-align: middle; text-align: center">{!! $system->neodymium !!}</td>
								@endif


								@if($system->thulium == 0)
								<td style="vertical-align: middle; text-align: center"></td>	
								@else
								<td style="vertical-align: middle; text-align: center">{!! $system->thulium !!}</td>
								@endif


								@if($system->caesium == 0)
								<td style="vertical-align: middle; text-align: center"></td>	
								@else
								<td style="vertical-align: middle; text-align: center">{!! $system->caesium !!}</td>
								@endif


								@if($system->technetium == 0)
								<td style="vertical-align: middle; text-align: center"></td>	
								@else
								<td style="vertical-align: middle; text-align: center">{!! $system->technetium !!}</td>
								@endif


								@if($system->hafnium == 0)
								<td style="vertical-align: middle; text-align: center"></td>	
								@else
								<td style="vertical-align: middle; text-align: center">{!! $system->hafnium !!}</td>
								@endif


								@if($system->mercury == 0)
								<td style="vertical-align: middle; text-align: center"></td>	
								@else
								<td style="vertical-align: middle; text-align: center">{!! $system->mercury !!}</td>
								@endif


								@if($system->cadmium == 0)
								<td style="vertical-align: middle; text-align: center"></td>	
								@else
								<td style="vertical-align: middle; text-align: center">{!! $system->cadmium !!}</td>
								@endif


								@if($system->vanadium == 0)
								<td style="vertical-align: middle; text-align: center"></td>	
								@else
								<td style="vertical-align: middle; text-align: center">{!! $system->vanadium !!}</td>
								@endif


								@if($system->chromium == 0)
								<td style="vertical-align: middle; text-align: center"></td>	
								@else
								<td style="vertical-align: middle; text-align: center">{!! $system->chromium !!}</td>
								@endif


								@if($system->platinum == 0)
								<td style="vertical-align: middle; text-align: center"></td>	
								@else
								<td style="vertical-align: middle; text-align: center">{!! $system->platinum !!}</td>
								@endif


								@if($system->cobalt == 0)
								<td style="vertical-align: middle; text-align: center"></td>	
								@else
								<td style="vertical-align: middle; text-align: center">{!! $system->cobalt !!}</td>
								@endif


								@if($system->scandium == 0)
								<td style="vertical-align: middle; text-align: center"></td>	
								@else
								<td style="vertical-align: middle; text-align: center">{!! $system->scandium !!}</td>
								@endif


								@if($system->titanium == 0)
								<td style="vertical-align: middle; text-align: center"></td>	
								@else
								<td style="vertical-align: middle; text-align: center">{!! $system->titanium !!}</td>
								@endif


								@if($system->tungsten == 0)
								<td style="vertical-align: middle; text-align: center"></td>	
								@else
								<td style="vertical-align: middle; text-align: center">{!! $system->tungsten !!}</td>
								@endif


								@if($system->atmo_gases == 0)
								<td style="vertical-align: middle; text-align: center"></td>	
								@else
								<td style="vertical-align: middle; text-align: center">{!! $system->atmo_gases !!}</td>
								@endif


								@if($system->eva_depo == 0)
								<td style="vertical-align: middle; text-align: center"></td>	
								@else
								<td style="vertical-align: middle; text-align: center">{!! $system->eva_depo !!}</td>
								@endif


								@if($system->hydrocarbons == 0)
								<td style="vertical-align: middle; text-align: center"></td>	
								@else
								<td style="vertical-align: middle; text-align: center">{!! $system->hydrocarbons !!}</td>
								@endif

								@if($system->silicates == 0)
								<td style="vertical-align: middle; text-align: center"></td>	
								@else
								<td style="vertical-align: middle; text-align: center">{!! $system->silicates !!}</td>
								@endif


								@if($system->system_value_30_day == 0)
								<td style="vertical-align: middle; text-align: center"></td>	
								@else
									<td style="vertical-align: middle; text-align: center">{!! number_format($system->system_value_30_day,2) !!}</td>
								@endif


							
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

	$(document).ready(function(){
		$('#system_stats').DataTable( {
			"paging":   false,
			"searching": true,
			"pageLength": 500,
		}
		);

	});

</script>


{!! HTML::script('assets/js/chart.min.js') !!}
{!! HTML::script('assets/js/as/piechart_region_view.js') !!}
@stop