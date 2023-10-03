@extends('layouts.app')

@section('page-title', $moon->moon_name)

@section('content')
<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
			{{ $moon->moon_name }}
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

@if($moon->moon_value_30_day == "")
<div class="alert alert-warning" role="alert">
	<b>{{ $moon->moon_name }} - has not yet been scanned.</b>
</div>
@endif

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
							<td>{!! $moon->moon_system_name !!}</td>
						</tr>
						
						<tr>
							<td>Constellation</td>
							<td>{!! $moon->moon_constellation_name !!}</td>
						</tr>

						<tr>
							<td>Region</td>
							<td>{!! $moon->moon_region_name !!}</td>
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

					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

@stop

