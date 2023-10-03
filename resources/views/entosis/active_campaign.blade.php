@extends('layouts.app')

@section('page-title', 'Entosis Manager | Overview')

@section('content')

<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
			Active Campaign
			<small> - {{ $campaign->ec_campaign_id }}</small>
			<div class="pull-right">
				<ol class="breadcrumb">
					<li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
					<li class="active">Entosis Manager / Campaign / {{ $campaign->ec_campaign_id }}</li>
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
	<li class="active"><a data-toggle="tab" href="#campaign">Campaign Summary</a></li>
	<li><a data-toggle="tab" href="#scouts">Scouts</a></li>
	<li><a data-toggle="tab" href="#hackers">Hackers</a></li>
	<li><a data-toggle="tab" href="#admin">Command & Control</a></li>
	<li><a data-toggle="tab" href="#completed-nodes">Completed Nodes</a></li>
</ul>
<div class="tab-content">
	<div id="campaign" class="tab-pane fade in active">
		
		<div class="row">
			<div class="col-md-3">
				<div class="panel panel-default">
					<div class="panel-heading">
						Campaign Details
					</div>
					<div class="panel-body">
						<table class="table" id="campaign-details">
							<thead>
								<tr>
									<th colspan="3">Campaign Details</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>Campaign ID</td>
									<td style="vertical-align: middle">{!! $campaign->ec_campaign_id !!}</td>
								</tr>
								<tr>
									<td>System</td>
									<td style="vertical-align: middle"><a href="http://evemaps.dotlan.net/map/{{ str_replace(" ", "_", $campaign->ec_target_region) }}/{{  $campaign->ec_target_system }}#adm" target="_blank">{!! $campaign->ec_target_system !!}</a></td>
								</tr>
								<tr>
									<td>Constellation</td>
									<td style="vertical-align: middle"><a href="http://evemaps.dotlan.net/map/{{ str_replace(" ", "_", $campaign->ec_target_region) }}/{{  $campaign->ec_target_constellation }}#adm" target="_blank">{!! $campaign->ec_target_constellation !!}</a></td>
								</tr>

								<tr>
									<td>Region</td>
									<td style="vertical-align: middle"><a href="http://evemaps.dotlan.net/map/{{ str_replace(" ", "_", $campaign->ec_target_region) }}#adm" target="_blank">{!! $campaign->ec_target_region !!}</a></td>
								</tr>
								<tr>
									<td>Event Type</td>
									<td style="vertical-align: middle">{!! $campaign->ec_event_type !!}</td>
								</tr>
								<tr>
									<td>Structure Type</td>
									<td style="vertical-align: middle">{!! $campaign->ec_structure_type !!}</td>
								</tr>
								<tr>
									<td>Availability</td>
									<td style="vertical-align: middle">{{ $campaign->ec_availability }}</td>
								</tr>
								@if($campaign->ec_status = 1)
								<tr>
									<td>Status</td>
									<td style="vertical-align: middle">Active</td>
								</tr>
								@elseif($campaign->ec_status = 2)
								<tr>
									<td>Status</td>
									<td style="vertical-align: middle">Completed</td>
								</tr>
								@endif
								<tr>
									<td>Created By</td>
									<td style="vertical-align: middle">{!! $campaign->ec_campaign_created_by !!}</td>
								</tr>
								<tr>
									<td>Created</td>
									<td style="vertical-align: middle">{!! \Carbon\Carbon::parse($campaign->ec_campaign_created_at)->diffForHumans() !!}</td>
								</tr>

							</tbody>
						</table>
					</div>				
				</div>
			</div>
		</div>

	</div>



	<div id="scouts" class="tab-pane fade ">
		<div class="row">
			<div class="col-md-3">
				<div class="panel panel-default">
					<div class="panel-heading">Register Character as Scout</div>
					<div class="panel-body">

						<form method="post" action="{{ route('entosis.register_scout_to_campaign', $campaign->ec_campaign_id )}}" enctype="multipart/form-data">
							{{ csrf_field() }}
							<div class="form-group">
								<label for="character">Characters</label>
								{!! Form::select('character', $select_characters, Input::get('character'), ['id' => 'character', 'class' => 'form-control']) !!}
							</div>

							<div class="form-group row">
								<div style="text-align: center;">
									<button type="submit" class="btn btn-info">Register Character</button>
								</div>
							</div>
						</form>

						<table class="table" id="campaign-details">
							<thead>
								<tr>
									<th colspan="3">Registered Characters</th>
								</tr>
							</thead>
							<tbody>


								@if (isset($scouts))              
								@foreach($scouts as $scout)
								<tr>
									<td style="vertical-align: middle"><img class="img-circle" src="https://imageserver.eveonline.com/Character/{{ $scout->es_character_id }}_32.jpg">{{ $scout->es_character_name }}</td>
									<td style="vertical-align: middle"><img class="img-circle" src="https://imageserver.eveonline.com/Alliance/{{ $scout->es_character_alliance_id }}_32.png">{{ $scout->es_character_alliance_name }}</td>
									<td class="text-center">
										<a href="#" class="btn btn-danger btn-circle edit" title="Remove Character"
										data-toggle="tooltip" data-placement="top">
										<i class="glyphicon glyphicon-remove"></i>
									</a>
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
		<div class="col-md-2">
			<div class="panel panel-default">
				<div class="panel-heading">Report Node</div>
				<div class="panel-body">
					<form method="post" action="{{ route('entosis.add_node_to_campaign', $campaign->ec_campaign_id )}}" enctype="multipart/form-data" id="scout-report-node">
						{{ csrf_field() }}
						<div class="panel-body" >
							<div class="col-md-12">


								<div class="form-group">
									<label for="spawn_system">System</label>
									{!! Form::select('spawn_system', $spawn_systems, Input::get('spawn_system'), ['id' => 'spawn_system', 'class' => 'form-control']) !!}
								</div>

								<div class="form-group">
									<label for="node">Node ID</label>
									<input type="text" class=" form-control" name="node" id="node" placeholder="Insert Node ID" autocomplete="off" >
								</div>

								<div class="form-group">
									<label for="hostile_present">Being Hacked By Hostile</label>
									{!! Form::select('hostile_present', ['No', 'Yes'], Input::get('hostile_present'), ['id' => 'hostile_present', 'class' => 'form-control']) !!}
								</div>
							</div>
						</div>

						<div class="form-group row">
							<div style="text-align: center;">
								<button type="submit" class="btn btn-success">Add Node</button>
							</div>
						</div>
					</form>

				</div>					
			</div>
		</div>
		<div class="col-md-7">
			<div class="panel panel-default">
				<div class="panel-heading">Previous Nodes Reported
				</div>
				<div class="panel-body">
					<table class="table" id="hackers-nodes">
						<thead>
							<tr>
								<th>Node ID</th>
								<th>System</th>
								<th>Created At</th>
							</tr>
						</thead>
						<tbody>

							<tr>
								<td style="vertical-align: middle">-id-</td>
								<td style="vertical-align: middle">-system-</td>
								<td style="vertical-align: middle">-status-</td>

							</tr>

						</tbody>
					</table>

				</div>


			</div>
		</div>
	</div>
