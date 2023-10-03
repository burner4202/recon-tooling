@extends('layouts.app')

@section('page-title', 'Task Manager | Overview')

@section('content')

<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
			Task Manager
			<small> - overview of all tasks</small>
			<div class="pull-right">
				<ol class="breadcrumb">
					<li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
					<li class="active">Task Manager / Overview</li>
				</ol>
			</div>

		</h1>
	</div>
</div>

@include('partials.messages')
<div class="row tab-search">
	<div class="col-md-12">
		
	</div>
</div>

<ul class="nav nav-tabs" id="task-manager">
	<li class="active"><a data-toggle="tab" href="#overview">Overview</a></li>
	<li><a data-toggle="tab" href="#outstanding">Outstanding</a></li>
	<li><a data-toggle="tab" href="#inprogress">In Progress</a></li>
	<li><a data-toggle="tab" href="#completed">Completed</a></li>
</ul>

<div class="tab-content">
	<div id="overview" class="tab-pane fade in active">
		<div class="row">
			<div class="col-md-3">
				<div class="panel panel-default">
					<div class="panel-heading">Add Task</div>
					<div class="panel-body">

						<form method="post" action="{{ route('taskmanager.add_pending') }}" enctype="multipart/form-data">
							{{ csrf_field() }}
							<div class="panel-body" >
								<div class="col-md-12">


									<div class="form-group">
										<label for="system">System</label>
										<input type="text" class="typeahead-systems form-control" name="system" id="system" placeholder="Search Systems" autocomplete="off" >
									</div>

									<div class="form-group">
										<label for="constellation">Constellation</label>
										<input type="text" class="typeahead-constellations form-control" name="constellation" id="constellation" placeholder="Search Constellations" autocomplete="off" >
									</div>

									<div class="form-group">
										<label for="region">Region</label>
										<input type="text" class="typeahead-regions form-control" name="region" id="region" placeholder="Search Regions" autocomplete="off" >
									</div>

									<div class="form-group">
										<label for="tasks">Task</label>
										{!! Form::select('tasks', $tasks, Input::get('tasks'), ['id' => 'tasks', 'class' => 'form-control']) !!}
									</div>

									<div class="form-group">
										<label for="prority">Prority</label>
										{!! Form::select('prority', $prority, Input::get('prority'), ['id' => 'prority', 'class' => 'form-control']) !!}
									</div>
									<div class="form-group">
										<label for="tasks">Task Notes</label>
										<textarea name="notes" type="text" class="form-control" id="notes" placeholder="Notes
										" rows="5"></textarea>
									</div>

								</div>
							</div>

							<div class="form-group row">
								<div style="text-align: center;">
									<button type="submit" class="btn btn-success">Add Task to Pending List</button>
								</div>
							</div>
						</form>
					</div>					
				</div>
			</div>
			<div class="col-md-9">
				<div class="panel panel-default">
					<div class="panel-heading">Pending Tasks, ready for Dispatch
					</div>
					<div class="panel-body">
						<div class="table-responsive top-border-table" id="location-table-wrapper">

							<table class="table" id="pending-tasks">
								<thead>
									<th style="vertical-align: middle">Task No</th>
									<th style="vertical-align: middle">System</th>
									<th style="vertical-align: middle">Constellation</th>
									<th style="vertical-align: middle">Region</th>
									<th style="vertical-align: middle">Task</th>
									<th style="vertical-align: middle">Prority</th>
									<th style="vertical-align: middle">Created By</th>
									<th style="vertical-align: middle">Created at</th>
									<th style="vertical-align: middle">Notes</th>
									<th style="vertical-align: middle">Dispatch
										<br>
										<a href="{{ route('taskmanager.dispatch_all') }}" class="label label-success" data-toggle="tooltip" data-placement="top">
											<span >Dispatch All</span>
										</a>
									</th>
									<th style="vertical-align: middle">Remove
										<br>
										<a href="{{ route('taskmanager.remove_all') }}" class="label label-danger" data-toggle="tooltip" data-placement="top">
											<span >Remove All</span>
										</a>
									</th>
								</thead>

								<tbody>

									@if (isset($pending_tasks))              
									@foreach($pending_tasks as $task)

									<tr>
										<td>{{ $task->id }}</td>
										<td style="vertical-align: middle"><a href="{{  route('solar.system', $task->tm_solar_system_id )}}">{!! $task->tm_solar_system_name !!}</a></td>
										<td style="vertical-align: middle"><a href="{{  route('solar.constellation', $task->tm_constellation_id )}}">{!! $task->tm_constellation_name !!}</a></td>
										<td style="vertical-align: middle"><a href="{{  route('solar.region', $task->tm_region_id )}}">{!! $task->tm_region_name !!}</a></td>
										<td style="vertical-align: middle">{!! $task->tm_task !!}</td>
										<td style="vertical-align: middle">{!! $task->tm_prority !!}</td>
										<td style="vertical-align: middle">{!! $task->tm_created_by_user_username !!}</td>
										<td style="vertical-align: middle">{!! $task->tm_created_datetime_at !!}</td>
										<td style="vertical-align: middle"><a href="#" data-toggle="tooltip" title="{!! $task->tm_notes !!}" data-placement="right" ><span class="glyphicon glyphicon-info-sign"></span></a></td>
										<td style="vertical-align: middle">
											<a href="{{ route('taskmanager.dispatch', $task->id) }}" class="label label-success" data-toggle="tooltip" data-placement="top">
												<span >Dispatch</span>
											</a>
										</td>
										<td style="vertical-align: middle">
											<a href="{{ route('taskmanager.remove_from_dispatch', $task->id) }}" class="label label-danger" data-toggle="tooltip" data-placement="top">
												<span >Remove</span>
											</a>
										</td>
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
						{!! $pending_tasks->fragment('home')->render() !!}
					</div>

				</div>


			</div>
		</div>
	</div>



