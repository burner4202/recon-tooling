@extends('layouts.app')

@section('page-title', 'Raw Moon Materials')

@section('content')

@inject('price', 'Vanguard\Http\Controllers\Web\RefinedMaterialsController')

<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
			Raw Moon Materials
			<small>- The Forge Prices of Raw Moon Materials</small>
			<div class="pull-right">
				<ol class="breadcrumb">
					<li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
					<li class="active">Raw Moon Materials</li>
				</ol>
			</div>
		</h1>
	</div>
</div>


@include('partials.messages')


<div class="row tab-search">
	<div class="col-md-5"></div>
</div>

<div class="table-responsive top-border-table" id="srp-table-wrapper">

	<table class="table" id="moon">
		<thead>
			<th>Name</th>
			<th>Average</th>
			<th>Highest</th>
			<th>Lowest</th>
			<th>Orders</th>
			<th>Volume</th>
			<th>Price Date</th>
			<th>Updated</th>
		</thead>
		<tbody>

			@if (isset($moons))              
			@foreach($moons as $moon)

			<tr>
					<td><img class="img-circle" src="https://image.eveonline.com/Type/{{ $moon->type_id }}_32.png"><a href="{{ route('refined.moons_history', $moon->type_id) }}">{{ $moon->name }}</a></td>
				<td>{{ number_format($price->getPrice($moon->type_id)->average, 2,".",",") }}</td>
				<td>{{ number_format($price->getPrice($moon->type_id)->highest, 2,".",",") }}</td>
				<td>{{ number_format($price->getPrice($moon->type_id)->lowest, 2,".",",") }}</td>
				<td>{{ number_format($price->getPrice($moon->type_id)->order_count, 2,".",",") }}</td>
				<td>{{ number_format($price->getPrice($moon->type_id)->volume, 2,".",",") }}</td>
				<td>{{ $price->getPrice($moon->type_id)->updated_at->toFormattedDateString() }}</td>
				<td>{{ $price->getPrice($moon->type_id)->updated_at->DiffForHumans() }}</td>

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
@stop


@section('scripts')
<script>
	$(document).ready(function(){
		$('#moon').DataTable( {
			"paging":   false,
			"searching": true,
			"pageLength": 500,
			"order": [[ 0, "asc" ]]
		}
		);

	});
</script>
@stop