</div>

<div id="hackers" class="tab-pane fade ">
	<div class="row">
		<div class="col-md-3">
			<div class="panel panel-default">
				<div class="panel-heading">Register Character as Hacker</div>
				<div class="panel-body">
					<form method="post" action="{{ route('entosis.register_hacker_to_campaign', $campaign->ec_campaign_id) }}" enctype="multipart/form-data">
						{{ csrf_field() }}
						<div class="form-group">
							<label for="character">Characters</label>
							{!! Form::select('character', $select_characters, Input::get('character'), ['id' => 'character', 'class' => 'form-control']) !!}
						</div>


						<div class="form-group row">
							<div style="text-align: center;">
								<button type="submit" class="btn btn-info">Register Character</button>
							</div>
						</div>
					</form>

					
					<table class="table" id="campaign-details">
						<thead>
							<tr>
								<th colspan="3">Registered Characters</th>
							</tr>
						</thead>
						<tbody>


							@if (isset($hackers))              
							@foreach($hackers as $hacker)
							<tr>
								<td style="vertical-align: middle"><img class="img-circle" src="https://imageserver.eveonline.com/Character/{{ $hacker->eh_character_id }}_32.jpg">{{ $hacker->eh_character_name }}</td>
								<td style="vertical-align: middle"><img class="img-circle" src="https://imageserver.eveonline.com/Alliance/{{ $hacker->eh_character_alliance_id }}_32.png">{{ $hacker->eh_character_alliance_name }}</td>
								<td class="text-center">
									<a href="#" class="btn btn-danger btn-circle edit" title="Remove Character"
									data-toggle="tooltip" data-placement="top">
									<i class="glyphicon glyphicon-remove"></i>
								</a>
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

	<div class="col-md-9">
		<div class="panel panel-default">
			<div class="panel-heading">Nodes allocated to you for hacking. 
			</div>
			<div class="panel-body">

				<table class="table" id="scouted-nodes">
					<thead>
						<tr>
							<th>Node ID</th>
							<th>System</th>
							<th>Allocated Character</th>
							<th>Status Update</th>
							<th>Estimated Completion (EVE Time)</th>
							<th>Current Status</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td style="vertical-align: middle">-id-</td>
							<td style="vertical-align: middle">-system-</td>
							<td style="vertical-align: middle">-character-</td>
							<td style="vertical-align: middle">
								<form method="post" action="#" enctype="multipart/form-data">
									{{ csrf_field() }}

									{!! Form::select('character', ['1, On My Way', '2, Ready to Go', '3, Warm Up Cycle', '4, Pause', '5, Complete'], Input::get('character'), ['id' => 'character', 'class' => 'form-control']) !!}

								</form>
							</td>
							<td style="vertical-align: middle">
								<form method="post" action="#" enctype="multipart/form-data">
									{{ csrf_field() }}

									{!! Form::select('finish_time', ['Time Picker'], Input::get('finish_time'), ['id' => 'finish_time', 'class' => 'form-control']) !!}

								</form>
							</td>
							<td style="vertical-align: middle">-status-</td>
						</tr>

					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
