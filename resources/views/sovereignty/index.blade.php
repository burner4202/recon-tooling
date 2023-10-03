@extends('layouts.app')

@section('page-title', 'Sovereignty')

@section('content')

<div class="row">
	<div class="col-md-12">
		<h1 class="page-header">
			Sovereignty
			<small> - who owns what!</small>
			<div class="pull-right">
				<ol class="breadcrumb">
					<li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
					<li class="active">Sovereignty</li>
				</ol>
			</div>

		</h1>
	</div>
</div>


<div class="col-md-12">

	<div class="col-md-3 ">
		{!! $sovereignty->appends(\Request::except('sovereignty'))->render() !!}
	</div>

	<div class="col-md-2 pull-right">
		<form method="GET" action="" accept-charset="UTF-8" id="sovereignty-form">
			Search Everything
			<div class="input-group custom-search-form">
				<input type="text" class="form-control" name="search" value="{{ Input::get('search') }}" placeholder="Search Everything">
				<span class="input-group-btn">
					<button class="btn btn-default" type="submit" id="search-sovereignty-btn">
						<span class="glyphicon glyphicon-search"></span>
					</button>
					@if (
						Input::has('search') && Input::get('search') != '' ||
						Input::has('region') && Input::get('region') != '' ||
						Input::has('vulnerable_from') && Input::get('vulnerable_from') != '' ||
						Input::has('vulnerable_day') && Input::get('vulnerable_day') != '' ||
						Input::has('supers_in_system') && Input::get('supers_in_system') != '' ||
						Input::has('bridge_in_system') && Input::get('bridge_in_system') != '' ||
						Input::has('keepstar_in_system') && Input::get('keepstar_in_system') != '' ||
						Input::has('vulnerable_to') && Input::get('vulnerable_to') != '' ||
						Input::has('alliance') && Input::get('alliance') != ''
						)					
						<a href="{{ route('sovereignty.index') }}" class="btn btn-danger" system="button" >
							<span class="glyphicon glyphicon-remove"></span>
						</a>
						@endif
					</span>
				</div>
			</div>

			<div class="col-md-2">
				Alliance
				{!! Form::select('alliance', $alliance, Input::get('alliance'), ['id' => 'alliance', 'class' => 'form-control']) !!}
			</div>

			<div class="col-md-2">
				Region
				{!! Form::select('region', $region, Input::get('region'), ['id' => 'region', 'class' => 'form-control']) !!}
			</div>

			<div class="col-md-2">
				Structure Type
				{!! Form::select('structure_type', $structure_type, Input::get('structure_type'), ['id' => 'structure_type', 'class' => 'form-control']) !!}
			</div>

			<div class="col-md-1">
				Titan Production
				{!! Form::select('supers_in_system', $supers_in_system, Input::get('supers_in_system'), ['id' => 'supers_in_system', 'class' => 'form-control']) !!}
			</div>

			<div class="col-md-1">
				Jump Bridge Network
				{!! Form::select('bridge_in_system', $bridge_in_system, Input::get('bridge_in_system'), ['id' => 'bridge_in_system', 'class' => 'form-control']) !!}
			</div>

			<div class="col-md-1">
				Keepstar
				{!! Form::select('keepstar_in_system', $keepstar_in_system, Input::get('keepstar_in_system'), ['id' => 'keepstar_in_system', 'class' => 'form-control']) !!}
			</div>

			<div class="col-md-1">
				Per Page
				{!! Form::select('no_per_page', $no_per_page, Input::get('no_per_page'), ['id' => 'no_per_page', 'class' => 'form-control']) !!}
			</div>

			<!--

			<div class="col-md-1">
				Vulnerable From
				{!! Form::select('vulnerable_from', $vulnerable_from, Input::get('vulnerable_from'), ['id' => 'vulnerable_from', 'class' => 'form-control']) !!}
			</div>


			<div class="col-md-1">
				Vulnerable To
				{!! Form::select('vulnerable_to', $vulnerable_to, Input::get('vulnerable_to'), ['id' => 'vulnerable_to', 'class' => 'form-control']) !!}
			</div>

			<div class="col-md-1">
				Vulnerable Day
				{!! Form::select('vulnerable_day', $vulnerable_day, Input::get('vulnerable_day'), ['id' => 'vulnerable_day', 'class' => 'form-control']) !!}
			</div>

		-->

		</form>
	</div>
</div>
<p></p>




