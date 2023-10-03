@extends('layouts.app')

@section('page-title', 'Capital Tracking')

@section('content')


<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
			Capital Tracking
			<small>- Known Characters / Pilots and assoicated Hull Type for {{ $alliance_name }}</small>
			<div class="pull-right">
				<ol class="breadcrumb">
					<li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
					<li class="active">Capital Tracking</li>
				</ol>
			</div>
		</h1>
	</div>
</div>


@include('partials.messages')


<div class="row tab-search">
	<div class="col-md-5"></div>
</div>

<div class="row col-md-12">
	<div class="col-md-3">
		<div class="panel panel-default">

			<div class="panel-heading">
				Summary of Stats
			</div>
			<div class="panel-body">
				<table class="table" id="statistics-details">
					<thead>
						<tr>
							<th>Statistics</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>Titan Pilots</td>
							<td style="vertical-align: middle">{!! $characters->where('titan', 1)->count() !!}</td>
						</tr>
						<tr>
							<td>Faction Titan Pilots</td>
							<td style="vertical-align: middle">{!! $characters->where('faction_titan', 1)->count() !!}</td>
						</tr>
						<tr>
							<td>Supercarrier Pilots</td>
							<td style="vertical-align: middle">{!! $characters->where('super', 1)->count() !!}</td>
						</tr>
						<tr>
							<td>Faction Supercarrier Pilots</td>
							<td style="vertical-align: middle">{!! $characters->where('faction_super', 1)->count() !!}</td>
						</tr>
						<tr>
							<td>Carrier Pilots</td>
							<td style="vertical-align: middle">{!! $characters->where('carrier', 1)->count() !!}</td>
						</tr>
						<tr>
							<td>Force Axuillary Pilots</td>
							<td style="vertical-align: middle">{!! $characters->where('fax', 1)->count() !!}</td>
						</tr>

						<tr>
							<td>Dreadnought Pilots</td>
							<td style="vertical-align: middle">{!! $characters->where('dread', 1)->count() !!}</td>
						</tr>
						<tr>
							<td>Faction Dreadnought Pilots</td>
							<td style="vertical-align: middle">{!! $characters->where('faction_dread', 1)->count() !!}</td>
						</tr>

						<tr>
							<td>Monitor</td>
							<td style="vertical-align: middle">{!! $characters->where('monitor', 1)->count() !!}</td>
						</tr>

					</tbody>
				</table>
			</div>
		</div>
	</div>

	<div class="col-md-9">
		<div class="panel panel-default">
			<div class="panel-heading">Alliance : <b>{{ $alliance_name }}</b> : Report</div>
			<div class="panel-body">
				<div class="table-responsive top-border-table" id="location-table-wrapper">

					<table class="table" id="characters">
						<thead>
							<th>Character Name</th>
							<th>References</th>
							<th>Titan</th>
							<th>Faction Titan</th>
							<th>Supercarrier</th>
							<th>Faction Supercarrier</th>
							<th>Carrier</th>
							<th>Force Auxillarie</th>
							<th>Dreadnought</th>
							<th>Faction Dreadnoughts</th>
							<th>Monitor</th>
						</thead>

						<tbody>

							@if (isset($characters))              
							@foreach($characters as $character)


							<tr>

								<td style="vertical-align: middle">{!! $character->character_name !!}</td>
								<td style="vertical-align: middle">

									<a href="https://zkillboard.com/character/{{ $character->character_character_id }}" target="_blank">
										<span class="label label-pill label-danger">
											Zkill
										</span>
									</a>

									<a href="https://evewho.com/pilot/{{ $character->character_name }}"  target="_blank">
										<span class="label label-pill label-success">
											Evewho
										</span>
									</a>

								</td>
								<td style="vertical-align: middle">{!! $character->titan !!}</td>
								<td style="vertical-align: middle">{!! $character->faction_titan !!}</td>
								<td style="vertical-align: middle">{!! $character->super !!}</td>
								<td style="vertical-align: middle">{!! $character->faction_super !!}</td>
								<td style="vertical-align: middle">{!! $character->carrier !!}</td>
								<td style="vertical-align: middle">{!! $character->fax !!}</td>
								<td style="vertical-align: middle">{!! $character->dread !!}</td>
								<td style="vertical-align: middle">{!! $character->faction_dread !!}</td>
								<td style="vertical-align: middle">{!! $character->monitor !!}</td>
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

@section('scripts')
<script>
	$(document).ready(function(){
		$('#characters').DataTable( {
			"paging":   false,
			"searching": true,
			"pageLength": 500,
		}
		);

	});
</script>
@stop

