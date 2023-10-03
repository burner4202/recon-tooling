@extends('layouts.app')

@section('page-title', $rig->name)

@section('content')

@inject('typeIDHelper', 'Vanguard\Http\Controllers\Web\UpwellRigsController')

<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
			{{ $rig->name }}
			<small></small>
			<div class="pull-right">
				<ol class="breadcrumb">
					<li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
					<li><a href="{{ route('upwell.rigs') }}">Upwell Rigs</a></li>
					<li class="active">{{ $rig->name }}</li>
				</ol>
			</div>

		</h1>
	</div>
</div>

<div class="row">
	<div class="col-md-4">
		<div id="edit-user-panel" class="panel panel-default">
			<div class="panel-heading">
				<img class="img-circle" src="https://image.eveonline.com/Type/{{ $rig->type_id }}_32.png">{{ $rig->name }}</a>
			</div>
			<div class="panel-body panel-profile">
				<br>
				<table class="table table-hover table-details">
					<thead>
						<tr>
							<th>Information</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>Description</td>
							<td>{!! $rig->description !!}</td>
						</tr>	

						<tr>
							<td>Note</td>
							<td>Manufacture Values based on Fuzzworks Calculation Sheets, Medium Structure, In Null with T2 Rigs Time/Material ME10/TE10</td>
						</tr>	
				
						<tr>
							<td>Last Updated</td>
							<td>{{ $rig->updated_at }} : {{ \Carbon\Carbon::parse($rig->updated_at)->diffForHumans() }}</td>
						</tr>	

					</tbody>
				</table>

				
			</div>
		</div>
	</div>

	<div class="col-md-4">
		<div id="edit-user-panel" class="panel panel-default">
			<div class="panel-heading">
				<img class="img-circle" src="https://image.eveonline.com/Type/{{ $rig->type_id }}_32.png">{{ $rig->name }}</a>

			</div>
			<div class="panel-body panel-profile">
				<table class="table table-hover table-details">
					
					<thead>

						<tr>
							<th>Build Materials</th>
							<th>Amount</th>
							<th>Item Value</th>
							<th>Total Cost</th>
						</tr>
					</thead>
					<tbody>				

						@foreach($properties->materials as $index => $materials)
						<tr>
							<td style= "vertical-align: middle"><img class="img-circle" src="https://image.eveonline.com/Type/{{ $index }}_32.png"><a href="{{ route('upwell.view_salvage', $index )}}">{{ $typeIDHelper->getSalvageDetails($index)->name }}</a></td>
							<td style= "vertical-align: middle">{{ $materials }}</td>

							@foreach($item_value as $type_id => $value)
							@if($type_id == $index) 
							<td style= "vertical-align: middle">{{ number_format($value,2) }}</td>
							
							<td style= "vertical-align: middle">{{ number_format($value * $materials,2) }}</td>
							@endif
							@endforeach
						</tr>
						@endforeach

						<tr>
							<td></td>
							<td></td>
							<td>Salvage Value</td>
							<td><b>{{ number_format($rig->value,2) }}</b></td>
						</tr>

						<tr>
							<td></td>
							<td></td>
							<td>Install Cost</td>
							<td><b>{{ number_format($properties->install_price ,2) }}</b></td>
						</tr>

							<tr>
							<td></td>
							<td></td>
							<td>Total Cost</td>
							<td><b>{{ number_format($rig->value + $properties->install_price ,2) }}</b></td>
						</tr>

					</tbody>
				</table>
			</div>
		</div>
	</div>

	<div class="col-md-4">
		<div class="panel panel-default upwell-chart">

			<div class="panel-heading">
				Upwell Structure Rig Manufacture by Salvage Value.<br>
			</div>
			<div class="panel-body chart">

				<div>
					<canvas id="myChart" height="400"></canvas>
				</div>

			</div>
		</div>
	</div>

</div>

@section('styles')
<style>
	.upwell-chart .chart {
		zoom: 1.235;
	}
</style>
@stop

@stop


@section('scripts')
<script>
	$(document).ready(function(){
		$('#upwell-rigs').DataTable( {
			"paging":   false,
			"searching": true,
			"pageLength": 500,
		}
		);

	});
</script>

<script>
	var labels = {!! json_encode(array_keys($pie_chart)) !!};
	var value = {!! json_encode(array_values($pie_chart)) !!};
	var chartColours = {!! json_encode(array_values($chartColours)) !!};
</script>
{!! HTML::script('assets/js/chart.min.js') !!}
{!! HTML::script('assets/js/as/upwell_rig_build_piechart.js') !!}
@stop



