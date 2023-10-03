@extends('layouts.app')

@section('page-title', 'Observation')

@section('content')

<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
			Observation
			<small> - {{ $observation->unique_id }} </small>
			<div class="pull-right">
				<ol class="breadcrumb">
					<li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
					<li><a href="{{ route('observation.list') }}">Observation Manager</a></li>
					<li class="active">{{ $observation->unique_id }}</li>
				</ol>
			</div>

		</h1>
	</div>
</div>

@include('partials.messages')

<div class="row">

	<div class="col-md-3">
		<div id="edit-user-panel" class="panel panel-default">
			<div class="panel-heading">
				Observation Information
			</div>
			<div class="panel-body">
				<table class="table table-hover">
					<thead>
						<tr>
							<th colspan="3">Information</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>Observed By</td>
							<td>{{ $observation->created_by_username }}</td>
						</tr>
						<tr>
							<td>Created</td>
							<td>{{ $observation->created_at }} : {{ \Carbon\Carbon::parse($observation->created_at)->diffForHumans() }}</td>
						</tr>
						<tr>
							<td>Reviewed By</td>
							<td>{{ $observation->reviewed_by_username }}</td>
						</tr>
						<tr>
							<td>Reviewed</td>
							<td>{{ $observation->updated_at }} : {{ \Carbon\Carbon::parse($observation->updated_at)->diffForHumans() }}</td>
						</tr>

						<tr>
							<td>Prority</td>
							@if($observation->prority == 0)
							<td ><span class="label label-warning">For Review.</span></td>
							@elseif($observation->prority == 1)
							<td ><span class="label label-danger">Useless</span></td>
							@elseif($observation->prority == 2)
							<td ><span class="label label-warning">Low</span></td>
							@elseif($observation->prority == 3)
							<td ><span class="label label-info">High</span></td>
							@elseif($observation->prority == 4)
							<td ><span class="label label-success">Urgent</span></td>
							@else
							<td ></td>
							@endif
						</tr>

						<tr>
							<td>State</td>
							@if($observation->state == 0)
							<td ><span class="label label-warning">For Review.</span></td>
							@elseif($observation->state == 1)
							<td ><span class="label label-success">Reviewed.</span></td>
							@else
							<td ><span class="label label-danger">Deleted</span></td>
							@endif
						</tr>

						<tr>
							<td>Score</td>
							@if($observation->score == 1)
							<td ><span class="label label-success">Awesome</span></td>
							@elseif($observation->score == 2)
							<td ><span class="label label-info">Good</span></td>
							@elseif($observation->score == 3)
							<td ><span class="label label-warning">Meh</span></td>
							@elseif($observation->score == 4)
							<td ><span class="label label-danger">Pure Shit</span></td>
							@else
							<td ></td>
							@endif
						</tr>

						@if($observation->solar_system_id > 0)
						<tr>
							<td>Solar System</td>
							<td><a href="{{ route('solar.system', $observation->solar_system_id) }}" target="_blank">{{ $observation->solar_system_name }}</a></td>
						</tr>
						@endif

						@if($observation->corporation_id > 0)
						<tr>
							<td>Corporation</td>
							<td><a href="{{ route('corporation.view', $observation->corporation_id) }}" target="_blank">{{ $observation->corporation_name }} ({{ $observation->corporation_ticker }})</a></td>
						</tr>
						@endif

						@if($observation->alliance_id > 0)
						<tr>
							<td>Alliance</td>
							{{ $observation->alliance_name }}</a>
							<td><a href="{{ route('alliance.view', $observation->alliance_id) }}" target="_blank">{{ $observation->alliance_name }} ({{ $observation->alliance_ticker }})</a></td>
						</tr>
						@endif
					</tbody>
				</table>
			</div>
		</div>
	</div>


	<div class="col-md-9">
		<div id="edit-user-panel" class="panel panel-default">
			<div class="panel-heading">
				Observation Information for - {{ $observation->unique_id }} created by <b>{{ $observation->created_by_username }}</b>
			</div>
			<div class="panel panel-body">

				{!! $observation->observation !!}

			</div>

		</div>
	</div>


</div>

@stop
