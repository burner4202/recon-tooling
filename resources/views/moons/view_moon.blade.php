@extends('layouts.app')

@section('page-title', $moon->moon_name)

@section('content')
<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
			2020 : {{ $moon->moon_name }} - (New Data)
			<small></small>
			<div class="pull-right">
				<ol class="breadcrumb">
					<li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
					<li><a href="{{ route('moons.moons') }}">Moons</a></li>
					<li class="active">{{ $moon->moon_name }}</li>
				</ol>
			</div>

		</h1>
	</div>
</div>

@if(isset($structure))
<div class="alert alert-success" role="alert">
	<b><a href="{{ route('structures.view', $structure->str_structure_id_md5) }}" target="_blank">{{ $structure->str_name }}</a></b> (<b>{{ $structure->str_type }}</b>) has been anchored on this moon, belonging to <b>{{ $structure->str_owner_corporation_name }}</b> of <b>{{ $structure->str_owner_alliance_name }}</b>. The structure is currently flagged as <b>{{ $structure->str_state }}</b> and has a fitting value of <b>{{ number_format($structure->str_value,2) }}</b>.<br>
	@if($structure->str_t2_rigged)
	This structure has been recorded as being <b>T2 Rigged</b>.
	@endif
</div>
@else
<div class="alert alert-warning" role="alert">
	There is currently no structure found on this moon, maybe have a think about scanning the system or allocating moon drills to the system moons.
</div>
@endif

<div class="row">
	<div class="col-md-4">
		<div id="edit-user-panel" class="panel panel-default">
			<div class="panel-heading">
				{{ $moon->moon_name }}
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
							<td>System</td>
							<td><a href="{{  route('solar.system', $moon->moon_system_id) }}">{!! $moon->moon_system_name !!}</a></td>
						</tr>
						
						<tr>
							<td>Constellation</td>
							<td><a href="{{  route('solar.constellation', $moon->moon_constellation_id) }}">{!! $moon->moon_constellation_name !!}</a></td>
						</tr>

						<tr>
							<td>Region</td>
							<td><a href="{{  route('solar.region', $moon->moon_region_id) }}">{!! $moon->moon_region_name !!}</a></td>
						</tr>
					</tbody>

					<thead>
						<tr>
							<th colspan="3">Value</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>Rarity</td>
							<td>R{!! $moon->moon_r_rating !!}</td>
						</tr>
						<tr>
							<td>1 Day Extraction</td>
							<td>{!! number_format($moon->moon_value_24_hour,2) !!} isk</td>
						</tr>

						<tr>
							<td>7 Day Extraction</td>
							<td>{!! number_format($moon->moon_value_7_day,2) !!} isk</td>
						</tr>

						<tr>
							<td>30 Day Extraction</td>
							<td>{!! number_format($moon->moon_value_30_day,2) !!} isk</td>
						</tr>
						<tr>
							<td>56 Day Extraction</td>
							<td>{!! number_format($moon->moon_value_56_day,2) !!} isk</td>
						</tr>



						<tr>

						</tr>

						<tr>
							<td>2017 Moon Composition</td>
							<td><a href="{{ route('moons.view_old_moon', $moon->moon_id)}}" target="_blank">Here</a></td>
						</tr>

					</tbody>

				</table>
			</div>
		</div>
	</div>

	<div class="col-md-4">
		<div class="panel panel-default ore-dist-chart">

			<div class="panel-heading">
				Moon Product Distribution<br>
			</div>
			<div class="panel-body chart">

				<div>
					<canvas id="moon_product_chart" height="280"></canvas>
				</div>

			</div>
		</div>
	</div>


	<div class="col-md-4">
		<div class="panel panel-default ore-dist-chart">

			<div class="panel-heading">
				Goo/Mineral Distribution (Per Hour)<br>
			</div>
			<div class="panel-body chart">

				<div>
					<canvas id="moon_mineral_chart" height="280"></canvas>
				</div>

			</div>
		</div>
	</div>




	<div class="col-md-12">
		<div id="edit-user-panel" class="panel panel-default">
			<div class="panel-heading">
				Moon Composition : Values calculated are using The Forge Prices @ Max Refine 89.33%
			</div>
			<div class="panel-body panel-profile">
				<br>
				<table class="table table-hover table-details">
					<thead>

						<tr>
							<td>Moon Product</td>
							<td>Volume</td>
							<td>Distribution</td>
							<td>Portion Size</td>
							<td>Refined Products</td>
							<td>Rarity</td>

							<td>Per Unit</td>
							<td>Value per Unit</td>
							<td>Units per Hour</td>
							<td>Value (1 Hour)</td>
							<td>Value (1 Day)</td>
							<td>Value (30 Days)</td>
						</tr>

					</thead>
					<tbody>





						@foreach (collect(json_decode($moon->moon_dist_ore)) as $type_id => $product)
						<tr>
							<td style= "vertical-align: middle">
								<a href="#" title="{!! $product->name !!} : {!! $product->distribution * 100 !!}%" data-toggle="tooltip" data-placement="right"><img class="img-circle" src="https://imageserver.eveonline.com/Type/{!! $type_id !!}_32.png"></a>
								{!! $product->name !!}
							</td>
							<td style= "vertical-align: middle">
								{!! $product->volume !!} m3
							</td>
							<td style= "vertical-align: middle">
								{!! $product->distribution * 100 !!}%
							</td>
							<td style= "vertical-align: middle">
								{!! $product->portionSize !!}
							</td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							@foreach($product->refined as $refine_id => $refine_product)
							<tr>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td style= "vertical-align: middle"><a href="#" title="{!! $refine_product->name !!}" data-toggle="tooltip" data-placement="right"><img class="img-circle" src="https://imageserver.eveonline.com/Type/{!! $refine_id !!}_32.png"></a>{!! $refine_product->name !!}</td>
								@if($refine_product->r_value > 0)
								<td style= "vertical-align: middle">R{!! $refine_product->r_value !!}</td>
								@else
								<td></td>
								@endif
								<td style= "vertical-align: middle">{!! number_format($refine_product->refine_amount,2) !!}</td>
								<td style= "vertical-align: middle">{!! number_format($refine_product->value_per_unit,2) !!}</td>
								<td style= "vertical-align: middle">{!! number_format($refine_product->refine_amount_per_hour,2) !!}</td>
								<td style= "vertical-align: middle">{!! number_format($refine_product->refine_value_per_hour,2) !!}</td>
								<td style= "vertical-align: middle">{!! number_format($refine_product->refine_1_day,2) !!}</td>
								<td style= "vertical-align: middle">{!! number_format($refine_product->refine_30_days,2) !!}</td>
							</tr>
							@endforeach
						</tr>
						@endforeach



					</tbody>
				</table>


			</div>
		</div>
	</div>
</div>

@stop

@section('styles')
<style>
	.ore-dist-chart .chart {
		zoom: 1.235;
	}
</style>
@stop

@section('scripts')
<script>
	var labels_product = {!! json_encode(array_keys($pie_chart_product)) !!};
	var value_product = {!! json_encode(array_values($pie_chart_product)) !!};
	var chartColours_product = {!! json_encode(array_values($chartColoursProduct)) !!};
	var labels_mineral = {!! json_encode(array_keys($pie_chart_mineral)) !!};
	var value_mineral = {!! json_encode(array_values($pie_chart_mineral)) !!};
	var chartColours_mineral = {!! json_encode(array_values($chartColoursMineral)) !!};
</script>
{!! HTML::script('assets/js/chart.min.js') !!}
{!! HTML::script('assets/js/as/piechart_moon_products.js') !!}
@stop