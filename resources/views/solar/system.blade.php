@extends('layouts.app')

@section('page-title', $systemDetails->ss_system_name)

@section('content')

<div class="row">
	<div class="col-md-12">
		<h1 class="page-header">
			{{ $systemDetails->ss_system_name }}

			<div class="pull-right">
				<ol class="breadcrumb">
					<li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
					<li><a href="{{ route('solar.universe') }}">Universe</a></li>
					<li><a href="{{ route('solar.region', $systemDetails->ss_region_id )}}">{{ $systemDetails->ss_region_name }}</a></li>
					<li><a href="{{ route('solar.constellation', $systemDetails->ss_constellation_id )}}">{{ $systemDetails->ss_constellation_name }}</a></li>
					<li class="active">{{ $systemDetails->ss_system_name }}</li>

				</ol>
			</div>
			<h4>Security Status: {{ $systemDetails->ss_security_status }} </h4>
		</h1>
	</div>
</div>

@include('partials.messages')

@if($systemDetails->ss_empty == "Empty")
<div class="alert alert-danger" role="alert">
	{{ $systemDetails->ss_system_name }} has been marked as empty.
</div>
@endif

@if (count($tasks))              
<div class="alert alert-info" role="alert">
	There are outstanding tasks in this system.
	<br>
	@foreach($tasks as $task)
	<br>
	<b>Task:</b> {{ $task->tm_task }}<br>
	<b>Created:</b> {{ \Carbon\Carbon::parse($task->created_at)->diffForHumans() }} by <b>{{ $task->tm_created_by_user_username }} </b><br>

	<a href="{{ route('taskmanager.claim', $task->id)}}" class="btn btn-success active" id="system-empty">
		Claim
	</a>
	<br>
	@endforeach
</div>

@endif

@if (count($my_tasks))              
<div class="alert alert-info" role="alert">
	You have outstanding tasks in this system.
	<br>
	@foreach($my_tasks as $task)
	<br>
	<b>Task:</b> {{ $task->tm_task }}<br>
	<b>Created:</b> {{ \Carbon\Carbon::parse($task->created_at)->diffForHumans() }} by <b>{{ $task->tm_created_by_user_username }} </b><br>
	Click the complete button below after you have finished your task, unless told otherwise!<br><br>


	<a href="{{ route('taskmanager.complete', $task->id)}}" class="btn btn-success active" id="system-empty">
		Complete
	</a>
	<br>
	
	
</div>
@endforeach
@endif

<div class="row">
	<div class="row tab-search">
		<div class="col-md-12">
			<form method="GET" action="" accept-charset="UTF-8" id="system-form" autocomplete="off">
				<div class="col-md-2">
					<div class="input-group custom-search-form">
						<input type="text" class="form-control" name="search" value="{{ Input::get('search') }}" placeholder="Search" meta name="csrf-token" content="{{csrf_token() }}">
						<span class="input-group-btn">
							<button class="btn btn-default" type="submit" id="search-system-btn">
								<span class="glyphicon glyphicon-search"></span>
							</button>
							@if (Input::has('search') && Input::get('search') != '')
							<a href="{{ route('solar.system', $system_id) }}" class="btn btn-danger" type="button" >
								<span class="glyphicon glyphicon-remove"></span>
							</a>
							@endif
						</span>
					</div>
				</div>

				<div class="col-md-1">
					{!! Form::select('no_per_page', $no_per_page, Input::get('no_per_page'), ['id' => 'no_per_page', 'placeholder' => 'Per Page', 'class' => 'form-control']) !!}
				</div>

			</form>


			<div class="col-md-1">	
				<a href="http://evemaps.dotlan.net/map/{{ str_replace(" ", "_", $systemDetails->ss_region_name) }}/{{ $systemDetails->ss_system_name }}#adm" target="_blank" class="btn btn-primary" id="dotlan-link"  data-toggle="tooltip" title="Dotlan Page for {{ $systemDetails->ss_system_name }}" data-placement="top">
					DOTLan Link
				</a>
			</div>

			<div class="col-md-1">	
				<a href="https://zkillboard.com/system/{{ $systemDetails->ss_system_id }}" target="_blank" class="btn btn-primary" id="zkillboard-link"  data-toggle="tooltip" title="Zkillboard Page for {{ $systemDetails->ss_system_name }}" data-placement="top">
					ZKillboard Link
				</a>
			</div>
			@permission('taskmanager.manage')
			<div class="col-md-1">	
				<a href="{{ route('taskmanager.dispatch_from_system', $systemDetails->ss_system_id) }}" class="btn btn-info" id="dispatch_task"  data-toggle="tooltip" title="Add a task to update entire system." data-placement="top">
					Assign System Task
				</a>
			</div>
			@endpermission

			@permission('set.waypoint')
			<div class="col-md-1">	
				<a href="{{ route('structures.setwaypoint_system', $systemDetails->ss_system_id)}}" class="btn btn-warning" id="system_waypoints" title="Set Waypoints for all structures in this system. If the structure has a structure id. Check for orphans first! Boss use only please." data-toggle="tooltip" data-placement="top">
					Set Waypoints
				</a>
			</div>
			@endpermission

			<div class="col-md-1 pull-right">
				<a href="{{ route('solar.system_empty', $systemDetails->ss_system_id )}}" class="btn btn-danger active" id="system-empty">
					System is Empty
				</a>
			</div>
		</div>


		
	</div>
