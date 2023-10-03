@extends('layouts.app')

@section('page-title', 'Upwell Modules')

@section('content')

<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
			Upwell Modules
			<small>- list of eve upwell modules</small>
			<div class="pull-right">
				<ol class="breadcrumb">
					<li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
					<li><a href="{{ route('upwell.modules') }}">Market</a></li>
					<li class="active">Upwell Modules</li>
				</ol>
			</div>
		</h1>
	</div>
</div>


@include('partials.messages')

<div class="table-responsive top-border-table" id="modules-table-wrapper">

	<table class="table" id="modules">
		<thead>
			<th> @sortablelink('name')</th>
			<th>Average</th>
			<th>Highest</th>
			<th>Lowest</th>
			<th>Orders</th>
			<th>Volume</th>
			<th>Updated</th>
		</thead>
		<tbody>
			@if (isset($modules))              
			@foreach($modules as $item)

			<tr>
				<td><img class="img-circle" src="https://image.eveonline.com/Type/{{ $item->upm_type_id }}_32.png"><a href="{{ route('upwell.view_modules', $item->upm_type_id )}}">{{ $item->upm_name }}</a></td>

				@foreach($prices as $price)
				@if($price->type_id == $item->upm_type_id)
		
				<td>{{ number_format($price->average,2) }}</td>
				<td>{{ number_format($price->highest,2 )}}</td>
				<td>{{ number_format($price->lowest,2) }}</t d>
				<td>{{ number_format($price->order_count,2) }}</td>
				<td>{{ number_format($price->volume,2) }}</td>
				<td>{{ $price->date }}</td>		
	
				@endif
				@endforeach


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
		}
		);

	});
</script>
@stop