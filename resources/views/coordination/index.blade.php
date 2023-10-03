@extends('layouts.app')

@section('page-title', 'Coordination Dashboard')

@section('content')

<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
			Coordination
			<small> - dashboard for monitoring fleets out & stuffs.</small>
			<div class="pull-right">
				<ol class="breadcrumb">
					<li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
					<li class="active">Coordination</li>
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
			<div class="panel-heading">Coordination Dash, string optional.</div>
			<div class="panel-body">
				<p>This module of the tools, will allow you to monitor ALL the fleets that are currently deployed.<br>
					Requirements are; a pap is opened on aDashboard, and the boss is 'checked in', this will allow the fleet to be displayed here<br>
					The fleet along with 'watched' systems will update every 60 seconds, until the pap is 'closed'<br>
					In order to monitor staging systems, add the system, encourage scouts/recon/eyes to be logged into aDashboard with their scout and update local scans as required.<br>
				If a local scan is older than 15 minutes, it will disppear.</p>
			</div>					

		</div>
	</div>

	<div class="row col-md-12">
		<div class="col-md-2">
			<div class="panel panel-default">
				<div class="panel-heading">Add System to Watch</div>
				<div class="panel-body">

					<form method="post" action="{{ route('coord.add_system') }}" enctype="multipart/form-data">
						{{ csrf_field() }}
						<div class="panel-body" >
							<div class="col-md-12">


								<div class="form-group">
									<label for="system">System</label>
									<input type="text" class="typeahead-systems form-control" name="system" id="system" placeholder="Search Systems" autocomplete="off" >
								</div>

							</div>
						</div>

						<div class="form-group row">
							<div style="text-align: center;">
								<button type="submit" class="btn btn-success">Add System</button>
							</div>
						</div>
					</form>
				</div>					
			</div>
		</div>



		<div class="col-md-10">
			<div class="panel panel-default">
				<div class="panel-heading">Current Operations (Updated every 60 seconds), Pap must be open and boss checked in, on aDashboard.
				</div>
				<div class="panel-body">

					<div class="content">
						<div id="fleet"></div>

					</div>

				</div>

			</div>
		</div>
	</div>

	<div class="row col-md-12">
		<div class="col-md-2">
			<div class="panel panel-default">
				<div class="panel-heading">Watched Systems.
				</div>
				<div class="panel-body">
					<div class="table-responsive top-border-table" id="list-of-watched-systems-information">

						<table class="table" id="stagers">
							<thead>
								<th> Solar System</th>
								<th> Region</th>
								<th> Remove</th>


							</thead>

							<tbody>


								@if (isset($systems))              
								@foreach($systems as $system)

								<tr>	

									<td style="vertical-align: middle"><a href="{{ route('solar.system', $system->solar_system_id )}}" target="_blank">{{  $system->solar_system_name }}</a></td>	
									<td style="vertical-align: middle"><a href="https://evemaps.dotlan.net/map/{{  str_replace(" ", "_", $system->region_name) }}" target="_blank">{{  $system->region_name }}</a></td>		
									<td style="vertical-align: middle"><a href="{{ route('coord.remove_system', $system->solar_system_id )}}" class="btn btn-danger btn-circle" title="Remove System"
										data-toggle="tooltip"
										data-placement="top"
										data-method="GET"
										data-confirm-title="Please Confirm"
										data-confirm-text="Are you sure?"
										data-confirm-delete="Yes Remove">
										<i class="glyphicon glyphicon-trash"></i>
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

		<div class="col-md-10">
			<div class="panel panel-default">
				<div class="panel-heading">Watched Systems Local Scans (15 minute expiry) - Have scouts add dscans to aDash for updates.</div>
				<div class="panel-body">

					<div class="col-md-12">


						<div class="content">
							<div id="watched_systems"></div>

						</div>

					</div>




				</div>					
			</div>
		</div>

		<div class="col-md-10">
			<div class="panel panel-default">
				<div class="panel-heading">Watched Systems Dscans (15 minute expiry) - Have scouts add dscans to aDash for updates.</div>
				<div class="panel-body">

					<div class="col-md-12">


						<div class="content">
							<div id="watched_systems_dscan"></div>

						</div>

					</div>




				</div>					
			</div>
		</div>
	</div>
</div>



@stop
@section('scripts')
<script type="text/javascript" src="js/app.js"></script>
@stop