</div>
<div class="col-md-6">
	{!! $structures->appends(\Request::except('structures'))->render() !!}
</div>

@if(count($sov_structures))
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				System Sovereignty
				<div class="pull-right" style="vertical-align:middle;">
					<span class="glyphicon glyphicon-info-sign fa-1x" data-toggle="tooltip" title="Sovereignty Information" data-placement="left"></span>
				</div>

			</div>
			<div class="panel-body" >
				<table class="table activity-tracker" id="activity-tracker">
					<thead>
						<tr>
							<th>Structure Type</th>
							<th>Alliance Name</th>
							<th>Alliance Ticker</th>
							<th>ADM</th>
							<th>Vulnerablity Start</th>
							<th>Vulnerablity End</th>
							<th>Window</th>
						</tr>
					</thead>
					<tbody>

						@foreach($sov_structures as $sov)

						<tr>
							<td style="vertical-align: middle"><img class="img-circle" src="https://images.evetech.net/types/{{ $sov->structure_type_id }}/render?size=32">&nbsp;{!! $sov->structure_type_name !!}</td>
							<td style="vertical-align: middle"><img class="img-circle" src="https://images.evetech.net/alliances/{{ $sov->alliance_id }}/logo?size=32">&nbsp;{!! $sov->alliance_name !!}</td>
							<td style="vertical-align: middle">{{ $sov->alliance_ticker }}</td>
							@if($sov->vulnerability_occupancy_level == "")
							<td style="vertical-align: middle">Reinforced</td>
							<td style="vertical-align: middle">Reinforced</td>
							<td style="vertical-align: middle">Reinforced</td>
							@else
							<td style="vertical-align: middle">{{ $sov->vulnerability_occupancy_level }}</td>
							<td style="vertical-align: middle">{{ \Carbon\Carbon::parse($sov->vulnerable_start_time)->toDayDateTimeString() }}</td>
							<td style="vertical-align: middle">{{ \Carbon\Carbon::parse($sov->vulnerable_end_time)->toDayDateTimeString() }}</td>
							<td style="vertical-align: middle">{{ \Carbon\Carbon::parse($sov->vulnerable_start_time)->diff(\Carbon\Carbon::parse($sov->vulnerable_end_time))->format('%hh %im') }}</td>
							@endif
						</tr>
						@endforeach

					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
@endif