</div>
<div id="outstanding" class="tab-pane fade">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading">Outstanding Tasks</div>
				<div class="panel-body">


					<div class="table-responsive top-border-table" id="location-table-wrapper">

						<table class="table" id="outstanding-tasks">
							<thead>
								<th style="vertical-align: middle">Task No</th>
								<th style="vertical-align: middle">System</th>
								<th style="vertical-align: middle">Constellation</th>
								<th style="vertical-align: middle">Region</th>
								<th style="vertical-align: middle">Task</th>
								<th style="vertical-align: middle">Prority</th>
								<th style="vertical-align: middle">Created By</th>
								<th style="vertical-align: middle">Created At</th>
								<th style="vertical-align: middle">Time Lapsed (Hours)</th>
								<th style="vertical-align: middle">Notes</th>
								<th style="vertical-align: middle">Claim Task</th>
								<th style="vertical-align: middle">Delete</th>
							</thead>

							<tbody>

								@if (isset($outstanding_tasks))              
								@foreach($outstanding_tasks as $task)

								<tr>
									<td>{{ $task->id }}</td>
									<td style="vertical-align: middle"><a href="{{  route('solar.system', $task->tm_solar_system_id )}}">{!! $task->tm_solar_system_name !!}</a></td>
									<td style="vertical-align: middle"><a href="{{  route('solar.constellation', $task->tm_constellation_id )}}">{!! $task->tm_constellation_name !!}</a></td>
									<td style="vertical-align: middle"><a href="{{  route('solar.region', $task->tm_region_id )}}">{!! $task->tm_region_name !!}</a></td>
									<td style="vertical-align: middle">{!! $task->tm_task !!}</td>
									<td style="vertical-align: middle">{!! $task->tm_prority !!}</td>
									<td style="vertical-align: middle">{!! $task->tm_created_by_user_username !!}</td>
									<td style="vertical-align: middle">{!! $task->tm_created_datetime_at !!}</td>
									<td style="vertical-align: middle">{!! \Carbon\Carbon::parse($task->tm_created_datetime_at)->diffInHours($now) !!}</td>
									<td style="vertical-align: middle"><a href="#" data-toggle="tooltip" title="{!! $task->tm_notes !!}" data-placement="right" ><span class="glyphicon glyphicon-info-sign"></span></a></td>
									<td style="vertical-align: middle">
										<a href="{{ route('taskmanager.claim', $task->id) }}" class="label label-info" data-toggle="tooltip" data-placement="top">
											<span >Claim Task</span>
										</a>
									</td>
									<td style="vertical-align: middle">
										<a href="{{ route('taskmanager.remove_from_dispatch', $task->id) }}" class="label label-danger" data-toggle="tooltip" data-placement="top">
											<span >Remove</span>
										</a>
									</td>
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
					{!! $outstanding_tasks->fragment('outstanding')->render() !!}
				</div>
			</div>
		</div>
	</div>
