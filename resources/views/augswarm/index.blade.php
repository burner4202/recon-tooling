@extends('layouts.app')

@section('page-title', 'Augswarm | Tracking')

@section('content')

<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
			Augswarm Tracking
			<small> - I need a fucking cyno! not now, right now!</small>
			<div class="pull-right">
				<ol class="breadcrumb">
					<li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
					<li><a href="{{ route('administration.index') }}">Administration</a></li>
					<li class="active">Augswarm Tracking / Overview</li>
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

<ul class="nav nav-tabs" id="augswarm">
	<li class="active"><a data-toggle="tab" href="#overview">Augswarm Tracking</a></li>
</ul>

<div class="tab-content">
	<div id="overview" class="tab-pane fade in active">
		<div class="row">
						<div class="col-md-8 ">
				<div class="panel panel-default">
					<div class="panel-heading">Augswarm Tracking Information</div>
					<div class="panel-body">
						<b>
						<p>Add characters you wish to track.</p>
						<p>The system will update every 5 minutes or you can force an update.</p>
						<p>If you wish to stop tracking, this can be done via the remove button.</p>
						<p></p>
					</b>
						<div class="col-md-1">	
						<a href="{{ route('augswarm.update') }}" class="btn btn-danger" id="force_update" data-toggle="tooltip" title="Force Update">
							Force Update
						</a>
					</div>

					</div>					
				</div>
			</div>

			<div class="col-md-4">
				<div class="panel panel-default">
					<div class="panel-heading">Add Augswarm to Track</div>
					<div class="panel-body">

						<form method="post" action="{{ route('augswarm.create') }}" enctype="multipart/form-data">
							{{ csrf_field() }}
							<div class="panel-body" >
								<div class="col-md-12">


									<div class="form-group">
										<label for="search">Character Name</label>
										<input type="text" class=" form-control" name="search" id="search" placeholder="Add A Character" autocomplete="off" >
									</div>

								</div>
							</div>

							<div class="form-group row">
								<div style="text-align: center;">
									<button type="submit" class="btn btn-success">Add Character</button>
								</div>
							</div>
						</form>
					</div>					
				</div>
			</div>
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-heading">Live Augswarms
					</div>
					<div class="panel-body">
						<div class="table-responsive top-border-table" id="location-table-wrapper">

							<table class="table" id="pending-tasks">
								<thead>
									<th style="vertical-align: middle">Character Name</th>
									<th style="vertical-align: middle">Corporation</th>
									<th style="vertical-align: middle">System</th>
									<th style="vertical-align: middle">Constellation</th>
									<th style="vertical-align: middle">Region</th>
									<th style="vertical-align: middle">Last Login</th>
									<th style="vertical-align: middle">Last Logout</th>
									<th style="vertical-align: middle">Online</th>
									<th style="vertical-align: middle">Logins</th>
									<th style="vertical-align: middle">Ship Name</th>
									<th style="vertical-align: middle">Ship Type</th>
									<th style="vertical-align: middle">Last Update</th>
									<th style="vertical-align: middle">Action</th>
								</thead>

								<tbody>

									@if (isset($augswarms))              
									@foreach($augswarms as $character)

									<tr>
										<td><a href="#"><img class="img-circle" src="https://imageserver.eveonline.com/Character/{{ $character->at_character_id }}_32.jpg">&nbsp;{{ $character->esi_character_name }}</a></td>
										<td><img class="img-circle" src="https://imageserver.eveonline.com/Corporation/{{ $character->esi_corporation_id }}_32.png">&nbsp;{{ $character->esi_corporation_name }}</a></td>
										<td style="vertical-align: middle"><a href="{{  route('solar.system', $character->ss_system_id )}}">{{ $character->ss_system_name }}</a></td>
										<td style="vertical-align: middle"><a href="{{  route('solar.constellation', $character->ss_constellation_id )}}">{{ $character->ss_constellation_name }}</a></td>
										<td style="vertical-align: middle"><a href="{{  route('solar.region', $character->ss_region_id )}}">{{ $character->ss_region_name }}</a></td>
										<td style="vertical-align: middle">{{ $character->at_last_login }} : {{ \Carbon\Carbon::parse($character->at_last_login)->diffForHumans() }}</a></td>
										<td style="vertical-align: middle">{{ $character->at_last_logout }} : {{ \Carbon\Carbon::parse($character->at_last_logout)->diffForHumans() }}</a></td>
										<td style="vertical-align: middle">
											@if ($character->at_online)
											<span class="label label-success }}">Online</span>
											@else
											<span class="label label-danger }}">Offline</span>
											@endif
										</td>
										<td style="vertical-align: middle">{{ $character->at_logins }}</a></td>
										<td style="vertical-align: middle">{!! $character->at_ship_name !!}</a></td>
										<td style="vertical-align: middle">{!! $character->at_ship_type_id_name !!}</a></td>
										<td style="vertical-align: middle">{{ $character->at_last_updated }} : {{ \Carbon\Carbon::parse($character->at_last_updated)->diffForHumans() }}</a></td>
										<td style="vertical-align: middle">
											<a href="{{ route('augswarm.remove', $character->at_character_id) }}" class="label label-danger" data-toggle="tooltip" data-placement="top">
												<span >Remove</span>
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
				$('#augswarm a[href="' + activeTab + '"]').tab('show');
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
