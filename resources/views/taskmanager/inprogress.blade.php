@extends('layouts.app')

@section('page-title', 'Task Manager | In Progress')

@section('content')

<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
			Task Manager
			<small> - tasks currently in progress</small>
			<div class="pull-right">
				<ol class="breadcrumb">
					<li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
					<li class="active">Task Manager / In Progress</li>
				</ol>
			</div>

		</h1>
	</div>
</div>

@include('partials.messages')

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
						<th style="vertical-align: middle">Accepted At</th>
						<th style="vertical-align: middle">Duration (Hours)</th>
						<th style="vertical-align: middle">Notes</th>
						<th style="vertical-align: middle">Unclaim</th>
						<th style="vertical-align: middle">Complete</th>
						

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
							<td style="vertical-align: middle">{!! $task->tm_accepted_datetime_at !!}</td>
							<td style="vertical-align: middle">{!! \Carbon\Carbon::parse($task->tm_accepted_datetime_at)->diffInHours($now) !!}</td>
							<td style="vertical-align: middle"><a href="#" data-toggle="tooltip" title="{!! $task->tm_notes !!}" data-placement="right" ><span class="glyphicon glyphicon-info-sign"></span></a></td>
							
							<td style="vertical-align: middle">
								<a href="{{ route('taskmanager.unclaim', $task->id) }}" class="label label-warning" data-toggle="tooltip" data-placement="top">
									<span >Unclaim Task</span>
								</a>
							</td>
							<td style="vertical-align: middle">
								<a href="{{ route('taskmanager.complete', $task->id) }}" class="label label-primary" data-toggle="tooltip" data-placement="top">
									<span >Complete</span>
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


@stop
@section('scripts')
<script>
	$(document).ready(function(){
		$('#inprogress-tasks').DataTable( {
			"paging":   false,
			"searching": true,
			"pageLength": 500,
			"order": [[ 6, "desc" ]]
		}
		);

	});
</script>
@stop