<div class="row">
	<div class="col-md-10">
		<div class="panel panel-default">
			<div class="panel-heading">Known Structures (<b>{{ $totalStructures }}</b>)
				<div class="pull-right" style="vertical-align:middle;">
					<span class="glyphicon glyphicon-info-sign fa-1x" data-toggle="tooltip" title="List of Known Structures, In This System" data-placement="left"></span>
				</div>
			</div>
			<div class="panel-body">
				<div class="table-responsive top-border-table" id="srp-table-wrapper">

					<table class="table" id="structures">
						<thead>
							<th>@sortablelink('str_name', 'Name')</th>
							<th>@sortablelink('str_type', 'Structure Type')</th>
							<th> Fitting Summary </th>
							<th>@sortablelink('str_vul_hour', 'Vulnerability Time')</th>
							@permission('deliver.package')
							<th>@sortablelink('str_package_delivered', 'Package Status')</th>
							@endpermission
							<th>@sortablelink('str_structure_id', 'Structure ID')</th>
							<th>@sortablelink('str_owner_corporation_name', 'Owner')</th>
							<th>@sortablelink('str_owner_alliance_name', 'Alliance')</th>
							<th>@sortablelink('str_value', 'Value')</th>
							<th>@sortablelink('str_state', 'State')</th>
							<th>@sortablelink('str_status', 'Status')</th>
							<th>@sortablelink('updated_at', 'Updated')</th>
							@permission('set.waypoint')
							<th>Set WayPoint</th>
							@endpermission

						</thead>
						<tbody>

							@if (isset($structures))              
							@foreach($structures as $structure)

							<tr>
								<td style="vertical-align: middle"><a href="{{ route('structures.view', $structure->str_structure_id_md5) }}">{{ $structure->str_name }}</a></td>
								<td style="vertical-align: middle"><img class="img-circle" src="https://images.evetech.net/types/{{ $structure->str_type_id }}/render?size=32">&nbsp;{{ $structure->str_type }}</td>



								<td style="vertical-align: middle; max-width:200px;">

									@if($structure->str_dooms_day)
									<span class="label label-danger">Dooms Day</span>
									@endif
									@if($structure->str_point_defense)
									<span class="label label-danger">Point Defense</span>
									@endif
									@if($structure->str_anti_cap)
									<span class="label label-danger">Anti Cap Fit</span>
									@endif
									@if($structure->str_anti_subcap)
									<span class="label label-danger">Anti Subcap Fit</span>
									@endif
									@if($structure->str_guide_bombs)
									<span class="label label-warning">Guided Bombs</span>
									@endif


									@if($structure->str_market)
									<span class="label label-primary">Market Hub</span>
									@endif
									@if($structure->str_cloning)
									<span class="label label-primary">Clone Bay</span>
									@endif
									@if($structure->str_capital_shipyard)
									<span class="label label-warning">Capital Production</span>
									@endif
									@if($structure->str_supercapital_shipyard)
									<span class="label label-danger">Titan Production</span>
									@endif
									@if($structure->str_hyasyoda)
									<span class="label label-primary">Hyasyoda</span>
									@endif
									@if($structure->str_invention)
									<span class="label label-primary">Invention</span>
									@endif
									@if($structure->str_manufacturing)
									<span class="label label-primary">Manufacturing</span>
									@endif
									@if($structure->str_research)
									<span class="label label-primary">Researching</span>
									@endif
									@if($structure->str_biochemical)
									<span class="label label-primary">Booster Production</span>
									@endif
									@if($structure->str_composite)
									<span class="label label-primary">Moon Reactions</span>
									@endif
									@if($structure->str_hybrid)
									<span class="label label-primary">Tech 3 Production</span>
									@endif
									@if($structure->str_moon_drilling)
									<span class="label label-primary">Moon Drilling</span>
									@endif
									@if($structure->str_reprocessing)
									<span class="label label-primary">Reprocessing</span>
									@endif
									@if($structure->str_t2_rigged)
									<span class="label label-success">T2 Rigged</span>
									@endif

								</td>

								<td style="vertical-align: middle">{{ $structure->str_vul_hour }}</td> 
								@permission('deliver.package')
								<td style="vertical-align: middle">

									@if ($structure->str_package_delivered === "Package Delivered")
									<span class="label label-success">{{ $structure->str_package_delivered }}</span>
									@elseif ($structure->str_package_delivered === "")
									<span class="label label-danger">No Package</span>
									@elseif($structure->str_package_delivered === "Package Vertified")
									<span class="label label-primary">Package Vertified</span>
									@else
									<span class="label label-danger">{{ $structure->str_package_delivered }}</span>
									@endif
								</td>
								@endpermission

								<td style="vertical-align: middle">{{ $structure->str_structure_id }}</td>
								@if($structure->str_owner_corporation_id > 1)
								<td style="vertical-align: middle"><a href="{{ route('corporation.view', $structure->str_owner_corporation_id )}}"><img class="img-circle" src="https://images.evetech.net/corporations/{{ $structure->str_owner_corporation_id }}/logo?size=32">&nbsp;{{ $structure->str_owner_corporation_name }}</a></td>
								@else
								<td></td>
								@endif
								@if($structure->str_owner_alliance_id > 1)
								<td style="vertical-align: middle"><a href="{{ route('alliance.view', $structure->str_owner_alliance_id )}}"><img class="img-circle" src="https://images.evetech.net/alliances/{{ $structure->str_owner_alliance_id }}/logo?size=32">&nbsp;{{ $structure->str_owner_alliance_name }}</a></td>
								@else
								<td></td>
								@endif
								<td style="vertical-align: middle">{{ number_format($structure->str_value,2) }}</td>

								<td style="vertical-align: middle">
									@if ($structure->str_state === "High Power")
									<span class="label label-success }}">High Power</span>
									@elseif ($structure->str_state === "Low Power")
									<span class="label label-danger }}">Low Power</span>
									@elseif ($structure->str_state === "Abandoned")
									<span class="label label-warning">Abandoned</span>
									@elseif ($structure->str_state === "Anchoring")
									<span class="label label-warning }}">Anchoring</span>
									@elseif ($structure->str_state === "Unanchoring")
									<span class="label label-primary }}">Unanchoring</span>
									@elseif ($structure->str_state === "Reinforced")
									<span class="label label-info }}">Reinforced</span>
									@else
									State Not Set
									@endif
								</td>


								<td style="vertical-align: middle">
									@if ($structure->str_status === "Unanchoring")
									<span class="label label-primary }}">Unanchoring</span>
									@elseif ($structure->str_status === "Armor")
									<span class="label label-warning }}"> Reinforced Armor</span>
									@elseif ($structure->str_status === "Hull")
									<span class="label label-danger }}"> Reinforced Hull</span>
									@else
									Status Not Set
									@endif

								</td>

								<td style="vertical-align: middle">{{ $structure->updated_at->diffForHumans() }}</td>
								@permission('set.waypoint')
								<td class="text-center" style="vertical-align: middle">
									<a href="{{ route('structures.setwaypoint', $structure->str_structure_id)}}" class="btn btn-success btn-circle edit" title="Set Waypoint" data-toggle="tooltip" data-placement="top">
										<i class="glyphicon glyphicon-play"></a></i>
									</a>
								</td>
								@endpermission

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
	</div>
	<div class="col-md-2">
		<div class="panel panel-default">
			<div class="panel-heading">
				Activity Tracker
				<div class="pull-right" style="vertical-align:middle;">
					<span class="glyphicon glyphicon-info-sign fa-1x" data-toggle="tooltip" title="Shows the last 5 Actions in this System." data-placement="left"></span>
				</div>

			</div>
			<div class="panel-body" >
				<table class="table activity-tracker" id="activity-tracker">
					<thead>
						<tr>
							<th>Username</th>
							<th>Action</th>
							<th>When</th>
						</tr>
					</thead>
					<tbody>

						@if(isset($actions))
						@foreach($actions as $action)

						<tr>
							<td>{{ $action->at_username }}</td>
							<td>{{ $action->at_action }}</td>
							<td>{{ $action->created_at->diffForHumans() }}</td>
						</tr>



						@endforeach


						@else

						<tr>
							<td colspan="6"><em>No Records Found</em></td>
						</tr>

						@endif
					</tbody>
				</table>
				{{ $actions->render() }}
			</div>

		</div>


	</div>



	<div class="col-md-2">
		<div class="panel panel-default">
			<div class="panel-heading">Paste a Dscan
				<div class="pull-right" style="vertical-align:middle;">
					<span class="glyphicon glyphicon-info-sign fa-1x" data-toggle="tooltip" title="Paste a DSCAN from a solar system, multiples may be required to cover entire system." data-placement="left"></span>
				</div>

			</div>
			<div class="panel-body">


				<form method="post" action="/dscan/system/{{ $systemDetails->ss_system_id }}" enctype="multipart/form-data">
					{{ csrf_field() }}
					<div class="form-group row">
						<div class="col-sm-12">
							<textarea name="title" type="text" class="form-control" id="dscan" placeholder="Go on... throw it on in.." rows="10"></textarea>
						</div>
					</div>
					<div class="form-group row">
						<div class="offset-sm-3 col-sm-9">
							<button type="submit" class="btn btn-primary">Submit</button>
						</div>
					</div>
				</form>


			</div>
		</div>
	</div>

	@permission('system.indices.view')
	<div class="col-md-12">
		<div class="panel panel-default indices-chart">
			<div class="panel-heading">6 Month History for System Indices</div>
			<div class="panel-body chart">
				<div>
					<canvas id="myChart" height="300"></canvas>
				</div>
			</div>
		</div>
		@endpermission

	</div>


</div>



@stop

@permission('system.indices.view')
@section('styles')
<style>
	.indices-chart .chart {
		zoom: 1.235;
	}
</style>
@stop
@endpermission



@section('scripts')


<script>

	$("#types").change(function () {
		$("#system-form").submit();
	});

	$("#no_per_page").change(function () {
		$("#system-form").submit();
	});



</script>
@permission('system.indices.view')
<script>
	var labels = {!! json_encode(array_keys($historyM)) !!};
	var manu = {!! json_encode(array_values($historyM)) !!};
	var rte = {!! json_encode(array_values($historyRTE)) !!};
	var rme = {!! json_encode(array_values($historyRME)) !!};
	var copy = {!! json_encode(array_values($historyC)) !!};
	var inv = {!! json_encode(array_values($historyI)) !!};
	var react = {!! json_encode(array_values($historyR)) !!};
</script>
{!! HTML::script('assets/js/chart.min.js') !!}
{!! HTML::script('assets/js/as/system_indices.history.js') !!}
@endpermission

@stop