</div>



</div>
<div id="inprogress" class="tab-pane fade">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading">Tasks currently in progress</div>
				<div class="panel-body">


					<div class="table-responsive top-border-table" id="inprogress-table-wrapper">

						<table class="table" id="inprogress-tasks">
							<thead>
								<th style="vertical-align: middle">Task No</th>
								<th style="vertical-align: middle">System</th>
								<th style="vertical-align: middle">Constellation</th>
								<th style="vertical-align: middle">Region</th>
								<th style="vertical-align: middle">Task</th>
								<th style="vertical-align: middle">Prority</th>
								<th style="vertical-align: middle">Created By</th>
								<th style="vertical-align: middle">Created At</th>
								<th style="vertical-align: middle">Accepted By</th>
								<th style="vertical-align: middle">Accepted At</th>
								<th style="vertical-align: middle">Duration (Hours)</th>
								<th style="vertical-align: middle">Notes</th>
								<th style="vertical-align: middle">Reallocate</th>
							</thead>
							<tbody>

								@if (isset($inprogress_tasks))              
								@foreach($inprogress_tasks as $task)

								<tr>
									<td>{{ $task->id }}</td>
									<td style="vertical-align: middle"><a href="{{  route('solar.system', $task->tm_solar_system_id )}}">{!! $task->tm_solar_system_name !!}</a></td>
									<td style="vertical-align: middle"><a href="{{  route('solar.constellation', $task->tm_constellation_id )}}">{!! $task->tm_constellation_name !!}</a></td>
									<td style="vertical-align: middle"><a href="{{  route('solar.region', $task->tm_region_id )}}">{!! $task->tm_region_name !!}</a></td>
									<td style="vertical-align: middle">{!! $task->tm_task !!}</td>
									<td style="vertical-align: middle">{!! $task->tm_prority !!}</td>
									<td style="vertical-align: middle">{!! $task->tm_created_by_user_username !!}</td>
									<td style="vertical-align: middle">{!! $task->created_at !!}</td>
									<td style="vertical-align: middle">{!! $task->tm_accepted_by_user_username !!}</td>
									<td style="vertical-align: middle">{!! $task->tm_accepted_datetime_at !!}</td>
									<td style="vertical-align: middle">{!! \Carbon\Carbon::parse($task->tm_created_datetime_at)->diffInHours($now) !!}</td>
									<td style="vertical-align: middle"><a href="#" data-toggle="tooltip" title="{!! $task->tm_notes !!}" data-placement="right" ><span class="glyphicon glyphicon-info-sign"></span></a></td>
									<td style="vertical-align: middle">
										<a href="{{ route('taskmanager.return_to_outstanding', $task->id) }}" class="label label-warning" data-toggle="tooltip" data-placement="top">
											<span >Reallocate back to Outstanding</span>
										</a>
									</td>
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
					{!! $inprogress_tasks->fragment('inprogress')->render() !!}
				</div>

			</div>
		</div>
	</div>

