@extends('layouts.app')

@section('page-title', 'Entosis Manager | Overview')

@section('content')

<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
			Entosis Manager
			<small> - making it that little bit easier, because spreadsheets meh</small>
			<div class="pull-right">
				<ol class="breadcrumb">
					<li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
					<li class="active">Entosis Manager / Campaigns</li>
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
	<li class="active"><a data-toggle="tab" href="#active-campaigns">Active Campaigns</a></li>
	<li><a data-toggle="tab" href="#add-campaign">Add Campaign</a></li>
	<li><a data-toggle="tab" href="#completed">Completed Campaigns</a></li>
</ul>
<div class="tab-content">
	<div id="active-campaigns" class="tab-pane fade in active">
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-heading">Active Campaigns</div>
					<div class="panel-body">


						<div class="table-responsive top-border-table" id="campaigns-table-wrapper">
							<table class="table" id="active-campaigns">
								<thead>
									<th style="vertical-align: middle">No</th>
									<th style="vertical-align: middle">System</th>
									<th style="vertical-align: middle">Constellation</th>
									<th style="vertical-align: middle">Region</th>
									<th style="vertical-align: middle">Event Type</th>
									<th style="vertical-align: middle">Structure Type</th>
									<th style="vertical-align: middle">Availability</th>
									<th style="vertical-align: middle">Created By</th>
									<th style="vertical-align: middle">Created At</th>
									<th style="vertical-align: middle">View Campaign</th>
									<th style="vertical-align: middle">Finish Campaign</th>
								</thead>

								<tbody>

									
									@if (isset($active_campaigns))              
									@foreach($active_campaigns as $campaign)

									<tr>
										<td style="vertical-align: middle">{{ $campaign->id }}</td>
										
										<td style="vertical-align: middle"><a href="{{  route('solar.system', $campaign->ec_target_system_id )}}">{!! $campaign->ec_target_system !!}</a></td>
										<td style="vertical-align: middle"><a href="{{  route('solar.constellation', $campaign->ec_target_constellation_id )}}">{!! $campaign->ec_target_constellation !!}</a></td>
										<td style="vertical-align: middle"><a href="{{  route('solar.region', $campaign->ec_target_region_id )}}">{!! $campaign->ec_target_region !!}</a></td>
										<td style="vertical-align: middle">{!! $campaign->ec_event_type !!}</td>
										<td style="vertical-align: middle">{!! $campaign->ec_structure_type !!}</td>
										<td style="vertical-align: middle">{{ $campaign->ec_availability }}</td>
										<td style="vertical-align: middle">{!! $campaign->ec_campaign_created_by !!}</td>
										<td style="vertical-align: middle">{!! \Carbon\Carbon::parse($campaign->ec_campaign_created_at)->format('d M y, H:m:s') !!}</td>
										<td style="vertical-align: middle">
											<a href="{{ route('entosis.view_campaign', $campaign->ec_campaign_id) }}" class="label label-info" data-toggle="tooltip" data-placement="top">
												<span >View Campaign</span>
											</a>
										</td>
										<td style="vertical-align: middle">
											<a href="{{ route('entosis.complete', $campaign->id) }}" class="label label-success" data-toggle="tooltip" data-placement="top">
												<span >Finish Campaign</span>
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


					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<div id="add-campaign" class="tab-pane fade ">
	<div class="row">
		<div class="col-md-2">
			<div class="panel panel-default">
				<div class="panel-heading">Add Campaign</div>
				<div class="panel-body">

					<form method="post" action="{{ route('entosis.add_pending') }}" enctype="multipart/form-data">
						{{ csrf_field() }}
						<div class="panel-body" >
							<div class="col-md-12">


								<div class="form-group">
									<label for="system">Target Systemn</label>
									<input type="text" class="typeahead-systems form-control" name="system" id="system" placeholder="Search Systems" autocomplete="off" >
								</div>

								<div class="form-group">
									<label for="event_type">Event Type</label>
									{!! Form::select('event_type', $event_type, Input::get('event_type'), ['id' => 'event_type', 'class' => 'form-control']) !!}
								</div>

								<div class="form-group">
									<label for="structure_type">Structure Type</label>
									{!! Form::select('structure_type', $structure_type, Input::get('structure_type'), ['id' => 'structure_type', 'class' => 'form-control']) !!}
								</div>

								<div class="form-group">
									<label for="availability">Availability</label>
									{!! Form::select('availability', $availability, Input::get('availability'), ['id' => 'availability', 'class' => 'form-control']) !!}
								</div>

								<div class="form-group">
									<label for="tasks">Campaign Notes</label>
									<textarea name="notes" type="text" class="form-control" id="notes" placeholder="Notes
									" rows="5"></textarea>
								</div>

							</div>
						</div>

						<div class="form-group row">
							<div style="text-align: center;">
								<button type="submit" class="btn btn-success">Add Campaign</button>
							</div>
						</div>
					</form>
				</div>					
			</div>
		</div>
		<div class="col-md-10">
			<div class="panel panel-default">
				<div class="panel-heading">Pending Campaigns, ready for Dispatch
				</div>
				<div class="panel-body">
					<div class="table-responsive top-border-table" id="location-table-wrapper">

						<table class="table" id="pending-tasks">
							<thead>
								<th style="vertical-align: middle">No</th>
								
								<th style="vertical-align: middle">System</th>
								<th style="vertical-align: middle">Constellation</th>
								<th style="vertical-align: middle">Region</th>
								<th style="vertical-align: middle">Event Type</th>
								<th style="vertical-align: middle">Structure Type</th>
								<th style="vertical-align: middle">Availability</th>
								<th style="vertical-align: middle">Created By</th>
								<th style="vertical-align: middle">Created At</th>
								<th style="vertical-align: middle">Actions</th>
							</thead>

							<tbody>

								
								@if (isset($pending_campaigns))              
								@foreach($pending_campaigns as $campaign)

								<tr>
									<td style="vertical-align: middle"><a href="{{ $campaign->ec_campaign_id }} ">{!! $campaign->id !!}</a></td>
									<td style="vertical-align: middle"><a href="{{  route('solar.system', $campaign->ec_target_system_id )}}">{!! $campaign->ec_target_system !!}</a></td>
									<td style="vertical-align: middle"><a href="{{  route('solar.constellation', $campaign->ec_target_constellation_id )}}">{!! $campaign->ec_target_constellation !!}</a></td>
									<td style="vertical-align: middle"><a href="{{  route('solar.region', $campaign->ec_target_region_id )}}">{!! $campaign->ec_target_region !!}</a></td>
									<td style="vertical-align: middle">{!! $campaign->ec_event_type !!}</td>
									<td style="vertical-align: middle">{!! $campaign->ec_structure_type !!}</td>
									<td style="vertical-align: middle">{{ $campaign->ec_availability }}</td>
									<td style="vertical-align: middle">{!! $campaign->ec_campaign_created_by !!}</td>
									<td style="vertical-align: middle">{!! \Carbon\Carbon::parse($campaign->ec_campaign_created_at)->format('d M y, H:m:s') !!}</td>
									<td style="vertical-align: middle">
										<a href="{{ route('entosis.dispatch', $campaign->id) }}" class="label label-success" data-toggle="tooltip" data-placement="top">
											<span >Dispatch</span>
										</a>
										
										
										<a href="{{ route('entosis.remove_from_dispatch', $campaign->id) }}" class="label label-danger" data-toggle="tooltip" data-placement="top">
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
				<div class="panel-heading">Completed Campaigns</div>
				<div class="panel-body">


					<table class="table" id="pending-tasks">
						<thead>
							<th style="vertical-align: middle">No</th>
							<th style="vertical-align: middle">System</th>
							<th style="vertical-align: middle">Constellation</th>
							<th style="vertical-align: middle">Region</th>
							<th style="vertical-align: middle">Event Type</th>
							<th style="vertical-align: middle">Structure Type</th>
							<th style="vertical-align: middle">Availability</th>
							<th style="vertical-align: middle">Created By</th>
							<th style="vertical-align: middle">Created At</th>
							<th style="vertical-align: middle">Summary</th>
						</thead>

						<tbody>

							
							@if (isset($completed_campaigns))              
							@foreach($completed_campaigns as $campaign)

							<tr>
								<td style="vertical-align: middle">{{ $campaign->id }}</td>
								<td style="vertical-align: middle"><a href="{{  route('solar.system', $campaign->ec_target_system_id )}}">{!! $campaign->ec_target_system !!}</a></td>
								<td style="vertical-align: middle"><a href="{{  route('solar.constellation', $campaign->ec_target_constellation_id )}}">{!! $campaign->ec_target_constellation !!}</a></td>
								<td style="vertical-align: middle"><a href="{{  route('solar.region', $campaign->ec_target_region_id )}}">{!! $campaign->ec_target_region !!}</a></td>
								<td style="vertical-align: middle">{!! $campaign->ec_event_type !!}</td>
								<td style="vertical-align: middle">{!! $campaign->ec_structure_type !!}</td>
								<td style="vertical-align: middle">{{ $campaign->ec_availability }}</td>
								<td style="vertical-align: middle">{!! $campaign->ec_campaign_created_by !!}</td>
								<td style="vertical-align: middle">{!! \Carbon\Carbon::parse($campaign->ec_campaign_created_at)->format('d M y, H:m:s') !!}</td>
								<td style="vertical-align: middle">
									<a href="#" class="label label-success" data-toggle="tooltip" data-placement="top">
										<span >Summary</span>
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

{!! HTML::script('assets/js/moment.min.js') !!}
{!! HTML::script('assets/js/bootstrap-datetimepicker.min.js') !!}
@stop
