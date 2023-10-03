@extends('layouts.app')

@section('page-title', $alliance->alliance_name)

@section('content')

<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
			{{ $alliance->alliance_name }}
			<small></small>
			<div class="pull-right">
				<ol class="breadcrumb">
					<li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
					<li><a href="{{ route('alliances.index') }}">Alliances</a></li>
					<li class="active">{{ $alliance->alliance_name }}</li>
				</ol>
			</div>

		</h1>
	</div>
</div>


<div class="container col-md-12">
	<ul class="nav nav-tabs" id="alliances">
		<li class="active"><a data-toggle="tab" href="#alliance">Home</a></li>
		<li><a data-toggle="tab" href="#corporations">Corporations</a></li>
		<li><a data-toggle="tab" href="#fleet-commanders">Fleet Commanders</a></li>
		@permission('stager.view')
		<li><a data-toggle="tab" href="#staging">Staging Systems</a></li>
		@endpermission
		@permission('dossier.view')
		<li><a data-toggle="tab" href="#associated-groups">Associated Groups</a></li>
		<li><a data-toggle="tab" href="#dossiers">Group Dossiers</a></li>
		@endpermission
		
	</ul>
	<div class="tab-content">
		<div id="alliance" class="tab-pane fade in active">
			<div class="row">
				<div class="col-md-3">
					<div id="edit-user-panel" class="panel panel-default">
						<div class="panel-heading">
							Alliance Information
						</div>
						<div class="panel-body panel-profile">
							<div class="image">
								<img alt="image" class="img-circle avatar" src="https://imageserver.eveonline.com/Alliance/{{ $alliance->alliance_alliance_id }}_128.png">
							</div>
							<div class="name"><a href="{{ route('structures.index')}}?search=&alliance={!! $alliance->alliance_name !!}&sort=str_value&direction=desc" target="_blank"><strong>{{ $alliance->alliance_name }}</strong></a></div>
							<br>


							<table class="table table-hover">
								<thead>
									<tr>
										<th colspan="3">Information</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>Ticker</td>
										<td>{{ $alliance->alliance_ticker }}</td>
									</tr>
									<tr>
										<td>Corporations</td>
										<td>{{ $totalCorporations }}</td>
									</tr>

								</tbody>

								<thead>
									<tr>
										<th colspan="3">Intelligence (Structure Data)</th>
									</tr>
								</thead>
								<tbody>

									<tr>
										<td>Home</td>
										@if(isset($home_region))
										<td>{{ $home_region->str_system }} - {{ $home_region->str_region_name }}</td>
										@else
										<td>No Data - Requires 1 Keepstar In Database.</td>
										@endif
									</tr>
									
									<tr>
										<td>Total Structures</td>
										<td>{{ $structures->count() }}</td>
									</tr>
									<tr>
										<td>Destroyed</td>
										<td>
											{{ $structures->where('str_destroyed', 1)->count() }}
										</td>
									</tr>
									<tr>
										<td>Abandoned</td>
										<td>
											{{ $structures->where('str_state', 'Abandoned')->count() }}
										</td>
									</tr>
									<tr>
										<td>Alive</td>
										<td>{{ $structures->where('str_destroyed', 0)->count() }}</td>
									</tr>
									<tr>
										<td>Online Structures</td>
										<td>{{ $structures->where('str_destroyed', 0)->where('str_state', 'High Power')->count() }}</td>
									</tr>

									<tr>
										<td>Keepstar</td>
										<td>
											<a href="{{ route('structures.index')}}?search=&alliance={!! $alliance->alliance_name !!}&sort=str_value&type=Keepstar&direction=desc">
												{{ $structures->where('str_destroyed', 0)->where('str_type', 'Keepstar')->count() }}
											</a>
										</td>

									</tr>

									<tr>
										<td>Super Production</td>
										<td>
											<a href="{{ route('structures.index')}}?search=&alliance={!! $alliance->alliance_name !!}&sort=str_value&titan_production=on&direction=desc">
												{{ $structures->where('str_destroyed', 0)->where('str_supercapital_shipyard', 1)->count() }}
											</a>
										</td>
									</tr>

									<tr>
										<td>Capital Production</td>
										<td>
											<a href="{{ route('structures.index')}}?search=&alliance={!! $alliance->alliance_name !!}&sort=str_value&cap_production=on&direction=desc">
												{{ $structures->where('str_destroyed', 0)->where('str_capital_shipyard', 1)->count() }}
											</a>
										</td>
									</tr>

									<tr>
										<td>T2 Rigged Reactions</td>
										<td>
											<a href="{{ route('structures.index')}}?search=&alliance={!! $alliance->alliance_name !!}&sort=str_value&t2rigged=on&direction=desc">
												{{ $structures->where('str_destroyed', 0)->where('str_t2_rigged', 1)->where('str_composite', 1)->count() }}
											</a>
										</td>
									</tr>

									<tr>
										<td>Moon Drills</td>
										<td>
											<a href="{{ route('structures.index')}}?search=&alliance={!! $alliance->alliance_name !!}&sort=str_value&moon_drilling=on&direction=desc">
												{{ $structures->where('str_destroyed', 0)->where('str_moon_drilling', 1)->count() }}
											</a>
										</td>

									</tr>

									<tr>
										<td>Fitting Value (Alive)</td>
										<td>{{ number_format($total_alive->sum('str_value'),2) }}</td>
									</tr>

									<tr>
										<td>Fitting Value (Destroyed)</td>
										<td>{{ number_format($total_destroyed->sum('str_value'),2) }}</td>
									</tr>

								</tbody>

								<thead>
									<tr>
										<th colspan="3">Sovereignty</th>
									</tr>
								</thead>
								<tbody>	
									<tr>
										<td>Infrastructure Hubs</td>
										<td>
											
											{{ $sov->where('structure_type_name', 'Infrastructure Hub')->count() }}

										</td>
									</tr>

									<tr>
										<td>Territorial Claim Unit</td>
										<td>
											
											{{ $sov->where('structure_type_name', 'Territorial Claim Unit')->count() }}

										</td>
									</tr>

									<tr>
										<td>Super Building Systems</td>
										<td>
											
											{{ $sov->where('structure_type_name', 'Infrastructure Hub')->where('supers_in_system', '1')->count() }}

										</td>
									</tr>

									<tr>
										<td>Jump Bridge Systems</td>
										<td>
											
											{{ $sov->where('structure_type_name', 'Infrastructure Hub')->where('bridge_in_system', '1')->count() }}

										</td>
									</tr>

									<tr>
										<td>ADM > 5</td>
										<td>

											{{ $sov->where('structure_type_name', 'Infrastructure Hub')->where('vulnerability_occupancy_level', '>=', '5')->count() }}

										</td>
									</tr>

									<tr>
										<td>ADM < 5</td>
										<td>

											{{ $sov->where('structure_type_name', 'Infrastructure Hub')->where('vulnerability_occupancy_level', '<', '5')->count() }}

										</td>
									</tr>

									<tr>
										<td>ADM < 3</td>
										<td>

											{{ $sov->where('structure_type_name', 'Infrastructure Hub')->where('vulnerability_occupancy_level', '<', '3')->count() }}

										</td>
									</tr>


									<tr>
										<td>Sov Index</td>
										<td>
											<a href="{{ route('sovereignty.index') }}?search={!! $alliance->alliance_name !!}" target="_blank">
												Here
											</a>
										</td>
									</tr>

								</tbody>
							</table>
						</div>


					</div>
				</div>

				<div class="col-md-3">
					<div class="panel panel-default">
						<div class="panel-heading">Keepstars (Top 10 by Value)</div>
						<div class="panel-body">
							@if (isset($keepstars))              
							@foreach($keepstars as $structure)

							<div class="user media">
								<div class="media-left">
									<a href="{{ route('structures.view', $structure->str_structure_id_md5) }}">
										<img class="media-object img-circle avatar" src="https://image.eveonline.com/Type/{{ $structure->str_type_id }}_32.png">
									</a>
								</div>
								<div class="media-body">
									<h5 class="media-heading">{{ $structure->str_name }}</h5>
									<div row="col-md-12">
										<div class="col-md-2">
											System: <br>
											Region:<br>
											Owner: <br>
											Value:<br>

										</div>
										<div class="col-md-10">
											<a href="{{ route('solar.system', $structure->str_system_id )}}">{{ $structure->str_system }}</a><br>
											{{ $structure->str_region_name }}<br>
											<a href="{{ route('corporation.view', $structure->str_owner_corporation_id )}}">{{ $structure->str_owner_corporation_name }}</a><br>
											{{ number_format($structure->str_value,2) }} isk<br>
										</div>
									</div>
								</div>
							</div>
							@endforeach
							@else
							None Scanned
							@endif
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="panel panel-default">
						<div class="panel-heading">Sotiyos (Top 10 by Value)</div>
						<div class="panel-body">
							@if (isset($soytios))              
							@foreach($soytios as $structure)

							<div class="user media">
								<div class="media-left">
									<a href="{{ route('structures.view', $structure->str_structure_id_md5) }}">
										<img class="media-object img-circle avatar" src="https://image.eveonline.com/Type/{{ $structure->str_type_id }}_32.png">
									</a>
								</div>
								<div class="media-body">
									<h5 class="media-heading">{{ $structure->str_name }}</h5>
									<div row="col-md-12">
										<div class="col-md-2">
											System: <br>
											Region:<br>
											Owner: <br>
											Value:<br>

										</div>
										<div class="col-md-10">
											<a href="{{ route('solar.system', $structure->str_system_id )}}">{{ $structure->str_system }}</a><br>
											{{ $structure->str_region_name }}<br>
											<a href="{{ route('corporation.view', $structure->str_owner_corporation_id )}}">{{ $structure->str_owner_corporation_name }}</a><br>
											{{ number_format($structure->str_value,2) }} isk<br>
										</div>
									</div>
								</div>
							</div>
							@endforeach
							@else
							None Scanned
							@endif
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="panel panel-default">
						<div class="panel-heading">Azbels (Top 10 by Value)</div>
						<div class="panel-body">
							@if (isset($azbels))              
							@foreach($azbels as $structure)

							<div class="user media">
								<div class="media-left">
									<a href="{{ route('structures.view', $structure->str_structure_id_md5) }}">
										<img class="media-object img-circle avatar" src="https://image.eveonline.com/Type/{{ $structure->str_type_id }}_32.png">
									</a>
								</div>
								<div class="media-body">
									<h5 class="media-heading">{{ $structure->str_name }}</h5>
									<div row="col-md-12">
										<div class="col-md-2">
											System: <br>
											Region:<br>
											Owner: <br>
											Value:<br>

										</div>
										<div class="col-md-10">
											<a href="{{ route('solar.system', $structure->str_system_id )}}">{{ $structure->str_system }}</a><br>
											{{ $structure->str_region_name }}<br>
											<a href="{{ route('corporation.view', $structure->str_owner_corporation_id )}}">{{ $structure->str_owner_corporation_name }}</a><br>
											{{ number_format($structure->str_value,2) }} isk<br>
										</div>
									</div>
								</div>
							</div>
							@endforeach
							@else
							None Scanned
							@endif
						</div>
					</div>
				</div>
			</div>
		</div>

		<div id="corporations" class="tab-pane fade">
			<div class="row">
				<div class="col-md-12">
					<div class="panel panel-default">
						<div class="panel-heading">Corporations</div>
						<div class="panel-body">
							<div class="table-responsive top-border-table" id="srp-table-wrapper">
								<table class="table" id="corporations">
									<thead>
										<th>@sortablelink('corp_name','Corporation Name')</th>
										<th>@sortablelink('ticker', 'Ticker')</th>
										<th>@sortablelink('member_count', 'Member Count')</th>
										<th>@sortablelink('updated_at', 'Updated')</th>
									</thead>

									<tbody>
										@if (isset($corporations))              
										@foreach($corporations as $corporation)
										<tr>
											<td style="vertical-align: middle"><a href="{{ route('corporation.view', $corporation->corporation_corporation_id )}}"><img class="img-circle" src="https://images.evetech.net/corporations/{{ $corporation->corporation_corporation_id  }}/logo?size=32">&nbsp;{{ $corporation->corporation_name }}</a></td>
											<td style="vertical-align: middle">{{ $corporation->corporation_ticker }}</td>
											<td style="vertical-align: middle">{{ $corporation->corporation_member_count }}</td>
											<td style="vertical-align: middle">{{ $corporation->updated_at->diffForHumans() }}</td>
										</tr>
										@endforeach
										@else
										<tr>
											<td colspan="6"><em>No Records Found</em></td>
										</tr>
										@endif

										{!! $corporations->fragment('corporations')->appends(\Request::except('corporations'))->render() !!}
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div id="structures" class="tab-pane fade">
			<div class="row">
				<div class="col-md-12">
					<div class="panel panel-default">
						<div class="panel-heading">Known Structures</div>
						<table class="table" id="structures">
							<thead>
								<th> @sortablelink('str_name', 'Structure Name')</th>
								<th> @sortablelink('str_type', 'Type')</th>
								<th> @sortablelink('str_state', 'State')</th>
								<th> @sortablelink('str_status', 'Status')</th>
								<th> @sortablelink('str_system', 'System')</th>
								<th> @sortablelink('str_region_name', 'Region')</th>
								<th> @sortablelink('str_owner_corporation_name', 'Corporation Owner')</th>
								<th> @sortablelink('str_owner_alliance_name', 'Alliance')</th>
								<th> @sortablelink('str_owner_alliance_ticker', 'Alliance TICKER')</th>
								<th> @sortablelink('str_value', 'Fitting Value',)</th>
								<th> @sortablelink('created_at', 'Created')</th>
								<th> @sortablelink('updated_at', 'Last Updated')</th>


							</thead>
							<tbody>

								@if (isset($structures))              
								@foreach($structures as $structure)

								<tr>

									<td style="vertical-align: middle"><a href="{{  route('structures.view', $structure->str_structure_id_md5 )}}">{{ $structure->str_name }}</a></td>
									<td><img class="img-circle" src="https://image.eveonline.com/Type/{{ $structure->str_type_id }}_32.png">&nbsp;{{ $structure->str_type }}</td>



									@if ($structure->str_state === "High Power")
									<td style="vertical-align: middle"><span class="label label-success }}">High Power</span></td>
									@elseif ($structure->str_state === "Low Power")
									<td style="vertical-align: middle"><span class="label label-danger }}">Low Power</span></td>
									@elseif ($structure->str_state === "Anchoring")
									<td style="vertical-align: middle"><span class="label label-warning }}">Anchoring</span></td>
									@elseif ($structure->str_state === "Unanchoring")
									<td style="vertical-align: middle"><span class="label label-primary }}">Unanchoring</span></td>
									@elseif ($structure->str_state === "Reinforced")
									<td style="vertical-align: middle"><span class="label label-info }}">Reinforced</span></td>
									@else
									<td style="vertical-align: middle">State Not Set</td>
									@endif

									@if ($structure->str_status === "Unanchoring")
									<td style="vertical-align: middle"><span class="label label-primary">Unanchoring</span></td>
									@elseif ($structure->str_status === "Armor")
									<td style="vertical-align: middle"><span class="label label-warning">Reinforced Armor</span></td>
									@elseif ($structure->str_status === "Hull")
									<td style="vertical-align: middle"><span class="label label-danger">Reinforced Hull</span></td>
									@else
									<td style="vertical-align: middle">Status Not Set</td>
									@endif



									<td style="vertical-align: middle"><a href="{{  route('solar.system', $structure->str_system_id) }}">{{ $structure->str_system }}</a></td>
									<td style="vertical-align: middle"><a href="{{  route('solar.region', $structure->str_region_id) }}">{{ $structure->str_region_name }}</a></td>
									<td style="vertical-align: middle"><a href="{{ route('corporation.view', $structure->str_owner_corporation_id )}}"><img class="img-circle" src="https://imageserver.eveonline.com/Corporation/{{ $structure->str_owner_corporation_id }}_32.png">&nbsp;{{ $structure->str_owner_corporation_name }}</a></td>
									<td style="vertical-align: middle"><a href="{{ route('alliance.view', $structure->str_owner_alliance_id )}}"><img class="img-circle" src="https://imageserver.eveonline.com/Alliance/{{ $structure->str_owner_alliance_id }}_32.png">&nbsp;{{ $structure->str_owner_alliance_name }}</a></td>
									<td style="vertical-align: middle">{{ $structure->str_owner_alliance_ticker }}</td>
									<td style="vertical-align: middle">{{ number_format($structure->str_value,2) }}</td>
									<td style="vertical-align: middle">{{ $structure->created_at->diffForHumans() }}</td>
									<td style="vertical-align: middle">{{ $structure->updated_at->diffForHumans() }}</td>
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

		<div id="fleet-commanders" class="tab-pane fade">
			<div class="row">
				<div class="col-md-12">
					<div class="panel panel-default">
						<div class="panel-heading">Fleet Commanders</div>
						<div class="panel-body">
							<div class="table-responsive top-border-table" id="srp-table-wrapper">
								<table class="table" id="corporations">
									<thead>
										<th>@sortablelink('as_character_name','Character Name')</th>
										<th>@sortablelink('as_corporation_name', 'Corporation')</th>
										<th>@sortablelink('as_standing', 'Standing')</th>
									</thead>

									<tbody>
										@if (isset($alliance_fcs))              
										@foreach($alliance_fcs as $fc)
										<tr>
											<td><img class="img-circle" src="https://imageserver.eveonline.com/Character/{{ $fc->as_contact_id }}_32.jpg">&nbsp;{{ $fc->as_character_name }}</td>
											<td style="vertical-align: middle"><a href="{{ route('corporation.view', $fc->as_corporation_id )}}"><img class="img-circle" src="https://images.evetech.net/corporations/{{ $fc->as_corporation_id }}/logo?size=32">&nbsp;{{ $fc->as_corporation_name }}</a></td>
											<td style="vertical-align: middle">{{ $fc->as_standing }}</td>
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


		@permission('dossier.view')
		<div id="associated-groups" class="tab-pane fade">

			<div class="row">
				<div class="col-md-12">
					<div class="panel panel-default">
						<div class="panel-heading">Associated Groups</div>
						<div class="panel-body">
							<div class="table-responsive top-border-table" id="srp-table-wrapper">
								<table class="table" id="dossier-table-wrapper">
									<thead>
										<th style="vertical-align: middle">Group</th>
										<th style="vertical-align: middle">Relationship Score</th>
										<th style="vertical-align: middle">Function</th>
									</thead>

									<tbody>

										@if (isset($associated_groups))              
										@foreach($associated_groups as $group)

										<tr>
											<td style="vertical-align: middle"><a href="{{ route('corporation.view', $group->corporation_id )}}"><img class="img-circle" src="https://images.evetech.net/corporations/{{ $group->corporation_id  }}/logo?size=32">&nbsp;{{ $group->corporation_name }}</a></td>
											<td style="vertical-align: middle">{!! $group->relationship_score !!}/100</td>
											<td style="vertical-align: middle">{!! $group->corporation_function !!}</td>

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

		<div id="dossiers" class="tab-pane fade">

			<div class="row">
				<div class="col-md-12">
					<div class="panel panel-default">
						<div class="panel-heading">Dossiers</div>
						<div class="panel-body">
							<div class="table-responsive top-border-table" id="srp-table-wrapper">
								<table class="table" id="dossier-table-wrapper">
									<thead>
										<th style="vertical-align: middle">Dossier Title</th>
										<th style="vertical-align: middle">Group</th>
										<th style="vertical-align: middle">Target Alliance</th>
										<th style="vertical-align: middle">Relationship Score</th>
										<th style="vertical-align: middle">Function</th>
										<th style="vertical-align: middle">Created By</th>
										<th style="vertical-align: middle">Reviewed By</th>
										<th style="vertical-align: middle">Approval Date</th>
									</thead>

									<tbody>

										@if (isset($dossiers))              
										@foreach($dossiers as $dossier)

										<tr>
											<td style="vertical-align: middle"><a href="{{ route('dossier.view', $dossier->id)}}">{!! $dossier->dossier_title !!}</a></td>
											<td style="vertical-align: middle"><a href="{{ route('corporation.view', $dossier->corporation_id )}}"><img class="img-circle" src="https://images.evetech.net/corporations/{{ $dossier->corporation_id  }}/logo?size=32">&nbsp;{{ $dossier->corporation_name }}</a></td>
											<td style="vertical-align: middle">{!! $dossier->target_alliance_name !!}</td>
											<td style="vertical-align: middle">{!! $dossier->relationship_score !!}/100</td>
											<td style="vertical-align: middle">{!! $dossier->corporation_function !!}</td>
											<td style="vertical-align: middle">{!! $dossier->created_by_username !!}</td>
											<td style="vertical-align: middle">{!! $dossier->approved_by_username !!}</td>
											<td style="vertical-align: middle">{!! $dossier->approved_date !!}</td>
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
		@endpermission

		@permission('stager.view')
		<div id="staging" class="tab-pane fade">

			@permission('stager.add')
			<div class="row col-md-12">
				<div class="panel panel-default">
					<div class="panel-heading">Add a Staging System</div>
					<div class="panel-body">
						<div class="col-md-12">
							<form method="post" action="{{ route('stager.add_system') }}" enctype="multipart/form-data">
								{{ csrf_field() }}

								<div class="col-md-2">

									<div class="form-group">
										<label for="system">System</label>
										<input type="text" class="typeahead-systems form-control" name="system" id="system" placeholder="Search Systems" autocomplete="off" >
									</div>

									{{ Form::hidden('alliance_name',  $alliance->alliance_name) }}
									{{ Form::hidden('alliance_id',  $alliance->alliance_alliance_id) }}
									{{ Form::hidden('alliance_ticker',  $alliance->alliance_ticker) }}

									<div class="form-group">
										<label for="tag">Tag</label>
										{!! Form::select('tag', ['Home' => 'Home', 'Staging (All)' => 'Staging (All)', 'Staging (Subcaps)' => 'Staging (Subcaps)', 'Staging (Blops)' => 'Staging (Blops)', 'Staging (Capitals)' => 'Staging (Capitals)', 'Staging (Superfleet)' => 'Staging (Superfleet)'], Input::get('tag'), ['id' => 'tag', 'class' => 'form-control']) !!}
									</div>

									<button type="submit" class="btn btn-success">Add Stager</button>
								</div>
							</div>
						</form>
					</div>
				</div>
				@endpermission

			</div>

			<div class="row col-md-12">
				<div class="panel panel-default">
					<div class="panel-heading">Stager Information</div>
					<div class="panel-body">
						<div class="col-md-12">

							<div class="table-responsive top-border-table" id="stager-information">

								<table class="table" id="stagers">
									<thead>
										<th> @sortablelink('solar_system_name', 'Solar System')</th>
										<th> @sortablelink('constellation_name', 'Constellation')</th>
										<th> @sortablelink('region_name', 'Region')</th>
										<th> @sortablelink('tag', 'Tag')</th>
										<th> External Links</th>
										@permission('stager.remove')
										<th> Action</th>
										@endpermission

									</thead>

									<tbody>


										@if (isset($stagers))              
										@foreach($stagers as $stager)

										<tr>	
											<td style="vertical-align: middle"><a href="{{ route('solar.system', $stager->solar_system_id )}}">{{  $stager->solar_system_name }}</a></td>		
											<td style="vertical-align: middle"><a href="{{ route('solar.constellation', $stager->constellation_id )}}">{{  $stager->constellation_name }}</a></td>		
											<td style="vertical-align: middle"><a href="{{ route('solar.region', $stager->region_id )}}">{{  $stager->region_name }}</a></td>		
											<td style="vertical-align: middle">{{  $stager->tag }}</td>		
											<td style="vertical-align: middle">

												<a href="https://zkillboard.com/system/{{ $stager->solar_system_id }}" class="label label-danger" data-toggle="tooltip" target="_blank">
													<span >ZKillboard </span>
												</a>
												<br>



												<a href="https://evemaps.dotlan.net/system/{{  $stager->solar_system_id }}" class="label label-warning" data-toggle="tooltip" target="_blank">
													<span >DOT Lan </span>
												</a>
												<br>

												@if($stager->tag == "Staging (Blops)")

												<a href="https://evemaps.dotlan.net/range/Sin,5/{{  $stager->solar_system_name }}" class="label label-success" data-toggle="tooltip" target="_blank">
													<span >Bridge Range</span>
												</a>
												@elseif($stager->tag == "Staging (Capitals)")
												<a href="https://evemaps.dotlan.net/range/Moros,5/{{  $stager->solar_system_name }}" class="label label-success" data-toggle="tooltip" target="_blank">
													<span >Jump Range</span>
												</a>
												@else
												<a href="https://evemaps.dotlan.net/range/Avatar,5/{{  $stager->solar_system_name }}" class="label label-success" data-toggle="tooltip" target="_blank">
													<span >Bridge Range</span>
												</a>
												@endif



											</td>

											@permission('stager.remove')
											<td style="vertical-align: middle"><a href="{{ route('stager.remove', $stager->id) }}" class="btn btn-danger btn-circle" title="Remove Stager"
												data-toggle="tooltip"
												data-placement="top"
												data-method="DELETE"
												data-confirm-title="Please Confirm"
												data-confirm-text="Are you sure?"
												data-confirm-delete="Yes Remove">
												<i class="glyphicon glyphicon-trash"></i>
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
		</div>
		@endpermission


	</div>
</div>
</div>








@stop
@section('styles')
<style>
	.health-chart .chart {
		zoom: 1.235;
	}
	.user.media {
		float: left;
		border: 1px solid #dfdfdf;
		padding: 10px;
		border-radius: 4px;
		margin-right: 15px;
	}
	.user.media .media-object {
		width: 64px;
		height: 64px;
	}

</style>

@stop
@section('scripts')
<script type="text/javascript">
	$(document).ready(function(){
		$('a[data-toggle="tab"]').on('show.bs.tab', function(e) {
			localStorage.setItem('activeTab', $(e.target).attr('href'));
		});
		var activeTab = localStorage.getItem('activeTab');
		if(activeTab){
			$('#alliances a[href="' + activeTab + '"]').tab('show');
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


@stop

