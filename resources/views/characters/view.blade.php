@extends('layouts.app')

@section('page-title', $character->character_name)

@section('content')

<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
			Intelligence on <b>{{ $character->character_name }}</b> of {{ $character->character_corporation_name }}
			<small></small>
			<div class="pull-right">
				<ol class="breadcrumb">
					<li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
					<li><a href="{{ route('character.index') }}">Character Search</a></li>
					<li class="active">{{ $character->character_name }}</li>
				</ol>
			</div>

		</h1>
	</div>
</div>

@include('partials.messages')

<div class="container row col-md-12">
	<ul class="nav nav-tabs" id="characters">
		<li class="active"><a data-toggle="tab" href="#information">Character Information</a></li>
		<li><a data-toggle="tab" href="#reporting">Reports ({{ count($reports) }})</a></li>
		<li><a data-toggle="tab" href="#relationships">Direct Relationships ({{ count($relationships->where('associated_character_name', '!=', $character->character_name)) }})</a></li>
		<li><a data-toggle="tab" href="#related_characters">Related Characters Reports ({{ count($related_characters) }})</a></li>			
		<li><a data-toggle="tab" href="#contracts">Contracts ({{ count($contracts) }})</a></li>	
	</ul>
	<div class="tab-content">
		<div id="information" class="tab-pane fade in active">

			<div class="row">
				<div class="col-md-3">
					<div id="edit-user-panel" class="panel panel-default">
						<div class="panel-heading">
							<b>Information on {{ $character->character_name }}</b>
						</div>

						<div class="panel-body panel-profile">
							<div class="image">
								<img alt="image" class="img-circle avatar" src="https://imageserver.eveonline.com/Character/{{ $character->character_character_id }}_128.png">
							</div>
							<p>

								<b>{{ $character->character_name }}</b>

							</p>

							<div class="col-md-12">
								<div class="col-md-6">
									<a href="https://evewho.com/character/{{ $character->character_character_id }}" class="label label-info" data-toggle="tooltip" target="_blank">
										<span >EVE Who</span>
									</a>
								</div>
								<div class="col-md-6">
									<a href="https://zkillboard.com/character/{{ $character->character_character_id }}" class="label label-danger" data-toggle="tooltip" target="_blank">
										<span >ZKill</span>
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
									<tr>
										<td>Corporation</td>
										<td>

											<a href="https://evewho.com/corporation/{{ $character->character_corporation_name }}" data-toggle="tooltip" target="_blank">
												{{ $character->character_corporation_name }}
											</a>
										</td>
									</tr>
									<tr>
										<td>Alliance</td>
										<td>
											@if($character->character_alliance_id > 1)
											<a href="https://evewho.com/alliance/{{ $character->character_alliance_name }}" data-toggle="tooltip" target="_blank">
												{{ $character->character_alliance_name }}
											</a>
											@else
											@endif
										</td>
									</tr>
								</tbody>
							</table>

							<table class="table table-hover">
								<thead>
									<tr>
										<th colspan="3">Capable Ships</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>Titan</td>
										<td>
											@if($character->titan)
											<span class="label label-danger">Titan</span>
											@endif
										</td>
									</tr>
									<tr>

										<td>Faction Titan</td>
										<td>
											@if($character->faction_titan)
											<span class="label label-danger">Faction Titan</span>
											@endif
										</td>
									</tr>
									<tr>
										<td>Supercapital</td>
										<td>
											@if($character->super)
											<span class="label label-danger">Supercapital</span>
											@endif
										</td>
									</tr>
									<tr>
										<td>Faction Supercapital</td>
										<td>
											@if($character->faction_super)
											<span class="label label-danger">Faction Supercapital</span>
											@endif
										</td>
									</tr>
									<tr>
										<td>Carrier</td>
										<td>
											@if($character->carrier)
											<span class="label label-success">Carrier</span>
											@endif
										</td>	
									</tr>
									<tr>
										<td>Fax</td>
										<td>

											@if($character->fax)
											<span class="label label-success">Fax</span>
											@endif
										</td>
									</tr>
									<tr>
										<td>Dread</td>
										<td>


											@if($character->dread)
											<span class="label label-warning">Dread</span>
											@endif
										</td>
									</tr>
									<tr>
										<td>Faction Dread</td>
										<td>

											@if($character->faction_dread)
											<span class="label label-danger">Faction Dread</span>
											@endif
										</td>
									</tr>
									<tr>
										<td>Monitor</td>
										<td>
											@if($character->monitor)
											<span class="label label-primary">Monitor</span>
											@endif
										</td>
									</tr>
									<tr>
										<td>Jump Freighter</td>
										<td>

											@if($character->jump_freighter)
											<span class="label label-danger">Jump Freighter</span>
											@endif
										</td>

									</tr>
									<tr>
										<td>Freighter</td>
										<td>

											@if($character->freighter)
											<span class="label label-warning">Freighter</span>
											@endif
										</td>

									</tr>
									<tr>
										<td>Rorqual</td>
										<td>

											@if($character->rorqual)
											<span class="label label-danger">Rorqual</span>
											@endif
										</td>

									</tr>
									<tr>
										<td>Cyno</td>
										<td>

											@if($character->cyno)
											<span class="label label-info">Cyno</span>
											@endif
										</td>

									</tr>
									<tr>
										<td>Industrial Cyno</td>
										<td>

											@if($character->industrial_cyno)
											<span class="label label-info">Industrial Cyno</span>
											@endif
										</td>

									</tr>

								</tbody>
							</table>

						</div>
					</div>
				</div>

				<div class="col-md-3">
					<div id="edit-user-panel" class="panel panel-default">
						<div class="panel-heading">
							<b>Characters with Relationships to {{ $character->character_name }}</b>
						</div>

						<div class="panel-body panel-profile">

							

							<table class="table" id="relationships_home">
								<thead>
									<th>Character</th>
									<th>Relationship</th>
									<th>Note</th>

								</thead>
								<tbody>


									@if (count($relationships))              
									@foreach($relationships as $relationship)

									<tr>
										@if($relationship->associated_character_name == $character->character_name)
										<td style="vertical-align: middle"><a href="{{ route('character.view', $relationship->character_name) }}" target="_blank"><img class="img-circle" src="https://image.eveonline.com/Character/{{ $relationship->character_id}}_32.jpg">&nbsp;{{ $relationship->character_name }}</a>
										</td>
										<td style="vertical-align: middle">Indirectly</td>
										<td style="vertical-align: middle"><a href="#" data-toggle="tooltip" title="{!! $relationship->notes !!}" data-placement="left" ><span class="glyphicon glyphicon-info-sign"></span></a></td>
										@else
										<td style="vertical-align: middle"><a href="{{ route('character.view', $relationship->associated_character_name) }}" target="_blank"><img class="img-circle" src="https://image.eveonline.com/Character/{{ $relationship->associated_character_id}}_32.jpg">&nbsp;{{ $relationship->associated_character_name }}</a>
										</td>
										<td style="vertical-align: middle">Directly</td>
										<td style="vertical-align: middle"><a href="#" data-toggle="tooltip" title="{!! $relationship->notes !!}" data-placement="left" ><span class="glyphicon glyphicon-info-sign"></span></a></td>
										@endif

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

				<div class="col-md-3">
					<div id="edit-user-panel" class="panel panel-default">
						<div class="panel-heading">
							<b>Alliance Standings Related to {{ $character->character_name }}</b>
						</div>

						<div class="panel-body panel-profile">

							

							<table class="table" id="relationships_home">
								<thead>
									<th>Alliance</th>
									<th>Standing</th>

								</thead>
								<tbody>


									@if (count($standings))              
									@foreach($standings as $standing)

									<tr>

										<td style="vertical-align: middle"><a href="#"><img class="img-circle" src="https://images.evetech.net/alliances/{{ $standing->as_enemy_alliance_id }}/logo?size=32">&nbsp;{{ $standing->as_enemy_alliance_name }}</a></td>

										@if($standing->standing <= 10 && $standing->standing >= 5)
										<td style="vertical-align: middle"><span class="label label-primary">{{ $standing->as_standing }}</span></td>
										@elseif($standing->as_standing <= 5 && $standing->as_standing >= 0)
										<td style="vertical-align: middle"><span class="label label-info">{{ $standing->as_standing }}</span></td>
										@elseif($standing->as_standing <= 0 && $standing->as_standing >= -5)
										<td style="vertical-align: middle"><span class="label label-warning">{{ $standing->as_standing }}</span></td>
										@else
										<td style="vertical-align: middle"><span class="label label-danger">{{ $standing->as_standing }}</span></td>
										@endif

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

				<div class="col-md-3">
					<div id="edit-user-panel" class="panel panel-default">
						<div class="panel-heading">
							<b>Associated Groups Related to {{ $character->character_name }}</b>
						</div>

						<div class="panel-body panel-profile">

							

							<table class="table" id="relationships_home">
								<thead>
									<th>Corporation</th>
									<th>Associated Alliance</th>
									<th>Function</th>

								</thead>
								<tbody>

									@if (count($dossiers))              
									@foreach($dossiers as $dossier)

									<tr>
										<td style="vertical-align: middle"><a href="#"><img class="img-circle" src="https://images.evetech.net/corporations/{{ $dossier->corporation_id }}/logo?size=32">&nbsp;{{ $dossier->corporation_name }}</a></td>
										<td style="vertical-align: middle"><a href="#"><img class="img-circle" src="https://images.evetech.net/alliances/{{ $dossier->target_alliance_id }}/logo?size=32">&nbsp;{{ $dossier->target_alliance_name }}</a></td>
										<td style="vertical-align: middle">{{ $dossier->corporation_function }}</td>
									</tr>


									@endforeach
									@else

									<tr>
										<td><em>No Records Found</em></td>
									</tr>

									@endif


								</tbody>
							</table>


						</div>
					</div>
				</div>
			</div>
		</div>

		<div id="reporting" class="tab-pane fade">

			<div class="row">
				<div class="col-md-12">
					<div class="panel panel-default">
						<div class="panel-heading">
							Character Reporting
						</div>
						<div class="panel-body">
							If you wish to report intelligence on this character, please complete the section below to add a record, tag and bag them, we will kill them later!
						</div>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-md-3">
					<div id="edit-user-panel" class="panel panel-default">
						<div class="panel-heading">
							<b>Reporting</b>
						</div>

						<div class="panel-body">

							<form method="post" action="{{ route('character.store_report')}}" enctype="multipart/form-data">
								{{ csrf_field() }}
								<div class="panel-body" >
									{{ Form::hidden('character_name',  $character->character_name) }}

									<div class="form-group">
										<label for="alliance_name">Suspected Associated Alliance.</label>
										<input type="text" class="form-control typeahead-alliances" name="alliance_name" placeholder="..." autocomplete="off">
									</div>
									<div class="form-group">
										<label for="system_name">Spotted System *</label>
										<input type="text" class="form-control typeahead-systems" name="system_name" placeholder="..." autocomplete="off">
									</div>
									<div class="form-group">
										<label for="spotted_hull">Spotted Hull</label>
										{!! Form::select('spotted_hull', ['Carrier' => 'Carrier', 'Cyno' => 'Cyno', 'Freighter' => 'Freighter', 'Industrial Cyno' => 'Industrial Cyno', 'Jump Freighter' => 'Jump Freighter', 'Monitor' => 'Monitor', 'Rorqual' => 'Rorqual', 'Super' => 'Super', 'Titan' => 'Titan'], Input::get('spotted_hull'), ['id' => 'spotted_hull', 'class' => 'form-control']) !!}
									</div>

									<div class="form-group">
										<label for="spotted_hull">Reason / Notes</label>
										<textarea name="notes" type="text" class="form-control" id="notes" placeholder="" rows="7"></textarea>
									</div>
								</div>

								<div class="form-group row">
									<div style="text-align: center;">
										<button type="submit" class="btn btn-success">Report</button>
									</div>
								</div>
							</form>
						</div>					
					</div>
				</div>


				<div class="col-md-9">
					<div id="edit-user-panel" class="panel panel-default">
						<div class="panel-heading">
							<b>Reporting History</b>
							<div class="pull-right">
								<a href="{{ route('character_reporting.index') }}?character={{$character->character_name}}">View all reports</a>
							</div>
						</div>

						<div class="panel-body panel-profile">


							<table class="table" id="reports">
								<thead>
									<th>System</th>
									<th>Region</th>
									<th>Characters Corporation</th>
									<th>Suspected Alliance</th>
									<th>Hull Type</th>
									<th>Added</th>
									<th>Note</th>
								</thead>
								<tbody>


									@if (count($reports))              
									@foreach($reports as $report)

									<tr>
										<td style="vertical-align: middle">{{ $report->system_name }}</td>
										<td style="vertical-align: middle">{{ $report->region_name }}</td>
										<td style="vertical-align: middle"><img class="img-circle" src="https://image.eveonline.com/Corporation/{{ $report->corporation_id }}_32.png">&nbsp;{{ $report->corporation_name }}</td>
										@if($report->alliance_id > 1)
										<td style="vertical-align: middle"><img class="img-circle" src="https://image.eveonline.com/Alliance/{{ $report->alliance_id}}_32.png">&nbsp;{{ $report->alliance_name }}</td>
										@else
										<td></td>
										@endif
										<td style="vertical-align: middle">{{ $report->hull_type }}</td>
										<td style="vertical-align: middle">{{ $report->created_at }} : {{ $report->created_at->diffForHumans() }}</td>
										<td style="vertical-align: middle"><a href="#" data-toggle="tooltip" title="{!! $report->notes !!}" data-placement="left" ><span class="glyphicon glyphicon-info-sign"></span></a></td>
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

		<div id="relationships" class="tab-pane fade">

			<div class="row">
				<div class="col-md-12">
					<div class="panel panel-default">
						<div class="panel-heading">
							Character Relationships
						</div>
						<div class="panel-body">
							Add a relationship between characters, make sure both characters are in the database, add by searching them <a href="{{ route('character.index') }}">here</a>.<p>
							For example, a character lights a Cyno and brings in a freighter.</p>
						</div>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-md-3">
					<div id="edit-user-panel" class="panel panel-default">
						<div class="panel-heading">
							<b>Reporting</b>
						</div>

						<div class="panel-body">

							<form method="post" action="{{ route('character.store_relationship')}}" enctype="multipart/form-data">
								{{ csrf_field() }}
								<div class="panel-body" >
									{{ Form::hidden('character_name',  $character->character_name) }}

									<div class="form-group">
										<label for="associated_character">Associated Character Name</label>
										<input type="text" class="form-control" name="associated_character" placeholder="..." autocomplete="off">
									</div>

									<div class="form-group">
										<label for="spotted_hull">Reason / Notes</label>
										<textarea name="notes" type="text" class="form-control" id="notes" placeholder="" rows="7"></textarea>
									</div>
								</div>

								<div class="form-group row">
									<div style="text-align: center;">
										<button type="submit" class="btn btn-success">Add Relationship</button>
									</div>
								</div>
							</form>
						</div>					
					</div>
				</div>


				<div class="col-md-9">
					<div id="edit-user-panel" class="panel panel-default">
						<div class="panel-heading">
							<b>Character Relationships - Static Information, at the time of the relationship being added. (Direct Entries Only)</b>
						</div>

						<div class="panel-body panel-profile">


							<table class="table" id="reports">
								<thead>
									<th>Character</th>
									<th>Corporation</th>
									<th>Alliance</th>
									<th>Added</th>
									<th>Note</th>
								</thead>
								<tbody>


									@if (count($relationships))              
									@foreach($relationships as $relationship)

									<tr>
										@if($relationship->associated_character_name != $character->character_name)

										<td style="vertical-align: middle"><a href="{{ route('character.view', $relationship->associated_character_name) }}" target="_blank"><img class="img-circle" src="https://image.eveonline.com/Character/{{$relationship->associated_character_id}}_32.jpg">&nbsp;{{ $relationship->associated_character_name }}</a></td>
										<td style="vertical-align: middle"><img class="img-circle" src="https://image.eveonline.com/Corporation/{{ $relationship->associated_corporation_id }}_32.png">&nbsp;{{ $relationship->associated_corporation_name }}</td>
										@if($relationship->associated_alliance_id > 1)
										<td style="vertical-align: middle"><img class="img-circle" src="https://image.eveonline.com/Alliance/{{ $relationship->associated_alliance_id}}_32.png">&nbsp;{{ $relationship->associated_alliance_name }}</td>
										@else
										<td></td>
										@endif


										<td style="vertical-align: middle">{{ $relationship->created_at }} : {{ $relationship->created_at->diffForHumans() }}</td>
										<td style="vertical-align: middle"><a href="#" data-toggle="tooltip" title="{!! $relationship->notes !!}" data-placement="left" ><span class="glyphicon glyphicon-info-sign"></span></a></td>
										@endif
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

		<div id="related_characters" class="tab-pane fade">

			<div class="row">
				<div class="col-md-12">
					<div class="panel panel-default">
						<div class="panel-heading">
							Related Characters
						</div>
						<div class="panel-body">
							If a character has previously been reported and they are of the same corporation, it will be listed here, the only caveat is that, it will have been the characters corporation at time of reporting and not necessarily, their current corporation,
						</div>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-md-12">
					<div class="panel panel-default">
						<div class="panel-heading">
							Related Corporation Members of <b>{{ $character->character_name }}</b>
						</div>
						<div class="panel-body">

							<div class="table-responsive" id="contracts-table-wrapper">
								<table class="table table-borderless table-striped">
									<thead>
										<tr>
											<th>Character</th>
											<th>System</th>
											<th>Region</th>
											<th>Suspected Alliance</th>
											<th>Hull</th>
											<th>Created</th>
											<th>Note</th>

										</tr>
									</thead>
									<tbody>
										@if (count($related_characters))
										@foreach ($related_characters as $related)
										<tr>



											<td style="vertical-align: middle"><a href="{{ route('character.view', $related->character_name) }}"><img class="img-circle" src="https://image.eveonline.com/Character/{{ $related->character_id}}_32.jpg">&nbsp;{{ $related->character_name }}</a></td>
											<td style="vertical-align: middle">{!! $related->system_name !!}</td>
											<td style="vertical-align: middle">{!! $related->region_name !!}</td>
											@if($related->alliance_id > 1)
											<td style="vertical-align: middle"><img class="img-circle" src="https://image.eveonline.com/Alliance/{{ $related->alliance_id}}_32.png">&nbsp;{{ $related->alliance_name }}</td>
											@else
											<td></td>
											@endif
											<td style="vertical-align: middle">{!! $related->hull_type !!}</td>
											<td style="vertical-align: middle">{{ $related->created_at }} : {{ \Carbon\Carbon::parse($related->created_at)->diffForHumans() }}</td>
											<td style="vertical-align: middle"><a href="#" data-toggle="tooltip" title="{!! $related->notes !!}" data-placement="left" ><span class="glyphicon glyphicon-info-sign"></span></a></td>

										</tr>
										@endforeach
										@else
										<tr>
											<td colspan="7"><em>No Records Found</em></td>
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

		<div id="contracts" class="tab-pane fade">

			<div class="row">
				<div class="col-md-12">
					<div class="panel panel-default">
						<div class="panel-heading">
							Public Contracts
						</div>
						<div class="panel-body">
							If this character has been seen exporting capital ships within our space on public contracts, fuck them.
						</div>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-md-12">
					<div class="panel panel-default">
						<div class="panel-heading">
							<b>Capital Public Contracts</b> created by {{ $character->character_name }}
						</div>
						<div class="panel-body">

							<div class="table-responsive" id="contracts-table-wrapper">
								<table class="table table-borderless table-striped">
									<thead>
										<tr>
											<th>Hull</th>
											<th>Region</th>
											<th>Character Name</th>
											<th>Corporation Name</th>
											<th>Alliance Name</th>
											<th>Standing</th>
											<th>Created</th>

										</tr>
									</thead>
									<tbody>
										@if (count($contracts))
										@foreach ($contracts as $contract)
										<tr>


											<td style="vertical-align: middle"><img class="img-circle" src="https://imageserver.eveonline.com/Type/{!! $contract->type_id !!}_32.png">{!! $contract->type_name !!}</td>
											<td style="vertical-align: middle">{!! $contract->region_name !!}</td>
											<td style="vertical-align: middle"><img class="img-circle" src="https://image.eveonline.com/Character/{{ $contract->issuer_id}}_32.jpg">&nbsp;{{ $contract->character_name }}</td>
											<td style="vertical-align: middle"><img class="img-circle" src="https://image.eveonline.com/Corporation/{{ $contract->corporation_id }}_32.png">&nbsp;{{ $contract->corporation_name }}</td>
											@if($contract->alliance_id > 1)
											<td style="vertical-align: middle"><img class="img-circle" src="https://image.eveonline.com/Alliance/{{ $contract->alliance_id}}_32.png">&nbsp;{{ $contract->alliance_name }}</td>
											@else
											<td></td>
											@endif
											@if($contract->standing <= 10 && $contract->standing >= 5)
											<td style="vertical-align: middle"><span class="label label-primary">{{ $contract->standing }}</span></td>
											@elseif($contract->standing <= 5 && $contract->standing >= 0)
											<td style="vertical-align: middle"><span class="label label-info">{{ $contract->standing }}</span></td>
											@elseif($contract->standing <= 0 && $contract->standing >= -5)
											<td style="vertical-align: middle"><span class="label label-warning">{{ $contract->standing }}</span></td>
											@else
											<td style="vertical-align: middle"><span class="label label-danger">{{ $contract->standing }}</span></td>
											@endif
											<td style="vertical-align: middle">{{ $contract->date_issued }} : {{ \Carbon\Carbon::parse($contract->date_issued)->diffForHumans() }}</td>

										</tr>
										@endforeach
										@else
										<tr>
											<td colspan="7"><em>No Records Found</em></td>
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

@section('scripts')
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

<script>
	var path3 = "{{ route('autocomplete.systems') }}";
	$('input.typeahead-systems').typeahead({
		source:  function (system, process) {
			return $.get(path3, { system: system }, function (data4) {
				return process(data4);
			});
		}
	});


</script>
@stop