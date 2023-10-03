@extends('layouts.app')

@section('page-title', 'Dossier Manager')

@section('content')

<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
			Dossier Manager
			<small> - create dossiers for groups</small>
			<div class="pull-right">
				<ol class="breadcrumb">
					<li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
					<li><a href="{{ route('dossier.index') }}">Dossier Manager</a></li>
					<li class="active">Create</li>
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

<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				Dossier Manager
			</div>
			<div class="panel-body">
				And... the lucky contestant  is <b>{!! $corporation->corporation_name !!}</b> they're in trouble oh no.<br>
				Below is various sets of intelligence to review on <b>{!! $corporation->corporation_name !!}</b>, using this information and the framework below, a relationship score will be calculated on the likelihood that the target alliance is associated with the group in question. <br><br>
				Have fun, once completed, it will be reviewed and approved.<br>
				The Dossier is basedon a weighted average calculation with the maximum of 100%. Each weight is detailed beside each option.
			</div>
		</div>
	</div>
</div>

<ul class="nav nav-tabs" id="dossier-manager">
	<li class="active"><a data-toggle="tab" href="#create">Create Dossier</a></li>
	<li><a data-toggle="tab" href="#structures">Corporation Structures</a></li>
</ul>

<div class="tab-content">
	<div id="create" class="tab-pane fade in active">
		<div class="row">
			<div class="col-md-3">
				<div id="edit-user-panel" class="panel panel-default">
					<div class="panel-heading">
						Corporation Information
					</div>

					<div class="panel-body panel-profile">
						<div class="image">
							<img alt="image" class="img-circle avatar" src="https://imageserver.eveonline.com/Corporation/{{ $corporation->corporation_corporation_id }}_128.png">
						</div>
						<div class="name"><strong>{{ $corporation->corporation_name }}</strong></div>

						<br>

						<div class="col-md-12">
							<div class="col-md-4">
								<a href="https://evewho.com/corporation/{{ $corporation->corporation_corporation_id }}" class="label label-info" data-toggle="tooltip" target="_blank">
									<span >EVE Who</span>
								</a>
							</div>
							<div class="col-md-4">
								<a href="https://zkillboard.com/corporation/{{ $corporation->corporation_corporation_id }}" class="label label-danger" data-toggle="tooltip" target="_blank">
									<span >ZKill</span>
								</a>
							</div>

							<div class="col-md-4">
								<a href="https://evemaps.dotlan.net/corp/{{ $corporation->corporation_corporation_id }}" class="label label-warning" data-toggle="tooltip" target="_blank">
									<span >DOT Lan</span>
								</a>
							</div>
						</div>
						<br>
						<table class="table table-hover">
							<thead>
								<tr>
									<th colspan="3">Information</th>
								</tr>
							</thead>
							<tbody>
								@if(isset($alliance))
								{{ Form::hidden('alliance_id',  $alliance->alliance_alliance_id) }}
								{{ Form::hidden('alliance_name',  $alliance->alliance_name) }}
								<tr>
									<td>Alliance</td>
									<td><a href="{{ route('alliance.view', $alliance->alliance_alliance_id) }}">{{ $alliance->alliance_name }}</a></td>
								</tr>
								@else
								{{ Form::hidden('alliance_id', "") }}
								{{ Form::hidden('alliance_name', "") }}
								<tr>
									<td>Alliance</td>
									<td>None</td>
								</tr>
								@endif
								<tr>
									<td>Ticker</td>
									<td>{{ $corporation->corporation_ticker }}</td>
								</tr>
								<tr>
									<td>Member Count</td>
									<td>{{ $corporation->corporation_member_count }}</td>
								</tr>
								<tr>
									<td>Updated</td>
									<td>{{ $corporation->updated_at->diffForHumans() }}</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>



			<div class="col-md-9">
				<div class="panel panel-default">
					<div class="panel-heading">Create Dossier</div>
					<div class="panel-body">

						<form method="post" action="{{ route('dossier.store') }}" enctype="multipart/form-data">
							{{ csrf_field() }}
							<div class="panel-body" >
								<div class="col-md-4">

									<div class="form-group">
										<label for="dossier_title">Title *</label>
										<input type="text" class="form-control" name="dossier_title" id="dossier_title" placeholder="Give me a cool name." autocomplete="off" >
										{{ Form::hidden('author', Auth::user()->username) }}
										{{ Form::hidden('approved', 'No') }}
										{{ Form::hidden('corporation_name',  $corporation->corporation_name) }}

									</div>
									<div class="form-group">
										<label for="alliance_name">Suspected Associated Alliance. *</label>
										<input type="text" class="form-control typeahead-alliances" name="alliance_name" placeholder="..." autocomplete="off">
									</div>
									<div class="form-group">
										<label for="is_shell_corporation">Is Suspected Shell Corporation. (10%)</label>
										{!! Form::select('is_shell_corporation', ['No' => 'No', 'Yes' => 'Yes', 'TBC' => 'TBC'], Input::get('is_shell_corporation'), ['id' => 'is_shell_corporation', 'class' => 'form-control']) !!}
									</div>


									<div class="form-group">
										<label for="has_relationship_via_evewho_history">Eve Who Relationship. (15%)</label>
										{!! Form::select('has_relationship_via_evewho_history', ['No' => 'No', 'Yes' => 'Yes', 'TBC' => 'TBC'], Input::get('has_relationship_via_evewho_history'), ['id' => 'has_relationship_via_evewho_history', 'class' => 'form-control']) !!}
									</div>

									<div class="form-group">
										<label for="has_office_in_alliance_staging">Has An Office In Target Alliance Staging. (5%)</label>
										{!! Form::select('has_office_in_alliance_staging', ['No' => 'No', 'Yes' => 'Yes', 'TBC' => 'TBC'], Input::get('has_office_in_alliance_staging'), ['id' => 'has_office_in_alliance_staging', 'class' => 'form-control']) !!}
									</div>
								</div>

								<div class="col-md-4">






									<div class="form-group">
										<label for="presence_of_cyno_alts">Presence of Cyno Alts. (5%)</label>
										{!! Form::select('presence_of_cyno_alts', ['No' => 'No', 'Yes' => 'Yes', 'TBC' => 'TBC'], Input::get('presence_of_cyno_alts'), ['id' => 'presence_of_cyno_alts', 'class' => 'form-control']) !!}
									</div>
									<div class="form-group">
										<label for="presence_of_freighter_alts">Presence of Freighter Alts. (5%)</label>
										{!! Form::select('presence_of_freighter_alts', ['No' => 'No', 'Yes' => 'Yes', 'TBC' => 'TBC'], Input::get('presence_of_freighter_alts'), ['id' => 'presence_of_freighter_alts', 'class' => 'form-control']) !!}
									</div>



									<div class="form-group">
										<label for="locators_confirm_location_of_related_alliance">Locator Agents Confirm Member/Staging Relationship. (5%)</label>
										{!! Form::select('locators_confirm_location_of_related_alliance', ['No' => 'No', 'Yes' => 'Yes', 'TBC' => 'TBC'], Input::get('locators_confirm_location_of_related_alliance'), ['id' => 'locators_confirm_location_of_related_alliance', 'class' => 'form-control']) !!}
									</div>

									<div class="form-group">
										<label for="has_structures_in_related_system_of_target_alliance">Has Structures in Related Systems. (20%)</label>
										{!! Form::select('has_structures_in_related_system_of_target_alliance', ['No' => 'No', 'Yes' => 'Yes', 'TBC' => 'TBC'], Input::get('has_structures_in_related_system_of_target_alliance'), ['id' => 'has_structures_in_related_system_of_target_alliance', 'class' => 'form-control']) !!}
									</div>
								</div>
								<div class="col-md-4">

									<div class="form-group">
										<label for="has_structures_in_systems_with_very_high_indexes">Has Structures in Systems with High Indexes. (10%)</label>
										{!! Form::select('has_structures_in_systems_with_very_high_indexes', ['No' => 'No', 'Yes' => 'Yes', 'TBC' => 'TBC'], Input::get('has_structures_in_systems_with_very_high_indexes'), ['id' => 'has_structures_in_systems_with_very_high_indexes', 'class' => 'form-control']) !!}
									</div>
									


									<div class="form-group">
										<label for="has_related_killboard_activity">Has Related Killboard History. (10%)</label>
										{!! Form::select('has_related_killboard_activity', ['No' => 'No', 'Yes' => 'Yes', 'TBC' => 'TBC'], Input::get('has_related_killboard_activity'), ['id' => 'has_related_killboard_activity', 'class' => 'form-control']) !!}
									</div>
									<div class="form-group">
										<label for="has_structures_on_expensive_money_moons">Has Structures on Expensive Money Moons (15%)</label>
										{!! Form::select('has_structures_on_expensive_money_moons', ['No' => 'No', 'Yes' => 'Yes', 'TBC' => 'TBC'], Input::get('has_structures_on_expensive_money_moons'), ['id' => 'has_structures_on_expensive_money_moons', 'class' => 'form-control']) !!}
									</div>

									<div class="form-group">
										<label for="corporation_function">Suspected Function of the Corporation.</label>
										{!! Form::select('corporation_function', ['Ally Corporation' => 'Ally Corporation', 'Industry' => 'Industry', 'Logistics' => 'Logistics', 'Holding' => 'Holding', 'Cynos' =>'Cynos', 'Capitals' => 'Capitals'], Input::get('corporation_function'), ['id' => 'corporation_function', 'class' => 'form-control']) !!}
									</div>
								</div>


								<div class="col-md-12">
									<div class="form-group">
										<label for="notes">Intel Notes / Evidence</label>
										<textarea name="notes" type="text" class="form-control" id="notes" placeholder="Notes
										" rows="10"></textarea>
									</div>
								</div>


							</div>

							<div class="form-group row">
								<div style="text-align: center;">
									<button type="submit" class="btn btn-success">Publish for Review</button>
								</div>
							</div>
						</form>
					</div>					
				</div>
			</div>



		</div>
	</div>

	<div id="structures" class="tab-pane fade">

		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-heading">Most Valuable Structures</div>
					<div class="panel-body">
						Structures that are within the database, limited to (20). for a full review of the corporation, please use all structures.
						<div class="col-md-12">
							<div class="table-responsive top-border-table" id="structures-table-wrapper">

								<table class="table" id="structures">
									<thead>
										<th> @sortablelink('str_name', 'Structure Name')</th>
										<th> @sortablelink('str_type', 'Type')</th>
										<th> Fitting Summary </th>
										<th> @sortablelink('str_vul_hour', 'Vulnerability Time')</th>
										<th> @sortablelink('str_state', 'State')</th>
										@permission('deliver.package')
										<th>@sortablelink('str_package_delivered', 'Package Status')</th>
										@endpermission
										<th> @sortablelink('str_status', 'Status')</th>

										<th> @sortablelink('str_system', 'System')</th>
										<th> @sortablelink('str_constellation_name', 'Constellation')</th>
										<th> @sortablelink('str_region_name', 'Region')</th>
										<th> @sortablelink('str_owner_corporation_name', 'Corporation Owner')</th>
										<th> @sortablelink('str_owner_alliance_name', 'Alliance')</th>
										<th> @sortablelink('str_value', 'Fitting Value',)</th>
										<th> @sortablelink('updated_at', 'Last Updated')</th>
										@permission('set.waypoint')
										<th> Set Waypoint </th>
										@endpermission


									</thead>
									<tbody>

										@if (isset($structures))              
										@foreach($structures as $structure)

										<tr>

											<td style="vertical-align: middle">
												@permission('structure.hitlist')
												@if($structure->str_hitlist == 1)

												<i class="glyphicon glyphicon-flag"></i>

												@endif
												@endpermission

												<a href="{{  route('structures.view', $structure->str_structure_id_md5 )}}">{{ $structure->str_name }}
												</a>


											</td>
											<td style="vertical-align: middle"><img class="img-circle" src="https://images.evetech.net/types/{{ $structure->str_type_id }}/render?size=32">&nbsp;{{ $structure->str_type }}</td>

											<td style="vertical-align: middle; max-width:180px;">

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
											<td style="vertical-align: middle">
												{{ $structure->str_vul_hour }}
											</td>
											@if ($structure->str_state === "High Power")
											<td style="vertical-align: middle"><span class="label label-success">High Power</span></td>
											@elseif ($structure->str_state === "Low Power")
											<td style="vertical-align: middle"><span class="label label-danger">Low Power</span></td>
											@elseif ($structure->str_state === "Abandoned")
											<td style="vertical-align: middle"><span class="label label-warning">Abandoned</span></td>
											@elseif ($structure->str_state === "Anchoring")
											<td style="vertical-align: middle"><span class="label label-warning">Anchoring</span></td>
											@elseif ($structure->str_state === "Unanchoring")
											<td style="vertical-align: middle"><span class="label label-primary">Unanchoring</span></td>
											@elseif ($structure->str_state === "Reinforced")
											<td style="vertical-align: middle"><span class="label label-info">Reinforced</span></td>
											@else
											<td style="vertical-align: middle">-</td>
											@endif

											@permission('deliver.package')
											<td style="vertical-align: middle">

												@if ($structure->str_package_delivered === "Package Delivered")
												<span class="label label-success">{{ $structure->str_package_delivered }}</span>
												@elseif ($structure->str_package_delivered === "")
												<span class="label label-danger">No Package</span>
												@else
												<span class="label label-danger">{{ $structure->str_package_delivered }}</span>
												@endif
											</td>
											@endpermission

											@if ($structure->str_status === "Unanchoring")
											<td style="vertical-align: middle"><span class="label label-primary">Unanchoring</span></td>
											@elseif ($structure->str_status === "Armor")
											<td style="vertical-align: middle"><span class="label label-warning">Reinforced Armor</span></td>
											@elseif ($structure->str_status === "Hull")
											<td style="vertical-align: middle"><span class="label label-danger">Reinforced Hull</span></td>
											@else
											<td style="vertical-align: middle">-</td>
											@endif

											<td style="vertical-align: middle"><a href="{{  route('solar.system', $structure->str_system_id) }}" target="_blank">{{ $structure->str_system }}</a></td>
											<td style="vertical-align: middle"><a href="{{  route('solar.constellation', $structure->str_constellation_id) }}" target="_blank">{{ $structure->str_constellation_name }}</a></td>
											<td style="vertical-align: middle"><a href="{{  route('solar.region', $structure->str_region_id) }}" target="_blank">{{ $structure->str_region_name }}</a></td>
											@if($structure->str_owner_corporation_id > 1)
											<td style="vertical-align: middle"><a href="{{ route('corporation.view', $structure->str_owner_corporation_id )}}" target="_blank"><img class="img-circle" src="https://images.evetech.net/corporations/{{ $structure->str_owner_corporation_id }}/logo?size=32">&nbsp;{{ $structure->str_owner_corporation_name }}</a></td>
											@else
											<td></td>
											@endif
											@if($structure->str_owner_alliance_id > 1)
											<td style="vertical-align: middle"><a href="{{ route('alliance.view', $structure->str_owner_alliance_id )}}" target="_blank"><img class="img-circle" src="https://images.evetech.net/alliances/{{ $structure->str_owner_alliance_id }}/logo?size=32">&nbsp;{{ $structure->str_owner_alliance_name }} ({{ $structure->str_owner_alliance_ticker }})</a></td>
											@else
											<td></td>
											@endif

											<td style="vertical-align: middle">{{ number_format($structure->str_value,2) }}</td>

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
			</div>
		</div>
	</div>
</div>



@stop

@section('styles')
{!! HTML::style('assets/css/bootstrap-datetimepicker.min.css') !!}
@stop

@section('scripts')
<script src="/vendor/unisharp/laravel-ckeditor/ckeditor.js"></script>
<script>
	CKEDITOR.replace( 'notes' );
</script>
<script>
	var path4 = "{{ route('autocomplete.alliances') }}";
	$('input.typeahead-alliances').typeahead({
		source:  function (alliance, process) {
			return $.get(path4, { alliance: alliance }, function (data4) {
				return process(data4);
			});
		}
	});


</script>
{!! HTML::script('assets/js/moment.min.js') !!}
{!! HTML::script('assets/js/bootstrap-datetimepicker.min.js') !!}
@stop