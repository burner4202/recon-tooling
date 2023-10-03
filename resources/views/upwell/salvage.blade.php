@extends('layouts.app')

@section('page-title', 'Salvage')

@section('content')

@inject('price', 'Vanguard\Http\Controllers\Web\RefinedMaterialsController')

<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
			Salvage
			<small>- list of eve salvage</small>
			<div class="pull-right">
				<ol class="breadcrumb">
					<li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
					<li class="active">Salvage</li>
				</ol>
			</div>
		</h1>
	</div>
</div>


@include('partials.messages')

<div class="row tab-search">
	<div class="col-md-5"></div>
</div>

<div class="table-responsive top-border-table" id="salvage-table-wrapper">

	<table class="table" id="salvage">
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

			@if (isset($salvage))              
			@foreach($salvage as $item)

			<tr>
				<td><img class="img-circle" src="https://image.eveonline.com/Type/{{ $item->type_id }}_32.png"><a href="{{ route('upwell.view_salvage', $item->type_id) }}">{{ $item->name }}</a></td>
				<td>{{ number_format($price->getPrice($item->type_id)->average, 2,".",",") }}</td>
				<td>{{ number_format($price->getPrice($item->type_id)->highest, 2,".",",") }}</td>
				<td>{{ number_format($price->getPrice($item->type_id)->lowest, 2,".",",") }}</td>
				<td>{{ number_format($price->getPrice($item->type_id)->order_count, 2,".",",") }}</td>
				<td>{{ number_format($price->getPrice($item->type_id)->volume, 2,".",",") }}</td>
				<td>{{ $price->getPrice($item->type_id)->updated_at->toFormattedDateString() }}</td>
				<td>{{ $price->getPrice($item->type_id)->updated_at->DiffForHumans() }}</td>

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
		$('#salvage').DataTable( {
			"paging":   false,
			"searching": true,
			"pageLength": 500,
			"order": [[ 0, "asc" ]]
		}
		);

	});
</script>
@stop
