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

@include('partials.messages')

@if($structure->str_destroyed == 1)
<div class="alert alert-danger" role="alert">
	{{ $structure->str_name }} has been marked as destroyed.
</div>
@endif

@if($structure->str_structure_id < 1)
<div class="alert alert-warning" role="alert">
	<b>{{ $structure->str_name }} - This structure needs meta data, update it before you do ANYTHING, if you are unsure, speak up in recon jabber.</b>
</div>
@endif

@permission('structure.hitlist')
@if($structure->str_hitlist == 1)
<div class="alert alert-danger" role="alert">
	<b>{{ $structure->str_name }} - is on the Hitlist, go and kill it already!</b>
</div>
@endif
@endpermission

@permission('structure.merge')
@if(count($duplicate_structures) > 1)
<div class="alert alert-warning" role="alert">
	<b>This structure has been flagged as a duplicate</b><br>
	
	<br><b>This structure has had a name change and the database has duplicate structure ids.
	<br>Before merging, please ensure the recon member has not incorrectly inputted wrong structure meta data, if they have, this can be resolved by inputting the proper meta data.
	<br>
	<br><a href="{{ route('merge.view', $structure->str_structure_id_md5 )}}">Click her to merge the new named structure with old data.</a></b>
	
</div>
@endif
@endpermission

@if($structure->str_destroyed == 2)
<div class="alert alert-warning" role="alert">
	
	@permission('set.waypoint')
	
	<a href="{{ route('structures.setwaypoint', $structure->str_structure_id)}}" class="btn btn-success btn-circle edit" title="Set Waypoint" data-toggle="tooltip" data-placement="top">
		<i class="glyphicon glyphicon-play"></a></i>
	</a>
	
	@endpermission

	{{ $structure->str_name }} has been marked as destroyed by the system, please validate by setting a waypoint, then mark as destroyed.
</div>
@endif


<div class="row tab-search">
	<div class="col-md-5"></div>
</div>

