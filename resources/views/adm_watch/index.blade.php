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
					<li class="active">ADM Watch / Overview</li>
				</ol>
			</div>

		</h1>
	</div>
</div>

@include('partials.messages')
</div>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">Systems that have been marked for monitoring ADMs

				@permission('adm_watch.manage')
				<div class="pull-right">
					<a href="{{ route('adm_watch.manage')}}">Manage</a>
				</div>
				@endpermission
			</div>

			<div class="panel-body">


				<div class="table-responsive top-border-table" id="location-table-wrapper">

					<table class="table" id="watching-systems">
						<thead>
							<th style="vertical-align: middle">System</th>
							<th style="vertical-align: middle">Constellation</th>
							<th style="vertical-align: middle">Region</th>
							<th style="vertical-align: middle">ADM</th>
							<th style="vertical-align: middle">Owner</th>
							@permission('adm_watch.manage')
							<th style="vertical-align: middle">Delete</th>
							@endpermission
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
								@permission('adm_watch.manage')
								<td style="vertical-align: middle">
									<a href="{{ route('adm_watch.remove_from_dispatch', $system->adm_system_id) }}" class="label label-danger" data-toggle="tooltip" data-placement="top">
										<span >Remove</span>
									</a>
								</td>
								@endpermission
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
				{!! $watching_systems->render() !!}
			</div>
		</div>
	</div>
</div>
</div>

</div>

@stop

@section('scripts')

<script>
	$(document).ready(function(){
		$('#watching-systems').DataTable( {
			"paging":   false,
			"searching": true,
			"pageLength": 500,
			"order": [[ 3, "asc" ]]
		}
		);

	});
</script>

@stop
