@extends('layouts.app')

@section('page-title', $structure->str_name)

@section('content')

<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
			{{ $structure->str_name }}
			<small> - structure information</small>
			<div class="pull-right">
				<ol class="breadcrumb">
					<li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
					<li><a href="{{ route('structures.index') }}">Structures</a></li>
					<li><a href="{{ route('solar.system', $structure->str_system_id )}}">{{ $structure->str_system }}</a></li>
					<li class="active">{{ $structure->str_name }}</li>
				</ol>
			</div>

		</h1>
	</div>
</div>
<div class="row">
	@include('partials.messages')

	@if($structure->str_destroyed == 1)
	<div class="alert alert-danger" role="alert">
		{{ $structure->str_name }} has been marked as destroyed.
	</div>
	@endif

	<div class="alert alert-warning" role="alert">
		<b>This structure id has multiple entries, would you like to merge all data into this new structure.
			<br>All data including previous activity tracking, current status/fit etc, will be merged into this structure.
			<br>
			<br>Please select which structure from the table you would like to merge all data with for this new structure.
			<br>When the merge is taking place, the owner/fitting of the new structure will be transferred to the existing structure data on file, make sure the recon members have updated the structure completely before merging.
		</b>
	</div>
		<div class="alert alert-danger" role="alert">
			<b>Only merge structures that are older than the current structure in the database.</b>
	</div>
</div>
</div>


