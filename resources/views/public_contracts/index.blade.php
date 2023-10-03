@extends('layouts.app')

@section('page-title', 'Public Contracts')

@section('content')

<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
			Public Contracts
			<div class="pull-right">
				<ol class="breadcrumb">
					<li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
					<li><a href="{{ route('intelligence.index') }}">Intelligence Dashboard</a></li>
					<li class="active">Public Contracts</li>
				</ol>
			</div>
		</h1>
	</div>
</div>

@include('partials.messages')
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				Public Contractss
			</div>
			<div class="panel-body">
				This section of the tools searches Delve, Fountain, Querious and Period Basis for Capital Hulls for sale on public contracts.<br>
				It itemises each hull/contract by hull type and also searches if the contract has been made in NPC Delve<br>
				It cross references the standings of each contract made and returns the current character/corporation standings in relation to the alliance.<br><br>
				Apply each filter as required to interrogate the data set.
			</div>
		</div>
	</div>
</div>

<div class="row col-md-12">
	<div class="panel panel-default weekly-report-chart">

		<div class="panel-heading">
			Visual Chart & Weekly Report of Capitals Leaving the Alliance.

		</div>
		<div class="panel-body chart">

			<div>
				<canvas id="myChart" height="250"></canvas>
			</div>

		</div>

	</div>

</div>


<div class="row col-md-12">
	<div class="panel panel-default">
		<div class="panel-heading">
			Public Contractss
		</div>
		<div class="panel-body">
			<div class="col-md-3 pull-right">
				<form method="GET" action="" accept-charset="UTF-8" id="contracts-form">
					Search Everything
					<div class="input-group custom-search-form">
						<input type="text" class="form-control" name="search" value="{{ Input::get('search') }}" placeholder="Search Everything">
						<span class="input-group-btn">
							<button class="btn btn-default" type="submit" id="search-indices-btn">
								<span class="glyphicon glyphicon-search"></span>
							</button>
							@if (
								Input::has('search') && Input::get('search') != '' ||
								Input::has('npc') && Input::get('npc') != '' ||
								Input::has('standings') && Input::get('standings') != '' ||
								Input::has('regions') && Input::get('regions') != '' ||
								Input::has('regions') && Input::get('alliance') != '' ||
								Input::has('type') && Input::get('type') != '')					
								<a href="{{ route('public_contracts.index') }}" class="btn btn-danger" system="button" >
									<span class="glyphicon glyphicon-remove"></span>
								</a>
								@endif
							</span>
						</div>
					</div>

					<div class="col-md-1">
						Standings
						{!! Form::select('standings', $standings, Input::get('standings'), ['id' => 'standings', 'class' => 'form-control']) !!}
					</div>

					<div class="col-md-1">
						Type
						{!! Form::select('type', $type, Input::get('type'), ['id' => 'type', 'class' => 'form-control']) !!}
					</div>

					<div class="col-md-1">
						NPC Delve
						{!! Form::select('npc', $npc, Input::get('npc'), ['id' => 'npc', 'class' => 'form-control']) !!}
					</div>

					<div class="col-md-1">
						Region
						{!! Form::select('regions', $regions, Input::get('regions'), ['id' => 'regions', 'class' => 'form-control']) !!}
					</div>

					<div class="col-md-2">
						Alliance
						{!! Form::select('alliance', $alliance, Input::get('alliance'), ['id' => 'alliance', 'class' => 'form-control']) !!}
					</div>

					<div class="col-md-2">
						{!! $contracts->appends(\Request::except('contracts'))->render() !!}
					</div>

				</form>
			</div>
		</div>
	</div>
</div>