<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">Structure Actions
				<div class="pull-right" style="vertical-align:middle;">
					<span class="glyphicon glyphicon-info-sign fa-1x" data-toggle="tooltip" title="4 Categories of Actions, State/Status/Fitting/Destroy. Select Required to Update Structure Information." data-placement="top"></span>

				</div>
			</div>
			<div class="panel-body">
				<div class="col-md-12">	

					@if($structure->str_structure_id < 1)
										<div class="col-md-1">
						<a href="{{ route('structures.destroy', $structure->str_structure_id_md5) }}" class="btn btn-danger active" id="destroy">
							Destroy
						</a>
					</div>
					@else


					@permission('structure.hitlist')
					@if($structure->str_hitlist == 1)
					<div class="col-md-1">	
						<a href="{{ route('structures.hitlist_remove', $structure->str_structure_id_md5) }}" class="btn btn-danger" id="hitlist_remove" data-toggle="tooltip" title="Remove from the Hitlist">
							<i class="glyphicon glyphicon-minus"></i>
						</a>
					</div>
					@else
					<div class="col-md-1">	
						<a href="{{ route('structures.hitlist_add', $structure->str_structure_id_md5) }}" class="btn btn-danger" id="hitlist_add" data-toggle="tooltip" title="Add to Hitlist">
							<i class="glyphicon glyphicon-flag"></i>
						</a>
					</div>
					@endif
					@endpermission
					<div class="col-md-1">	
						<a href="{{ route('structures.state_high_power', $structure->str_structure_id_md5) }}" class="btn btn-success" id="state_high_power">
							High Power
						</a>
					</div>
					<div class="col-md-1">	
						<a href="{{ route('structures.state_low_power', $structure->str_structure_id_md5) }}" class="btn btn-danger" id="state_low_power">
							Low Power
						</a>
					</div>
					<div class="col-md-1">	
						<a href="{{ route('structures.state_abandoned', $structure->str_structure_id_md5) }}" class="btn btn-warning active" id="state_abandoned">
							Abandoned
						</a>
					</div>
					<div class="col-md-1">	
						<a href="{{ route('structures.state_anchoring', $structure->str_structure_id_md5) }}" class="btn btn-warning" id="anchoring">
							Anchoring
						</a>
					</div>
					<div class="col-md-1">
						<a href="{{ route('structures.status_unanchoring', $structure->str_structure_id_md5) }}" class="btn btn-primary" id="unanchoring">
							Unanchoring
						</a>
					</div>

					@if($structure->str_cored === "" || $structure->str_cored === "No")
					<div class="col-md-1">
						<a href="{{ route('structures.cored', $structure->str_structure_id_md5) }}" class="btn btn-success" id="cored">
							Has a Core
						</a>
					</div>
					@else
					<div class="col-md-1">
						<a href="{{ route('structures.cored', $structure->str_structure_id_md5) }}" class="btn btn-danger" id="no-cored">
							Has no Core
						</a>
					</div>
					@endif

					@if($structure->str_status === "")
					<div class="col-md-1">
						<a href="{{ route('structures.status_reinforced', $structure->str_structure_id_md5) }}" class="btn btn-success" id="reinforced-armor">
							Reinforce Armor
						</a>
					</div>
					@elseif ($structure->str_status === "Armor")
					<div class="col-md-1">
						<a href="{{ route('structures.status_reinforced', $structure->str_structure_id_md5) }}" class="btn btn-danger" id="reinforced-hull">
							Reinforce Hull
						</a>
					</div>
					@else
					<div class="col-md-1">
						<a href="{{ route('structures.status_reinforced', $structure->str_structure_id_md5) }}" class="btn btn-warning" id="reinforced-armor">
							Reinforce Armor
						</a>
					</div>
					@endif



					<div class="col-md-1">
						<a href="{{ route('structures.status_reinforced_clear', $structure->str_structure_id_md5) }}" class="btn btn-info" id="reinforce-clear">
							Clear Reinforcement
						</a>
					</div>



					@permission('deliver.package')
					@if($structure->str_package_delivered === "")
					<div class="col-md-1">
						<a href="{{ route('structures.package_delivered', $structure->str_structure_id_md5) }}" class="btn btn-success" id="deliver_packaged">
							Deliver Package
						</a>
					</div>
					@elseif ($structure->str_package_delivered === "Package Removed")
					<div class="col-md-1">
						<a href="{{ route('structures.package_delivered', $structure->str_structure_id_md5) }}" class="btn btn-success" id="deliver_packaged">
							Deliver Package
						</a>
					</div>
					@elseif ($structure->str_package_delivered === "Package Vertified")
					<div class="col-md-1">
					</div>
					@else
					<div class="col-md-1">
						<a href="{{ route('structures.package_delivered', $structure->str_structure_id_md5) }}" class="btn btn-danger" id="remove_packaged">
							Remove Package
						</a>
					</div>
					@endif
					@endpermission


					@if($structure->str_has_no_fitting === "")
					<div class="col-md-1">
						<a href="{{ route('structures.fitting', $structure->str_structure_id_md5) }}" class="btn btn-success" id="fitted">
							Fitted
						</a>
					</div>
					@elseif ($structure->str_has_no_fitting === "No Fitting")
					<div class="col-md-1">
						<a href="{{ route('structures.fitting', $structure->str_structure_id_md5) }}" class="btn btn-success" id="no-fitted">
							Fitted
						</a>
					</div>
					@else
					<div class="col-md-1">
						<a href="{{ route('structures.fitting', $structure->str_structure_id_md5) }}" class="btn btn-danger" id="no-fitting">
							No Fitting
						</a>
					</div>
					@endif



					
					<div class="col-md-1">	
					</div>
					<div class="col-md-1">
						<a href="{{ route('structures.destroy', $structure->str_structure_id_md5) }}" class="btn btn-danger active" id="destroy">
							Destroy
						</a>
					</div>
					@endif
				</div>
			</div>
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





		<div class="col-md-3">
			<div class="panel panel-default">
				<div class="panel-heading">Structure Info Link
					<div class="pull-right" style="vertical-align:middle;">
						<span class="glyphicon glyphicon-info-sign fa-1x" data-toggle="tooltip" title="Copy the structure information from ingame and paste in this box to allocate owners." data-placement="top"></span>
					</div>
				</div>

				<div class="panel-body">


					<form method="post" action="/dscan/structure/{{ $structure->str_structure_id_md5 }}" enctype="multipart/form-data">
						{{ csrf_field() }}
						<div class="form-group row">
							<div class="col-sm-12">
								<textarea name="title" type="text" class="form-control" id="dscan" placeholder="[14:24:53] scopehone > <url=showinfo:35835//1029852136388>1DQ1-A - Keepstar (Corporation)</url>
									" rows="3"></textarea>
								</div>
							</div>
							<div class="form-group row">
								<div class="offset-sm-3 col-sm-9">
									<button type="submit" class="btn btn-primary">Submit Meta Data</button>
								</div>
							</div>
						</form>

					</div>
				</div>
			</div>


			@if($structure->str_structure_id > 1)
			<div class="col-md-3">
				<div class="panel panel-default">
					<div class="panel-heading">Parse Fitting
						<div class="pull-right" style="vertical-align:middle;">
							<span class="glyphicon glyphicon-info-sign fa-1x" data-toggle="tooltip" title="Use a ship scanner and paste the contents into the fitting box." data-placement="top"></span>
						</div>
					</div>
					<div class="panel-body">


						<form method="post" action="/dscan/structure/fitting/{{ $structure->str_structure_id_md5 }}" enctype="multipart/form-data">
							{{ csrf_field() }}
							<div class="form-group row">
								<div class="col-sm-12">
									<textarea name="title" type="text" class="form-control" id="dscan" placeholder="Paste Scan from Ship Scanner for Citadel Fit." rows="3"></textarea>
								</div>
							</div>
							<div class="form-group row">
								<div class="offset-sm-3 col-sm-9">
									<button type="submit" class="btn btn-primary">Submit Fitting</button>
								</div>
							</div>
						</form>

					</div>
				</div>
			</div>

			<div class="col-md-3">
				<div class="panel panel-default">
					<div class="panel-heading">Vulnerability Setting
						<div class="pull-right" style="vertical-align:middle;">
							<span class="glyphicon glyphicon-info-sign fa-1x" data-toggle="tooltip" title="Select Day and Time of the Vulnerability Window, Submit." data-placement="top"></span>
						</div>

					</div>
					<div class="panel-body">


						<form method="post" action="/structure/vulnerability/{{ $structure->str_structure_id_md5 }}" enctype="multipart/form-data">
							{{ csrf_field() }}
							<div class="panel-body" >
								<table class="table user-activity" id="vul-timer">
									<thead>

										<tr>
											<th style="vertical-align: middle">Time</th>
											<th style="vertical-align: middle">
												{!! Form::select('vul_hour', $vul_hour, Input::get('vul_hour'), ['id' => 'vul_hour', 'class' => 'form-control']) !!}
											</th>
										</tr>
									</thead>
									<tbody>
									</tbody>
								</table>
							</div>

							<div class="form-group row">
								<div class="offset-sm-3 col-sm-9">
									<button type="submit" class="btn btn-primary">Submit Vulnerability Window</button>
								</div>
							</div>
						</form>

					</div>
				</div>
			</div>


			<div class="col-md-5">
				<div class="panel panel-default">
					<div class="panel-heading">
						Structure Fitting
						<div class="pull-right" style="vertical-align:middle;">
							<span class="glyphicon glyphicon-info-sign fa-1x" data-toggle="tooltip" title="Fitting is automatically parsed, input fitting in the Parse Fitting box from an ingame scan." data-placement="left"></span>
						</div>

					</div>
					<div class="panel-body" >
						<table class="table user-activity" id="structure-fitting">
							<thead>
								<tr>
									<th>Module</th>
									<th>Value</th>
								</tr>
							</thead>
							<tbody>

								@if(!is_null(json_decode($structure->str_fitting)))
								@foreach(json_decode($structure->str_fitting) as  $index => $module)

								<tr>
									<td><img class="img-circle" src="https://image.eveonline.com/Type/{{ $module->type_id }}_32.png">&nbsp;{{ $module->name }}</td>
									<td style="vertical-align: middle">{{ number_format($module->price, 2) }}</td>
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

			@if($structure->str_moon_drilling == 1)
			<div class="col-md-4">
				<div class="panel panel-default">
					<div class="panel-heading">Moon
						<div class="pull-right" style="vertical-align:middle;">
							<span class="glyphicon glyphicon-info-sign fa-1x" data-toggle="tooltip" title="Select which moon this structure is anchored on." data-placement="top"></span>
						</div>

					</div>
					<div class="panel-body">
						<form method="post" action="/structure/moon_anchored/{{ $structure->str_structure_id_md5 }}" enctype="multipart/form-data">
							{{ csrf_field() }}
							<div class="panel-body" >
								<table class="table user-activity" id="vul-timer">
									<thead>
										<tr>

											<th style="vertical-align: middle">
												Select Moon
											</th>

											<th style="vertical-align: middle">
												{!! Form::select('moon', $moon, Input::get('moon'), ['id' => 'moon', 'class' => 'form-control']) !!}
											</th>
										</tr>
									</thead>
									<tbody>
									</tbody>
								</table>
							</div>

							<div class="form-group row">
								<div class="offset-sm-3 col-sm-9">
									<button type="submit" class="btn btn-primary">Allocate Moon</button>
								</div>
							</div>
						</form>

					</div>
				</div>
			</div>
			@endif
			@endif

			<div class="col-md-4">
				<div class="panel panel-default">
					<div class="panel-heading">
						Activity Tracker
						<div class="pull-right" style="vertical-align:middle;">
							<span class="glyphicon glyphicon-info-sign fa-1x" data-toggle="tooltip" title="Shows last 10 events for the structure." data-placement="left"></span>
						</div>

					</div>
					<div class="panel-body">
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
									<td>{{ $action->created_at }} : {{ \Carbon\Carbon::parse($action->created_at)->diffForHumans() }}</td>
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
		</div>

	</div>
</div>



@stop
@section('scripts')
<script>
	$(document).ready(function(){
		$('#structure-fitting').DataTable( {
			"paging":   false,
			"searching": true,
			"pageLength": 50,
			"sorting" :  [[ 0, "asc" ]]
		}
		);

	});
</script>
@stop