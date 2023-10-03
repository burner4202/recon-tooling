@extends('layouts.app')

@section('page-title', 'ADM Watch | Overview')

@section('content')

<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
			ADM Watch
			<small> -hurf and blurf kill and mine all the things. (Updated Hourly)</small>
			<div class="pull-right">
				<ol class="breadcrumb">
					<li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
					<li><a href="{{ route('adm_watch.index') }}">ADM Watch</a></li>
					<li class="active">Manage</li>
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

<ul class="nav nav-tabs" id="adm_watch-manager">
	<li class="active"><a data-toggle="tab" href="#overview">Overview</a></li>
	<li><a data-toggle="tab" href="#watching">Watching</a></li>
</ul>

<div class="tab-content">
	<div id="overview" class="tab-pane fade in active">
		<div class="row">
			<div class="col-md-3">
				<div class="panel panel-default">
					<div class="panel-heading">Add Stuff to Watch ADMs</div>
					<div class="panel-body">

						<form method="post" action="{{ route('adm_watch.add_to_pending') }}" enctype="multipart/form-data">
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

								</div>
							</div>

							<div class="form-group row">
								<div style="text-align: center;">
									<button type="submit" class="btn btn-success">Monitor ADMs</button>
								</div>
							</div>
						</form>
					</div>					
				</div>
			</div>
			<div class="col-md-9">
				<div class="panel panel-default">
					<div class="panel-heading">Systems Ready to Dispatch
					</div>
					<div class="panel-body">
						<div class="table-responsive top-border-table" id="location-table-wrapper">

							<table class="table" id="pending-systems">
								<thead>
									<th style="vertical-align: middle">System</th>
									<th style="vertical-align: middle">Constellation</th>
									<th style="vertical-align: middle">Region</th>
									<th style="vertical-align: middle">ADM</th>
									<th style="vertical-align: middle">Owner</th>
									<th style="vertical-align: middle">Created at</th>
									<th style="vertical-align: middle">Dispatch
										<br>
										<a href="{{ route('adm_watch.dispatch_all') }}" class="label label-success" data-toggle="tooltip" data-placement="top">
											<span >Dispatch All</span>
										</a>
									</th>
									<th style="vertical-align: middle">Remove
										<br>
										<a href="{{ route('adm_watch.remove_all') }}" class="label label-danger" data-toggle="tooltip" data-placement="top">
											<span >Remove All</span>
										</a>
									</th>
								</thead>

								<tbody>

									@if (isset($pending_systems))              
									@foreach($pending_systems as $system)

									<tr>
										<td style="vertical-align: middle"><a href="{{  route('solar.system', $system->adm_system_id )}}">{!! $system->adm_system_name !!}</a></td>
										<td style="vertical-align: middle"><a href="{{  route('solar.constellation', $system->adm_constellation_id )}}">{!! $system->adm_constellation_name !!}</a></td>
										<td style="vertical-align: middle"><a href="{{  route('solar.region', $system->adm_region_id )}}">{!! $system->adm_region_name !!}</a></td>
										<td style="vertical-align: middle">{!! $system->vulnerability_occupancy_level !!}</a></td>
										<td style="vertical-align: middle"><a href="{{ route('alliance.view', $system->alliance_id )}}"><img class="img-circle" src="https://images.evetech.net/alliances/{{ $system->alliance_id }}/logo?size=32">&nbsp;{{ $system->alliance_name }} ({{ $system->alliance_ticker }})</a></td>
										<td style="vertical-align: middle">{!! \Carbon\Carbon::parse($system->created_at)->format('d M y, H:m:s') !!}</td>
										<td style="vertical-align: middle">
											<a href="{{ route('adm_watch.dispatch', $system->adm_system_id) }}" class="label label-success" data-toggle="tooltip" data-placement="top">
												<span >Dispatch</span>
											</a>
										</td>
										<td style="vertical-align: middle">
											<a href="{{ route('adm_watch.remove_from_dispatch', $system->adm_system_id) }}" class="label label-danger" data-toggle="tooltip" data-placement="top">
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
						{!! $pending_systems->fragment('home')->render() !!}
					</div>

				</div>


			</div>
		</div>
	</div>



</div>
<div id="watching" class="tab-pane fade">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading">Watching systems</div>
				<div class="panel-body">


					<div class="table-responsive top-border-table" id="location-table-wrapper">

						<table class="table" id="watching-systems">
							<thead>
								<th style="vertical-align: middle">System</th>
								<th style="vertical-align: middle">Constellation</th>
								<th style="vertical-align: middle">Region</th>
								<th style="vertical-align: middle">ADM</th>
								<th style="vertical-align: middle">Owner</th>
								<th style="vertical-align: middle">Delete</th>
							</thead>

							<tbody>

								@if (isset($watching_systems))              
								@foreach($watching_systems as $system)

								<tr>
									<td style="vertical-align: middle"><a href="{{  route('solar.system', $system->adm_system_id )}}">{!! $system->adm_system_name !!}</a></td>
									<td style="vertical-align: middle"><a href="{{  route('solar.constellation', $system->adm_constellation_id )}}">{!! $system->adm_constellation_name !!}</a></td>
									<td style="vertical-align: middle"><a href="{{  route('solar.region', $system->adm_region_id )}}">{!! $system->adm_region_name !!}</a></td>
									<td style="vertical-align: middle">{!! $system->vulnerability_occupancy_level !!}</a></td>
									<td style="vertical-align: middle"><a href="{{ route('alliance.view', $system->alliance_id )}}"><img class="img-circle" src="https://images.evetech.net/alliances/{{ $system->alliance_id }}/logo?size=32">&nbsp;{{ $system->alliance_name }} ({{ $system->alliance_ticker }})</a></td>
									<td style="vertical-align: middle">
										<a href="{{ route('adm_watch.remove_from_dispatch', $system->adm_system_id) }}" class="label label-danger" data-toggle="tooltip" data-placement="top">
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
					{!! $watching_systems->fragment('watching')->render() !!}
				</div>
			</div>
		</div>
	</div>
</div>

</div>

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

<script>
	$(document).ready(function(){
		$('#pending-systems').DataTable( {
			"paging":   false,
			"searching": true,
			"pageLength": 500,
			"order": [[ 0, "desc" ]]
		}
		);

		$('#watching-systems').DataTable( {
			"paging":   false,
			"searching": true,
			"pageLength": 500,
			"order": [[ 0, "desc" ]]
		}
		);

	});
</script>

@stop