<div class="col-md-12">
	<div class="panel panel-default">
		<div class="panel-heading">
			Naught Boy's & Girls.
		</div>
		<div class="panel-body">

			<div class="table-responsive" id="contracts-table-wrapper">
				<table class="table table-borderless table-striped">
					<thead>
						<tr>
							<th> @sortablelink('type_name', 'Type')</th>
							<th> @sortablelink('region_name', 'Region')</th>
							<th> @sortablelink('date_issued', 'Date Issued')</th>
							<th> @sortablelink('date_expired', 'Date Expired')</th>
							<th> @sortablelink('character_name', 'Character Name')</th>
							<th> @sortablelink('corporation_name', 'Corporation Name')</th>
							<th> @sortablelink('alliance_name', 'Alliance Name')</th>
							<th> @sortablelink('showinfo_link', 'Show Info Link')</th>
							<th> @sortablelink('standing', 'Standing')</th>
							<th> @sortablelink('is_carrier', 'Carrier')</th>
							<th> @sortablelink('is_dread', 'Dread')</th>
							<th> @sortablelink('is_fax', 'Fax')</th>
							<th> @sortablelink('is_super', 'Super')</th>
							<th> @sortablelink('is_titan', 'Titan')</th>
							<th> @sortablelink('is_npc_delve', 'NPC Delve')</th>
						</tr>
					</thead>
					<tbody>
						@if (count($contracts))
						@foreach ($contracts as $contract)
						<tr>


							<td style="vertical-align: middle"><img class="img-circle" src="https://imageserver.eveonline.com/Type/{!! $contract->type_id !!}_32.png">{!! $contract->type_name !!}</td>
							<td style="vertical-align: middle">{!! $contract->region_name !!}</td>
							<td style="vertical-align: middle">{!! $contract->date_issued !!}</td>
							<td style="vertical-align: middle">{!! $contract->date_expired !!}</td>

							<td style="vertical-align: middle"><img class="img-circle" src="https://image.eveonline.com/Character/{{ $contract->issuer_id}}_32.jpg">&nbsp;{{ $contract->character_name }}</td>
							<td style="vertical-align: middle"><img class="img-circle" src="https://image.eveonline.com/Corporation/{{ $contract->corporation_id }}_32.png">&nbsp;{{ $contract->corporation_name }}</td>
							@if($contract->alliance_id > 1)
							<td style="vertical-align: middle"><img class="img-circle" src="https://image.eveonline.com/Alliance/{{ $contract->alliance_id}}_32.png">&nbsp;{{ $contract->alliance_name }}</td>
							@else
							<td></td>
							@endif


							<td style="vertical-align: middle">
								<input size="50" id="ingame_contract-{!! $contract->contract_id !!}" value="{!! $contract->showinfo_link !!}" disabled readonly>
							</td>

							@if($contract->standing <= 10 && $contract->standing >= 5)
							<td style="vertical-align: middle"><span class="label label-primary">{{ $contract->standing }}</span></td>
							@elseif($contract->standing <= 5 && $contract->standing >= 0)
							<td style="vertical-align: middle"><span class="label label-info">{{ $contract->standing }}</span></td>
							@elseif($contract->standing <= 0 && $contract->standing >= -5)
							<td style="vertical-align: middle"><span class="label label-warning">{{ $contract->standing }}</span></td>
							@else
							<td style="vertical-align: middle"><span class="label label-danger">{{ $contract->standing }}</span></td>
							@endif

							@if($contract->is_carrier == 1) 
							<td style="vertical-align: middle">Yes</td>
							@else
							<td></td>
							@endif

							@if($contract->is_dread == 1) 
							<td style="vertical-align: middle">Yes</td>
							@else
							<td></td>
							@endif

							@if($contract->is_fax == 1) 
							<td style="vertical-align: middle">Yes</td>
							@else
							<td></td>
							@endif

							@if($contract->is_super == 1) 
							<td style="vertical-align: middle">Yes</td>
							@else
							<td></td>
							@endif

							@if($contract->is_titan == 1) 
							<td style="vertical-align: middle">Yes</td>
							@else
							<td></td>
							@endif

							@if($contract->is_npc_delve == 1) 
							<td style="vertical-align: middle">Yes</td>
							@else
							<td></td>
							@endif
						</tr>
						@endforeach
						@else
						<tr>
							<td colspan="7"><em>No Records Found</em></td>
						</tr>
						@endif
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

@stop

@section('styles')
<style>
	.weekly-report-chart .chart {
		zoom: 1.235;
	}
</style>
@stop

@section('scripts')


<script>
	var labels = {!! json_encode(array_keys($chart)) !!};
	var is_titan = {!! json_encode(array_column($chart, 'is_titan')) !!};
	var is_carrier = {!! json_encode(array_column($chart, 'is_carrier')) !!};
	var is_fax = {!! json_encode(array_column($chart, 'is_fax')) !!};
	var is_dread = {!! json_encode(array_column($chart, 'is_dread')) !!};
	var is_super = {!! json_encode(array_column($chart, 'is_super')) !!};
	var is_npc_delve = {!! json_encode(array_column($chart, 'is_npc_delve')) !!};
	var is_neutral_contract = {!! json_encode(array_column($chart, 'is_neutral_contract')) !!};
	var is_friendly_contract = {!! json_encode(array_column($chart, 'is_friendly_contract')) !!};
	var is_hostile_contract = {!! json_encode(array_column($chart, 'is_hostile_contract')) !!};
</script>
{!! HTML::script('assets/js/chart.min.js') !!}
{!! HTML::script('assets/js/as/public_contracts_stacked.js') !!}


<script>

	$("#search").change(function () {
		$("#contracts-form").submit();
	});

	$("#standings").change(function () {
		$("#contracts-form").submit();
	});

	$("#type").change(function () {
		$("#contracts-form").submit();
	});

	$("#npc").change(function () {
		$("#contracts-form").submit();
	});

	$("#regions").change(function () {
		$("#contracts-form").submit();
	});

	$("#alliance").change(function () {
		$("#contracts-form").submit();
	});

</script>


@stop




