</div>
<div id="admin" class="tab-pane fade ">
	<div class="row">
		<div class="col-md-3">
			<div class="panel panel-default">

				
				<div class="panel-heading">Scouted Nodes, Ready to be allocated</div>

				<div class="panel-body">

					<table class="table" id="command-registered-nodes">
						<thead>
							<tr>
								<th>Node ID</th>
								<th>System</th>
								<th>Reported By</th>
							</tr>
						</thead>
					</table>
				</div>		
			</div>
		</div>


	<div class="col-md-2">
		<div class="panel panel-default">
			<div class="panel-heading">Registered Scouts
			</div>
			<div class="panel-body">
				<table class="table" id="command-registered-scouts">
					<thead>
						<tr>
							<th>Character</th>
							<th>Location</th>
							<th>Ship</th>
						</tr>
					</thead>
					<tbody>

					</tbody>
				</table>
			</div>
		</div>


	</div>
	<div class="col-md-12">
	</div>
	
	
	
</div>
</div>
<div id="completed-nodes" class="tab-pane fade ">
	<div class="row">
		<div class="col-md-4">
			<div class="panel panel-default">
				<div class="panel-heading">Completed Nodes</div>
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
	$(document).ready(function() {
		// On Load, Load registered hackers/scouts
		load_registered_hackers();
		load_registered_scouts();
		load_registered_nodes();

		setInterval(function() {
			// Now call registered hackers/scouts route to update every 5 seconds.
			load_registered_hackers();
			load_registered_scouts();
			load_registered_nodes();
			
		}, 5000);
	});

	function load_registered_scouts() {
		$.ajax({
			url: '{{ route('entosis.view_campaign_registered_scouts', $campaign->ec_campaign_id) }}',
			dataType: 'json',

			success: function (data) {
				$('#command-registered-scouts tr').not(':first').remove();
				var html = '';
				for(var i = 0; i < data.length; i++){

					html += '<tr>'+
					'<td style="vertical-align: middle"><img class="img-circle" src="https://imageserver.eveonline.com/Character/' + data[i].es_character_id +  '_32.jpg">' + ' ' + data[i].es_character_name + '</td>' +
					'<td style="vertical-align: middle">' + data[i].es_location_system_name + '</td>' +
					'<td style="vertical-align: middle"><a href="#" title="' + data[i].es_ship_type_name +  '"data-toggle="tooltip" data-placement="right"><img class="img-circle" src="https://imageserver.eveonline.com/Type/' + data[i].es_ship_type_id + '_32.png"></a></td>' +
					'</tr>';
				} 

				$('#command-registered-scouts tr').first().after(html);
			},
			error: function (data) {
			}
		});
	}

	function load_registered_hackers() {
		$.ajax({
			url: '{{ route('entosis.view_campaign_registered_hackers', $campaign->ec_campaign_id) }}',
			dataType: 'json',

			success: function (data) {
				$('#command-registered-hackers tr').not(':first').remove();
				var html = '';
				for(var i = 0; i < data.length; i++){
					html += '<tr>'+
					'<td style="vertical-align: middle"><img class="img-circle" src="https://imageserver.eveonline.com/Character/' + data[i].eh_character_id +  '_32.jpg">' + ' ' + data[i].eh_character_name + '</td>' +
					'<td style="vertical-align: middle"><a href="#" title="' + data[i].eh_character_alliance_name +  '"data-toggle="tooltip" data-placement="right"><img class="img-circle" src="https://imageserver.eveonline.com/Alliance/' + data[i].eh_character_alliance_id + '_32.png"></a></td>' +
					'<td style="vertical-align: middle">' + data[i].eh_location_system_name + '</td>' +
					'<td style="vertical-align: middle"><a href="#" title="' + data[i].eh_ship_type_name +  '"data-toggle="tooltip" data-placement="right"><img class="img-circle" src="https://imageserver.eveonline.com/Type/' + data[i].eh_ship_type_id + '_32.png"></a></td>' +
					'<td style="vertical-align: middle">' + data[i].eh_registered_at + '</td>' +
					'</tr>';
				} 

				$('#command-registered-hackers tr').first().after(html);
			},
			error: function (data) {
			}
		});
	}

	function load_registered_nodes() {
		$.ajax({
			url: '{{ route('entosis.view_campaign_registered_nodes', $campaign->ec_campaign_id) }}',
			dataType: 'json',

			success: function (data) {
				$('#command-registered-nodes tr').not(':first').remove();
				var html = '';
				for(var i = 0; i < data.length; i++){
					html += '<tr>'+

					'<td style="vertical-align: middle">' + data[i].en_node_id + '</td>' +
					'<td style="vertical-align: middle">' + data[i].en_node_system_name + '</td>' +
					'<td style="vertical-align: middle">' + data[i].en_added_by_username + '</td>' +
					'</tr>';
				} 

				$('#command-registered-nodes tr').first().after(html);
			},
			error: function (data) {
			}
		});
	}

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

{!! HTML::script('assets/js/moment.min.js') !!}
{!! HTML::script('assets/js/bootstrap-datetimepicker.min.js') !!}
@stop
