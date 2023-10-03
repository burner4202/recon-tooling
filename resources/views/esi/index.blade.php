@extends('layouts.app')

@section('page-title', 'Token')

@section('content')

<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
			ESI Token
			<small> - creates an eve online token</small>
			<div class="pull-right">
				<ol class="breadcrumb">
					<li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
					<li class="active">ESI Tokens</li>
				</ol>
			</div>

		</h1>
	</div>
</div>

@include('partials.messages')

<div class="row tab-search">
	<div class="col-md-1">
		<a href="{{ route('esi.token_add') }}" class="btn btn-primary active" title="Add each of your EVE characters by clicking this button">
			<i class="glyphicon glyphicon-plus"></i>
			Add Character
		</a>
	</div>
	@permission('corporation.manage')
		<div class="col-md-1">
		<a href="{{ route('esi.token_add_corporation') }}" class="btn btn-success active" title="Add each of your EVE characters by clicking this button">
			<i class="glyphicon glyphicon-plus"></i>
			Add Corporation
		</a>
	</div>
	@endpermission
</div>


<div class="row tab-search">
	<div class="col-md-5"></div>
</div>

<div class="table-responsive top-border-table" id="srp-table-wrapper">

	<table class="table" id="characters">
		<thead>
			<th> @sortablelink('esi_name', 'Character Name')</th>
			<th> @sortablelink('esi_corporation_name', 'Corporation')</th>
			<th> @sortablelink('esi_active', 'Token Active')</th>
			<th> @sortablelink('updated_at', 'Last Updated')</th>
		</thead>
		<tbody>

			@if (isset($characters))              
			@foreach($characters as $character)

			<tr>
				<td><a href="#"><img class="img-circle" src="https://imageserver.eveonline.com/Character/{{ $character->esi_character_id }}_32.jpg">{{ $character->esi_name }}</a></td>
				<td><img class="img-circle" src="https://imageserver.eveonline.com/Corporation/{{ $character->esi_corporation_id }}_32.png">{{ $character->esi_corporation_name }}</td>
				@if ($character->esi_active)
				<td style="vertical-align: middle"><span class="glyphicon glyphicon-ok"></span></a></td>
				@else
				<td style="vertical-align: middle"><span class="glyphicon glyphicon-remove"></span></a></td>
				@endif
				<td style="vertical-align: middle">{{ $character->updated_at->diffForHumans() }}</th>
			</td>
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
		$('#characters').DataTable( {
			"paging":   false,
			"searching": true,
			"pageLength": 500,
		}
		);

	});
</script>
@stop


