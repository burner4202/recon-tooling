@extends('layouts.app')

@section('page-title', 'Activity Tracker | Index')

@section('content')

<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
			Activity Tracker
			<small> - summary of user activity</small>
			<div class="pull-right">
				<ol class="breadcrumb">
					<li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
					<li class="active">Activity Tracker</li>
				</ol>
			</div>

		</h1>
	</div>
</div>

@include('partials.messages')

<div class="row tab-search">
	<div class="col-md-12"></div>
	<form method="GET" action="" accept-charset="UTF-8" id="activity-form">
		<div class="col-md-2">
			Search Everything
			<div class="input-group custom-search-form">
				<input type="text" class="form-control" name="search" value="{{ Input::get('search') }}" placeholder="Search Everything">
				<span class="input-group-btn">
					<button class="btn btn-default" type="submit" id="search-activity-btn">
						<span class="glyphicon glyphicon-search"></span>
					</button>
					@if (
						Input::has('search') && Input::get('search') != '' || 
						Input::has('username') && Input::get('username') != '' ||
						Input::has('system') && Input::get('system') != '' ||
						Input::has('action') && Input::get('action') != ''
						)
						<a href="{{ route('activitytracker.index') }}" class="btn btn-danger" system="button" >
							<span class="glyphicon glyphicon-remove"></span>
						</a>
						@endif
					</span>
				</div>
			</div>

			<div class="col-md-2">
				Username
				{!! Form::select('username', $username, Input::get('username'), ['id' => 'username', 'class' => 'form-control']) !!}
			</div>

			<div class="col-md-2">
				Action
				{!! Form::select('action', $action, Input::get('action'), ['id' => 'action', 'class' => 'form-control']) !!}
			</div>

			<div class="col-md-2">
				System
				<div class="input-group custom-search-form">
					<input type="text" class="form-control typeahead-systems" name="system" value="{{ Input::get('system') }}" placeholder="..." autocomplete="off">
					<span class="input-group-btn">
						<button class="btn btn-default" type="submit" id="search-systems-btn">
							<span class="glyphicon glyphicon-search"></span>
						</button>
						@if (Input::has('system') && Input::get('system') != '')
						<a href="{{ route('activitytracker.index') }}" class="btn btn-danger" type="button" >
							<span class="glyphicon glyphicon-remove"></span>
						</a>
						@endif
					</span>
				</div>
			</div>

		</form>


		
	</div>

</div>



<div class="col-md-12">
	{!! $activity->appends(\Request::except('activity'))->render() !!}
	<div class="panel panel-default">
		<div class="panel-heading">Activity Tracker, All Upwell Actions.</div>
		<div class="panel-body">

			
			<div class="table-responsive top-border-table" id="location-table-wrapper">

				<table class="table" id="pending-tasks">
					<thead>
						<th> @sortablelink('at_username', 'Username')</th>
						<th> @sortablelink('at_system_name', 'System Name')</th>
						<th> @sortablelink('at_structure_name', 'Structure Name')</th>
						<th> @sortablelink('at_corporation_name', 'Corporation Name')</th>
						<th> @sortablelink('at_action', 'Action')</th>
						<th> @sortablelink('at_created_at', 'Created At')</th>
					</thead>

					<tbody>

						@if (isset($activity))              
						@foreach($activity as $action)

						<tr>
							<td>{{ $action->at_username }}</td>
							<td style="vertical-align: middle"><a href="{{  route('solar.system', $action->at_system_id )}}">{!! $action->at_system_name !!}</a></td>
							<td style="vertical-align: middle"><a href="{{  route('structures.view', $action->at_structure_hash )}}">{!! $action->at_structure_name !!}</a></td>
							<td style="vertical-align: middle"><a href="{{  route('corporation.view', $action->at_corporation_id )}}">{!! $action->at_corporation_name !!}</a></td>
							<td style="vertical-align: middle">{!! $action->at_action !!}</td>
							<td style="vertical-align: middle">{!! \Carbon\Carbon::parse($action->created_at) !!} : {!! \Carbon\Carbon::parse($action->created_at)->diffForHumans() !!} </td>
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

	</div>

</div>

@stop

@section('scripts')


<script>

	$("#search").change(function () {
		$("#activity-form").submit();
	});

	$("#username").change(function () {
		$("#activity-form").submit();
	});

	$("#action").change(function () {
		$("#activity-form").submit();
	});

	$("#system").change(function () {
		$("#activity-form").submit();
	});


</script>
<script>

	var path1 = "{{ route('autocomplete.systems') }}";
	$('input.typeahead-systems').typeahead({
		source:  function (system, process) {
			return $.get(path1, { system: system }, function (data1) {
				return process(data1);
			});
		}
	});
</script>





@stop


