@extends('layouts.app')

@section('page-title', $fitting->fitting_name)

@section('content')

<div class="row">
	<div class="col-lg-12">
		<h2 class="page-header">
			<img class="img-circle" src="https://image.eveonline.com/Type/{{ $fitting->fitting_hull_type_id }}_32.png"> Fitting Name: {{ $fitting->fitting_name }} </a></img>
			<small>{{ $fitting->fitting_hull_name }}</small>
			<div class="pull-right">
				<ol class="breadcrumb">
					<li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
					<li><a href="{{ route('fittings.index') }}">Fittings</a></li>
					<li class="active">{{ $fitting->fitting_name }}</li>
				</ol>
			</div>

		</h1>
	</div>
</div>

<div class="row">
	<div class="col-md-4">
		<div id="edit-user-panel" class="panel panel-default">
			<div class="panel-heading">
				{{ $fitting->fitting_name }}
				<div class="pull-right" style="vertical-align:middle;">
					<span class="glyphicon glyphicon-info-sign fa-1x" data-toggle="tooltip" title="Fitting Summary" data-placement="top"></span>
				</div>
			</div>

			<div class="panel-body panel-profile">
				<div class="image">
					<img alt="image" class="img-circle avatar" src="https://image.eveonline.com/Type/{{ $fitting->fitting_hull_type_id }}_64.png">
				</div>
				<div class="name"><strong>{{  $fitting->fitting_name  }}</strong></div>

				<table class="table table-hover table-details">
					<thead>
						<tr>
							<th colspan="3">Information</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>Fitting Name</td>
							<td>{{ $fitting->fitting_name }}</td>
						</tr>
						<tr>
							<td>Hull Type</td>
							<td>{{ $fitting->fitting_hull_name }}</td>
						</tr>

						<tr>
							<td>Hull Value</td>
							<td>{{ number_format($fitting->fitting_hull_value,2) }}</td>
						</tr>

						<tr>
							<td>Module Value</td>
							<td>{{ number_format($fitting->fitting_module_value,2) }}</td>
						</tr>

						<tr>
							<td>Cargo Value</td>
							<td>{{ number_format($fitting->fitting_cargo_value,2) }}</td>
						</tr>

						<tr>
							<td>Total Value</td>
							<td><b>{{ number_format($fitting->fitting_value,2) }}</b></td>
						</tr>

						<tr>
							<td>Added By</td>
							<td>{{ $fitting->fitting_added_by }}</td>
						</tr>
						<tr>
							<td>Created</td>
							<td>{{ $fitting->created_at }} : {{ $fitting->created_at->diffForHumans() }}</td>
						</tr>

					</tbody>
				</table>


			</div>

		</div>
	</div>

	<div class="col-md-4">
		<div id="edit-user-panel" class="panel panel-default">
			<div class="panel-heading">
				Fitting
				<div class="pull-right" style="vertical-align:middle;">
					<span class="glyphicon glyphicon-info-sign fa-1x" data-toggle="tooltip" title="Module Fitting" data-placement="top"></span>
				</div>
			</div>
			<div class="panel-body panel-profile">
				<table class="table table-hover table-details" id="fitting-modules">

					<thead>
						<tr>
							<th>Name</th>
							<th>Slot</th>
							<th>Price</th>
						</tr>
					</thead>
					<tbody>				

						@foreach($modules as $index => $module)
						<tr>
							<td style= "vertical-align: middle"><img class="img-circle" src="https://image.eveonline.com/Type/{{ $module->type_id }}_32.png">{{ $module->name }}</td>
							<td style= "vertical-align: middle">{{ $module->slot }}</td>
							<td style= "vertical-align: middle">{{ number_format($module->price,2) }}</td>
						</tr>
						@endforeach

					</tbody>
				</table>
			</div>
		</div>
	</div>

	<div class="col-md-4">
		<div id="edit-user-panel" class="panel panel-default">
			<div class="panel-heading">
				Cargo
				<div class="pull-right" style="vertical-align:middle;">
					<span class="glyphicon glyphicon-info-sign fa-1x" data-toggle="tooltip" title="Items in Cargo/Drone Bay" data-placement="top"></span>
				</div>
			</div>
			<div class="panel-body panel-profile">
				<table class="table table-hover table-details" id="fitting-cargo">

					<thead>
						<tr>
							<th>Name</th>
							<th>Amount</th>
							<th>Price</th>
						</tr>
					</thead>
					<tbody>				

						@foreach($cargo as $index => $item)
						<tr>
							<td style= "vertical-align: middle"><img class="img-circle" src="https://image.eveonline.com/Type/{{ $item->type_id }}_32.png">{{ $item->name }}</td>
							<td style= "vertical-align: middle">{{ $item->amount }}</td>
							<td style= "vertical-align: middle">{{ number_format($item->price,2) }}</td>
						</tr>
						@endforeach

					</tbody>
				</table>
			</div>
		</div>
	</div>

</div>


@stop

@section('scripts')
<script>
	$(document).ready(function(){
		$('#fitting-modules').DataTable( {
			"paging":   false,
			"searching": true,
			"pageLength": 500,
			"order": [[ 1, "asc" ]]
		}
		);

	});
</script>

<script>
	$(document).ready(function(){
		$('#fitting-cargo').DataTable( {
			"paging":   false,
			"searching": true,
			"pageLength": 500,
		}
		);

	});
</script>


@stop


