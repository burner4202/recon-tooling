@extends('layouts.app')

@section('page-title', 'Upwell Rigs')

@section('content')

<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
			Upwell Rigs
			<small> - list of upwell rigs</small>
			<div class="pull-right">
				<ol class="breadcrumb">
					<li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
					<li class="active">Upwell Rigs</li>
				</ol>
			</div>

		</h1>
	</div>
</div>

@include('partials.messages')
<div class="col-md-12">
	<div class="panel panel-default upwell-chart">

		<div class="panel-heading">
			Upwell Structure Rig, by Value.<br>
			Select Rig below to see brakedown of costs.
		</div>
		<div class="panel-body chart">

			<div>
				<canvas id="myChart" height="395"></canvas>
			</div>

		</div>
	</div>
</div>
<div class="col-md-12">
	<div class="panel panel-default">
		<div class="panel-heading">Upwell Rigs</div>
		<div class="panel-body">

			
			<div class="table-responsive top-border-table" id="inprogress-table-wrapper">

				<table class="table" id="upwell-rigs">
					<thead>
						<th> @sortablelink('name')</th>
						<th> @sortablelink('value', 'Manufacture Value')</th>
						<th> Install Price</th>
						<th> Total Value</th>
						<th> @sortablelink('updated_at', 'Last Updated')</th>
						

					</thead>
					<tbody>

						@if (isset($rigs))              
						@foreach($rigs as $type_id => $rig)

						<tr>
							<td><img class="img-circle" src="https://imageserver.eveonline.com/Type/{{ $rig->type_id }}_32.png">&nbsp;<a href="{{ route('upwell.rig', $rig->type_id) }}">{{ $rig->name }}</a></td>
							<td>{!! number_format($rig->value,2) !!}</td>
							@foreach(json_decode($rig->meta_data) as $desc => $meta_data)
							@if($desc == "install_price")
							<td>{!! number_format($meta_data,2) !!}</td>
							<td>{!! number_format($meta_data + $rig->value,2) !!}</td>
							@endif
							@endforeach
							
							<td>{!! $rig->updated_at !!} : {!! \Carbon\Carbon::parse($rig->updated_at)->diffForHumans() !!} </td>
						</tr>

						@endforeach
						@else

						<tr>
							<td colspan="6"><em>No Records Found</em></td>
						</tr>

						@endif




					</tbody>

				</table>
				{!! $rigs->render() !!}
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
	var labels = {!! json_encode(array_keys($graph)) !!};
	var value = {!! json_encode(array_values($graph)) !!};
</script>
{!! HTML::script('assets/js/chart.min.js') !!}
{!! HTML::script('assets/js/as/upwell_rig_statistics.js') !!}
@stop