<div class="row">
	<div class="col-md-3">
		<div id="edit-user-panel" class="panel panel-default">
			<div class="panel-heading">
				<a href="{{ route('structures.view', $structure->str_structure_id_md5) }}">{{ $structure->str_name }}</a> - 
				@permission('set.waypoint')

				<a href="{{ route('structures.setwaypoint', $structure->str_structure_id)}}" title="Set Waypoint" data-toggle="tooltip" data-placement="top">
					Set Way Point.
				</a>

				@endpermission
				<div class="pull-right" style="vertical-align:middle;">
					<span class="glyphicon glyphicon-info-sign fa-1x" data-toggle="tooltip" title="Upwell Structure Summary" data-placement="top"></span>
				</div>
			</div>

			<div class="panel-body panel-profile">
				<div class="image">
					<img alt="image" class="img-circle avatar" src="https://images.evetech.net/types/{{ $structure->str_type_id }}/render?size=64">

				</div>
				<div class="name"><strong>{{ $structure->str_type }}</strong></div>
				<div class="fitting-labels">

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

				</div>


				<td>

					<table class="table table-hover table-details">
						<thead>
							<tr>
								<th colspan="3">Structure Informations</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>Structure Hash</td>
								<td>{{ $structure->str_structure_id_md5 }}</td>
							</tr>
							<tr>
								<td>Structure ID</td>
								@if ($structure->str_structure_id == "")
								<td>Input Structure Meta Data</td>
								@else
								<td>{{ $structure->str_structure_id }}</td>
								@endif
							</tr>
							<tr>
								<td>Type</td>
								<td>{{ $structure->str_type }}</td>
							</tr>

							<tr>
								<td>Size</td>
								<td>{{ $structure->str_size }}</td>
							</tr>
							<tr>
								<td style="vertical-align: middle">System</td>
								<td style="vertical-align: middle"><a href="{{ route('solar.system', $structure->str_system_id )}}">{{ $structure->str_system }}</a></td>
							</tr>
							<tr>

								<td>Region</td>
								<td><a href="{{ route('solar.region', $structure->str_region_id )}}">{{ $structure->str_region_name }}</a></td>
							</tr>

							<tr>
								<td style="vertical-align: middle">Owner</td>


								@if ($structure->str_owner_corporation_name == "")
								<td>
									No Corporation
								</td>
								@else
								<td style="vertical-align: middle"><a href="{{ route('corporation.view', $structure->str_owner_corporation_id )}}"><img class="img-circle" src="https://images.evetech.net/corporations/{{ $structure->str_owner_corporation_id }}/logo?size=32">&nbsp;{{ $structure->str_owner_corporation_name }}</a>
								</td>
								@endif

							</tr>


							<tr>
								<td style="vertical-align: middle">Alliance</td>

								@if ($structure->str_owner_alliance_name === "")
								<td>
									No Alliance
								</td>
								@else
								<td style="vertical-align: middle"><a href="{{ route('alliance.view', $structure->str_owner_alliance_id )}}"><img class="img-circle" src="https://images.evetech.net/alliances/{{ $structure->str_owner_alliance_id }}/logo?size=32">&nbsp;{{ $structure->str_owner_alliance_name }} ({{ $structure->str_owner_alliance_ticker }})</a>

								</td>
								@endif
							</tr>


							<tr>
								<td style="vertical-align: middle">Standings</td>
								@if($structure->str_standings <= 10 && $structure->str_standings >= 5)
									<td style="vertical-align: middle"><span class="label label-primary">{{ $structure->str_standings }}</span></td>
									@elseif($structure->str_standings <= 5 && $structure->str_standings >= 0)
										<td style="vertical-align: middle"><span class="label label-info">{{ $structure->str_standings }}</span></td>
										@elseif($structure->str_standings <= 0 && $structure->str_standings >= -5)
											<td style="vertical-align: middle"><span class="label label-warning">{{ $structure->str_standings }}</span></td>
											@else
											<td style="vertical-align: middle"><span class="label label-danger">{{ $structure->str_standings }}</span></td>
											@endif
										</tr>


										<tr>
											<td>State</td>
											<td>

												@if ($structure->str_state === "High Power")
												<span class="label label-success }}">High Power</span>
												@elseif ($structure->str_state === "Low Power")
												<span class="label label-danger }}">Low Power</span>
												@elseif ($structure->str_state === "Abandoned")
												<span class="label label-warning }}">Abandoned</span>
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
										</tr>
										<tr>
											<td>Status</td>
											<td>

												@if ($structure->str_status === "Unanchoring")
												<span class="label label-primary }}">Unanchoring</span>
												@elseif ($structure->str_status === "Armor")
												<span class="label label-warning }}"> Reinforced Armor</span>
												@elseif ($structure->str_status === "Hull")
												<span class="label label-danger }}"> Reinforced Hull</span>
												@else
												-
												@endif

											</td>
										</tr>
										<tr>
											<td>Abandoned Time</td>
											@if ($structure->str_abandoned_time === "")
											<td>-</td>
											@else
											<td>

												<td">{{ $structure->str_abandoned_time }} : {{ \Carbon\Carbon::parse($structure->str_abandoned_time)->diffForHumans() }}</td>

												</td>
												@endif
											</tr>
											<tr>
												<td>Vulnerability Time</td>
												@if ($structure->str_vul_hour === "")
												<td>-</td>
												@else
												<td>
													<b>{{ $structure->str_vul_hour }}</b> <br>
												</td>
												@endif
											</tr>

											<tr>
												<td style="vertical-align: middle">Fitted</td>
												<td>
													@if ($structure->str_has_no_fitting === "No Fitting")
													<span class="label label-danger }}">{{ $structure->str_has_no_fitting }}</span>
													@else
													<span class="label label-success }}">{{ $structure->str_has_no_fitting }}</span>
													@endif
												</td>
											</tr>

											<tr>
												<td style="vertical-align: middle">Cored</td>
												<td>
													@if($structure->str_cored === "" || $structure->str_cored === "No")
													<span class="label label-danger">Does not have a Core present</span>
													@else
													<span class="label label-success">Has a Core</span>
													@endif
												</td>
											</tr>

											<tr>
												<td>Fitting Value</td>
												<td><b>{{ number_format($structure->str_value,2) }} isk</b></td>
											</tr>
											@permission('deliver.package')
											<tr>
												<td>Package Status</td>
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
											</tr>
											@endpermission

											@if($structure->str_moon_drilling == 1)
											<td>Anchored Moon</td>
											@if ($structure->str_moon === "")
											<td>-</td>
											@else
											<td>
												@permission('moon.view.moons')
												<a href="{{ route('moons.view_moon', $structure->str_moon)}}" target="_blank">
													@endpermission
													{{ $structure->str_moon }}
													@permission('moon.view.moons')
												</a>
												@endpermission
											</td>
											@endif


											<tr>
												<td>Moon Rarity</td>
												@if ($structure->str_moon === "")
												<td>-</td>
												@else
												<td>R{!! $moon_data->moon_r_rating !!}</td>
												@endif
											</tr>
											@endif


											<tr>
												<td>Created</td>
												<td>{{ $structure->created_at }} : {{ $structure->created_at->diffForHumans() }}</td>
											</tr>
											<tr>
												<td>Last Updated</td>
												<td>{{ $structure->updated_at }} : {{ $structure->updated_at->diffForHumans() }}</td>
											</tr>
										</tbody>
									</table>


								</div>

							</div>
						</div>





						<div class="col-md-9">
							<div class="panel panel-default">
								<div class="panel-heading">
									Duplicate Structures
									<div class="pull-right" style="vertical-align:middle;">
										<span class="glyphicon glyphicon-info-sign fa-1x" data-toggle="tooltip" title=".. place holder..." data-placement="left"></span>
									</div>

								</div>
								<div class="panel-body">
									<table class="table duplicate-structures" id="duplicate-structures">
										<thead>
											<tr>
												<th>Structure Name</th>
												<th>Structure Id</th>
												<th>Type</th>
												<th>Owner</th>
												<th>Alliance</th>
												<th>Fitting Value</th>
												<th>System</th>
												<th>Created</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody>

											@if(isset($duplicate_structures))
											@foreach($duplicate_structures as $dup_structure)

											<tr>
												<td><a href="{{ route('structures.view', $dup_structure->str_structure_id_md5) }}">{{ $dup_structure->str_name }}</a></td>
												<td>{{ $dup_structure->str_structure_id }}</td>
												<td>{{ $dup_structure->str_type }}</td>
												@if ($structure->str_owner_corporation_name == "")
												<td>
													No Corporation
												</td>
												@else
												<td><a href="{{ route('corporation.view', $structure->str_owner_corporation_id )}}"><img class="img-circle" src="https://images.evetech.net/corporations/{{ $structure->str_owner_corporation_id }}/logo?size=32">&nbsp;{{ $dup_structure->str_owner_corporation_name }}</a>
												</td>
												@endif
												@if ($dup_structure->str_owner_alliance_name === "")
												<td>
													No Alliance
												</td>
												@else
												<td><a href="{{ route('alliance.view', $dup_structure->str_owner_alliance_id )}}"><img class="img-circle" src="https://images.evetech.net/alliances/{{ $dup_structure->str_owner_alliance_id }}/logo?size=32">&nbsp;{{ $dup_structure->str_owner_alliance_name }} ({{ $dup_structure->str_owner_alliance_ticker }})</a>
												</td>
												@endif
												<td><b>{{ number_format($dup_structure->str_value,2) }} isk</b></td>
												<td><a href="{{ route('solar.system', $dup_structure->str_system_id )}}">{{ $dup_structure->str_system }}</a></td>
												<td>{{ $dup_structure->created_at }} : {{ \Carbon\Carbon::parse($dup_structure->created_at)->diffForHumans() }}</td>

												<td>
													<a href="{{ route('merge.structure', [$dup_structure->str_structure_id_md5, $structure->str_structure_id_md5]) }}" class="btn btn-warning" id="merge_structure">Merge</a>
													<a href="{{ route('merge.structure_with_fit', [$dup_structure->str_structure_id_md5, $structure->str_structure_id_md5]) }}" class="btn btn-success" id="merge_structure">Merge With Fit</a>
												</td>
											</tr>

											@endforeach
											@else

											<tr>
												<td colspan="6"><em>No Duplicate Records Found</em></td>
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