</div>
</div>
<div id="completed" class="tab-pane fade">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading">Completed Tasks</div>
				<div class="panel-body">


					<div class="table-responsive top-border-table" id="inprogress-table-wrapper">

						<table class="table" id="completed-tasks">
							<thead>
								<th style="vertical-align: middle">Task No</th>
								<th style="vertical-align: middle">System</th>
								<th style="vertical-align: middle">Constellation</th>
								<th style="vertical-align: middle">Region</th>
								<th style="vertical-align: middle">Task</th>
								<th style="vertical-align: middle">Prority</th>
								<th style="vertical-align: middle">Created By</th>
								<th style="vertical-align: middle">Created At</th>
								<th style="vertical-align: middle">Completed By</th>
								<th style="vertical-align: middle">Accepted At</th>
								<th style="vertical-align: middle">Completed At</th>
								<th style="vertical-align: middle">Duration Created (Hours)</th>
								<th style="vertical-align: middle">Duration Accepted (Hours)</th>
								<th style="vertical-align: middle">Notes</th>
								<th style="vertical-align: middle">Status</th>
							</thead>
							<tbody>

								@if (isset($completed_tasks))              
								@foreach($completed_tasks as $task)

								<tr>
									<td>{{ $task->id }}</td>
									<td style="vertical-align: middle"><a href="{{  route('solar.system', $task->tm_solar_system_id )}}">{!! $task->tm_solar_system_name !!}</a></td>
									<td style="vertical-align: middle"><a href="{{  route('solar.constellation', $task->tm_constellation_id )}}">{!! $task->tm_constellation_name !!}</a></td>
									<td style="vertical-align: middle"><a href="{{  route('solar.region', $task->tm_region_id )}}">{!! $task->tm_region_name !!}</a></td>
									<td style="vertical-align: middle">{!! $task->tm_task !!}</td>
									<td style="vertical-align: middle">{!! $task->tm_prority !!}</td>
									<td style="vertical-align: middle">{!! $task->tm_created_by_user_username !!}</td>
									<td style="vertical-align: middle">{!! $task->created_at !!}</td>
									<td style="vertical-align: middle">{!! $task->tm_accepted_by_user_username !!}</td>
									<td style="vertical-align: middle">{!! $task->tm_accepted_datetime_at !!}</td>
									<td style="vertical-align: middle">{!! $task->tm_completed_datetime_at !!}</td>
									<td style="vertical-align: middle">{!! \Carbon\Carbon::parse($task->created_at)->diffInHours($task->tm_completed_datetime_at) !!}</td>
									<td style="vertical-align: middle">{!! \Carbon\Carbon::parse($task->tm_accepted_datetime_at)->diffInHours($task->tm_completed_datetime_at) !!}</td>
									<td style="vertical-align: middle"><a href="#" data-toggle="tooltip" title="{!! $task->tm_notes !!}" data-placement="right" ><span class="glyphicon glyphicon-info-sign"></span></a></td>
									<td style="vertical-align: middle"><a class="label label-success"><span >Completed</span></td>
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
						{!! $completed_tasks->fragment('completed')->render() !!}
					</div>

				</div>
			</div>
		</div>

	</div>
</div>
</div>

@stop

@section('styles')
{!! HTML::style('assets/css/bootstrap-datetimepicker.min.css') !!}
@stop

@section('scripts')

<script type="text/javascript">
	$(document).ready(function(){
		$('a[data-toggle="tab"]').on('show.bs.tab', function(e) {
			localStorage.setItem('activeTab', $(e.target).attr('href'));
		});
		var activeTab = localStorage.getItem('activeTab');
		if(activeTab){
			$('#task-manager a[href="' + activeTab + '"]').tab('show');
		}
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
<script>
	var path2 = "{{ route('autocomplete.constellations') }}";
	$('input.typeahead-constellations').typeahead({
		source:  function (constellation, process) {
			return $.get(path2, { constellation: constellation }, function (data2) {
				return process(data2);
			});
		}
	});
</script>
<script>
	var path3 = "{{ route('autocomplete.regions') }}";
	$('input.typeahead-regions').typeahead({
		source:  function (region, process) {
			return $.get(path3, { region: region }, function (data3) {
				return process(data3);
			});
		}
	});


</script>

{!! HTML::script('assets/js/moment.min.js') !!}
{!! HTML::script('assets/js/bootstrap-datetimepicker.min.js') !!}


<script>
	$(document).ready(function(){
		$('#pending-tasks').DataTable( {
			"paging":   false,
			"searching": true,
			"pageLength": 500,
			"order": [[ 0, "desc" ]]
		}
		);

		$('#outstanding-tasks').DataTable( {
			"paging":   false,
			"searching": true,
			"pageLength": 500,
			"order": [[ 0, "desc" ]]
		}
		);

		$('#inprogress-tasks').DataTable( {
			"paging":   false,
			"searching": true,
			"pageLength": 500,
			"order": [[ 9, "desc" ]]
		}
		);

		$('#completed-tasks').DataTable( {
			"paging":   false,
			"searching": true,
			"pageLength": 500,
			"order": [[ 10, "desc" ]]
		}
		);

	});
</script>

@stop