<div class="row col-md-12">
	<div class="panel panel-default">
		<div class="panel-heading">Sovereignty - List of Structures</div>
		<div class="panel-body">

			<div class="table-responsive top-border-table" id="sovereignty-table-wrapper">

				<table class="table activity-tracker" id="sovereignty">
					<thead>
						<tr>
							<th>@sortablelink('solar_system_name', 'Solar System')</th>
							<th>@sortablelink('constellation_name', 'Constellation Name')</th>
							<th>@sortablelink('region_name', 'Region Name')</th>
							<th>@sortablelink('structure_type_name', 'Structure Type')</th>
							<th>@sortablelink('alliance_name', 'Alliance Name')</th>
							<th>@sortablelink('alliance_ticker', 'Alliance Ticker')</th>
							<th>@sortablelink('vulnerability_occupancy_level', 'ADM')</th>
							<th>@sortablelink('vulnerable_start_time', 'Vulnerablity Start')</th>
							<th>@sortablelink('vulnerable_end_time', 'Vulnerablity End')</th>
							<th>Window</th>
							<th>@sortablelink('supers_in_system', 'Titan Production')</th>
							<th>@sortablelink('bridge_in_system', 'Jump Bridge')</th>
							<th>@sortablelink('keepstar_in_system', 'Keepstar')</th>
						</tr>
					</thead>
					<tbody>

						@foreach($sovereignty as $sov)

						<tr>
							<td style="vertical-align: middle"><a href="{{ route('solar.system', $sov->solar_system_id) }}">{{ $sov->solar_system_name }}</a></td>
							<td style="vertical-align: middle"><a href="{{ route('solar.constellation', $sov->constellation_id) }}">{{ $sov->constellation_name }}</a></td>	
							<td style="vertical-align: middle"><a href="{{ route('solar.region', $sov->region_id) }}">{{ $sov->region_name }}</a></td>
							<td style="vertical-align: middle"><img class="img-circle" src="https://images.evetech.net/types/{{ $sov->structure_type_id }}/render?size=32">&nbsp;{!! $sov->structure_type_name !!}</td>
							<td style="vertical-align: middle"><img class="img-circle" src="https://images.evetech.net/alliances/{{ $sov->alliance_id }}/logo?size=32">&nbsp;{!! $sov->alliance_name !!}</td>
							<td style="vertical-align: middle">{{ $sov->alliance_ticker }}</td>
							@if($sov->vulnerability_occupancy_level == "")
							<td style="vertical-align: middle">Reinforced</td>
							<td style="vertical-align: middle">Reinforced</td>
							<td style="vertical-align: middle">Reinforced</td>
							<td style="vertical-align: middle">Reinforced</td>
							@else
							<td style="vertical-align: middle">{{ $sov->vulnerability_occupancy_level }}</td>
							<td style="vertical-align: middle">{{ \Carbon\Carbon::parse($sov->vulnerable_start_time)->toDayDateTimeString() }}</td>
							<td style="vertical-align: middle">{{ \Carbon\Carbon::parse($sov->vulnerable_end_time)->toDayDateTimeString() }}</td>
							<td style="vertical-align: middle">{{ \Carbon\Carbon::parse($sov->vulnerable_start_time)->diff(\Carbon\Carbon::parse($sov->vulnerable_end_time))->format('%hh %im') }}</td>
							@endif
							@if($sov->supers_in_system)
							<td style="vertical-align: middle">Yes</td>
							@else
							<td style="vertical-align: middle"></td>
							@endif
							@if($sov->bridge_in_system)
							<td style="vertical-align: middle">Yes</td>
							@else
							<td style="vertical-align: middle"></td>
							@endif
							@if($sov->keepstar_in_system)
							<td style="vertical-align: middle">Yes</td>
							@else
							<td style="vertical-align: middle"></td>
							@endif
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

	$("#search").change(function () {
		$("#sovereignty-form").submit();
	});

	$("#no_per_page").change(function () {
		$("#sovereignty-form").submit();
	});

	$("#structure_type").change(function () {
		$("#sovereignty-form").submit();
	});

	$("#supers_in_system").change(function () {
		$("#sovereignty-form").submit();
	});

	$("#bridge_in_system").change(function () {
		$("#sovereignty-form").submit();
	});

	$("#alliance").change(function () {
		$("#sovereignty-form").submit();
	});

	$("#region").change(function () {
		$("#sovereignty-form").submit();
	});

	$("#vulnerable_to").change(function () {
		$("#sovereignty-form").submit();
	});

	$("#vulnerable_from").change(function () {
		$("#sovereignty-form").submit();
	});

	$("#vulnerable_day").change(function () {
		$("#sovereignty-form").submit();
	});

	$("#keepstar_in_system").change(function () {
		$("#sovereignty-form").submit();
	});

</script>


@stop

