@extends('layouts.app')

@section('page-title', $dossier->dossier_title)

@section('content')

<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
			{{ $dossier->dossier_title }}
			<small> - for <b>{!! $corporation->corporation_name !!}</b></small>
			<div class="pull-right">
				<ol class="breadcrumb">
					<li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
					<li class="active">Dossier for {!! $corporation->corporation_name !!}</li>
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

<div class="row col-md-12">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				Dossier for <b>{!! $corporation->corporation_name !!}</b>
			</div>
			<div class="panel-body">
				Below is various sets of intelligence on <b>{!! $corporation->corporation_name !!}</b>, using this information and the framework below, a relationship score  has been be calculated on the likelihood that the target alliance is associated with the group in question. <br>

				The Dossier is based on a weighted average calculation with the maximum of 100%. Each weight is detailed beside each option.<br><br>
				@if($dossier->state <= 1)
				<div class="col-md-12">
					<div class="col-md-2">
		
						<a href="{{ route('dossier.approved', $dossier->id) }}" class="btn btn-success" id="approve">
							Approve.
						</a>
				
					</div>
				</div>
				@endif
			</div>
		</div>
	</div>
</div>




<div class="row col-md-12">
	<div class="col-md-2">
		<div id="edit-user-panel" class="panel panel-default">
			<div class="panel-heading">
				Corporation Information
			</div>

			<div class="panel-body panel-profile">
				<div class="image">
					<img alt="image" class="img-circle avatar" src="https://imageserver.eveonline.com/Corporation/{{ $dossier->corporation_id }}_128.png">
				</div>
				<div class="name"><strong>{{ $dossier->corporation_name }}</strong></div>

				<br>

				<div class="col-md-12">
					<div class="col-md-4">
						<a href="https://evewho.com/corporation/{{ $dossier->corporation_id }}" class="label label-info" data-toggle="tooltip" target="_blank">
							<span >EVE Who</span>
						</a>
					</div>
					<div class="col-md-4">
						<a href="https://zkillboard.com/corporation/{{ $dossier->corporation_id }}" class="label label-danger" data-toggle="tooltip" target="_blank">
							<span >ZKill</span>
						</a>
					</div>

					<div class="col-md-4">
						<a href="https://evemaps.dotlan.net/corp/{{ $dossier->corporation_id }}" class="label label-warning" data-toggle="tooltip" target="_blank">
							<span >DOT Lan</span>
						</a>
					</div>
				</div>
				<br>
				<table class="table table-hover">
					<thead>
						<tr>
							<th colspan="3">Additional Information</th>
						</tr>
					</thead>
					<tbody>
						@if($dossier->alliance_id > 1)
						<tr>
							<td>Alliance</td>
							<td><a href="{{ route('alliance.view', $dossier->alliance_id) }}">{{ $dossier->alliance_name }}</a></td>
						</tr>
						@else
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
				<table class="table table-hover">
					<thead>
						<tr>
							<th colspan="3">Target Alliance Information</th>
						</tr>
					</thead>
					<tbody>

						<tr>
							<td>Target Alliance</td>
							<td><a href="{{ route('alliance.view', $dossier->target_alliance_id) }}">{{ $dossier->target_alliance_name }}</a></td>
						</tr>
						<tr>
							<td>Relationship Score</td>
							<td>{{ $dossier->relationship_score }}/100%</td>
						</tr>
						<tr>
							<td>Corporation Function </td>
							<td>{{ $dossier->corporation_function }}</td>
						</tr>

					</tbody>
				</table>
			</div>
		</div>
	</div>

	<div class="col-md-3">
		<div id="edit-user-panel" class="panel panel-default">
			<div class="panel-heading">
				Dossier Information
			</div>
			<div class="panel-body panel-profile">
				<table class="table table-hover">
					<thead>
						<tr>
							<th colspan="3">Information</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>Title</td>
							<td>{{ $dossier->dossier_title }}</td>
						</tr>
						<tr>
							<td>Author</td>
							<td>{{ $dossier->created_by_username }}</td>
						</tr>
						<tr>
							<td>Approved</td>
							<td>{{ $dossier->approved_by_username }}</td>
						</tr>
						<tr>
							<td>Date</td>
							<td>{{ $dossier->approved_date }}</td>
						</tr>

						@if($dossier->state == 1) 
						<tr>
							<td>Dossier Status </td>
							<td>
								<a href="#" class="label label-warning" data-toggle="tooltip" data-placement="top">
									<span>Draft</span>
								</a>
							</td>
						</tr>
						@elseif($dossier->state == 0) 
						<tr>
							<td>Dossier Status </td>
							<td>
								<a href="#" class="label label-danger" data-toggle="tooltip" data-placement="top">
									<span>Deleted</span>
								</a>
							</td>
						</tr>
						@else
							<tr>
							<td>Dossier Status </td>
							<td>
								<a href="#" class="label label-success" data-toggle="tooltip" data-placement="top">
									<span>Approved</span>
								</a>
							</td>
						</tr>
						@endif

						<tr>
							<td>Shell Corporation (10%)</td>
							<td>{{ $dossier->is_shell_corporation }}</td>
						</tr>

						<tr>
							<td>EVE Who Relationship (15%)</td>
							<td>{{ $dossier->has_relationship_via_evewho_history }}</td>
						</tr>
						<tr>
							<td>Office in Alliance Staging (5%)</td>
							<td>{{ $dossier->has_office_in_alliance_staging }}</td>
						</tr>

						<tr>
							<td>Related Killboard Activity (10%)</td>
							<td>{{ $dossier->has_related_killboard_activity }}</td>
						</tr>

						<tr>
							<td>Present of Cyno Alts (5%)</td>
							<td>{{ $dossier->presence_of_cyno_alts }}</td>
						</tr>

						<tr>
							<td>Present of Logistic Alts (5%)</td>
							<td>{{ $dossier->presence_of_freighter_alts }}</td>
						</tr>

						<tr>
							<td>Locators of Confirm Character Locations (5%)</td>
							<td>{{ $dossier->locators_confirm_location_of_related_alliance }}</td>
						</tr>

						<tr>
							<td>Shared Structures in Space (20%)</td>
							<td>{{ $dossier->has_structures_in_related_system_of_target_alliance }}</td>
						</tr>

						<tr>
							<td>Structures in High Index Systems (10%)</td>
							<td>{{ $dossier->has_structures_in_systems_with_very_high_indexes }}</td>
						</tr>

						<tr>
							<td>Structures on Expensive Moons (15%)</td>
							<td>{{ $dossier->has_structures_on_expensive_money_moons }}</td>
						</tr>

					</tbody>
				</table>
				
			</div>
		</div>
	</div>

		<div class="col-md-7">
		<div id="edit-user-panel" class="panel panel-default">
			<div class="panel-heading">
				Dossier Information
			</div>
			<div class="panel-body">
				
				{!! $dossier->notes !!}
				
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