@extends('layouts.app')

@section('page-title', trans('app.dashboard'))

@section('content')

<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
			Intelligence Dashboard
			<div class="pull-right">
				<ol class="breadcrumb">
					<li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
					<li class="active">Intelligence Dashboard</li>
				</ol>
			</div>
		</h1>
	</div>
</div>

@include('partials.messages')

<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				Intelligence Dashboard
			</div>
			<div class="panel-body">
				This section of the tools allows for the analysis of the recon dataset.<br>
				Each module is detailed with a brief description of the functionality.
			</div>
		</div>
	</div>
</div>

<div class="row col-md-12">
	@permission('system.indices.view')
	<div class="col-md-2">
		<a href="{{ route('indices.index') }}" class="panel-link" data-toggle="tooltip">
			<div class="panel panel-default dashboard-panel">
				<div class="panel-body">
					<div class="icon">
						<i class="fa fa-bar-chart"></i>
					</div>
					<p class="lead">System Indices</p>
					<small class="text-muted text-center">
						
						<p>This module pulls the system indexes from CCP daily. It presents information that can be used to find expensive structures/T2 Rigged Reaction Tatara's and Titan Production, among others.</p>
						
						<p>The day on day delta function will allow for the observer to identify 'as a job is installed'.</p>
					</small>
				</div>
			</div>
		</a>
	</div>
	@endpermission

	@permission('sovereignty.view')
	<div class="col-md-2">
		<a href="{{ route('sovereignty.index') }}" class="panel-link" data-toggle="tooltip">
			<div class="panel panel-default dashboard-panel">
				<div class="panel-body">
					<div class="icon">
						<i class="fa fa-map-marker"></i>
					</div>
					<p class="lead">Sovereignty</p>
					<small class="text-muted text-center">
						
						<p>This module pulls the in game Sovereignty from CCP daily. It presents public information simiar to DOTLan.</p>
						
						<p>Additionally, the data set is correlated with the structure database to identify titan production and jump bridge networks within the Sovereign space, including ownership.</p>
					</small>
				</div>
			</div>
		</a>
	</div>
	@endpermission

	@permission('alliances.view')
	<div class="col-md-2">
		<a href="{{ route('alliances.index') }}" class="panel-link" data-toggle="tooltip">
			<div class="panel panel-default dashboard-panel">
				<div class="panel-body">
					<div class="icon">
						<i class="fa fa-bank"></i>
					</div>
					<p class="lead">Alliances</p>
					<small class="text-muted text-center">
						
						<p>This module pulls the in game Alliances & associated Corporations from CCP daily.</p>
						
						<p>Each alliance is correlated with the structure database, system indexes, sovereignty, alliance standings & regional moon datasets to give an overall intelligence report on each alliance.</p>
					</small>
				</div>
			</div>
		</a>
	</div>

	<div class="col-md-2">
		<a href="{{ route('alliance_health.index') }}" class="panel-link" data-toggle="tooltip">
			<div class="panel panel-default dashboard-panel">
				<div class="panel-body">
					<div class="icon">
						<i class="fa fa-heartbeat"></i>
					</div>
					<p class="lead">Alliances Health Index</p>
					<small class="text-muted text-center">
						
						<p>This module pulls the in game using Infrastructure Hubs & ADMS to generate a health index daily.</p>
						
						<p>Each alliance is scored based on their empire spawl using ADMS and Infrastructure Count, Ratting Kills/Structures/Indexes will be integrated eventually.</p>
					</small>
				</div>
			</div>
		</a>
	</div>
	@endpermission

	@permission('regional.report.view')
	<div class="col-md-2">
		<a href="{{ route('regional.report.index') }}" class="panel-link" data-toggle="tooltip">
			<div class="panel panel-default dashboard-panel">
				<div class="panel-body">
					<div class="icon">
						<i class="fa fa-circle-o"></i>
					</div>
					<p class="lead">Military Regional Report</p>
					<small class="text-muted text-center">
						
						<p>This module aggregates the data within the tools to create a real time report of each region.</p>
						
						<p>Each region is correlated with the structure database, system indexes, sovereignty, regional moon datasets to give an overall intelligence report on each region.</p>
					</small>
				</div>
			</div>
		</a>
	</div>
	@endpermission

	@permission('stager.view')
	<div class="col-md-2">
		<a href="{{ route('stager.index') }}" class="panel-link" data-toggle="tooltip">
			<div class="panel panel-default dashboard-panel">
				<div class="panel-body">
					<div class="icon">
						<i class="fa fa-street-view"></i>
					</div>
					<p class="lead">Staging Systems</p>
					<small class="text-muted text-center">
						
						<p>This module analyses the structure dataset to identify alliance Keepstars.</p>

						<p>Each alliance's most expensive keepstar is identified within the dataset, in turn flagging their 'home'.</p>
						
					</small>
				</div>
			</div>
		</a>
	</div>
	@endpermission

</div>
<div class="row col-md-12">
		@permission('capital.tracking')
	<div class="col-md-2">
		<a href="{{ route('killmail.index') }}" class="panel-link" data-toggle="tooltip">
			<div class="panel panel-default dashboard-panel">
				<div class="panel-body">
					<div class="icon">
						<i class="fa fa-eject"></i>
					</div>
					<p class="lead">Capital Tracking</p>
					<small class="text-muted text-center">
						
						<p>This module analyses killmails to identify characters capital of flying a select set of hulls, including Carriers/Supers/Titans/Faxes including Faction</p>
					</small>
				</div>
			</div>
		</a>
	</div>
	@endpermission

	@permission('activitytracker.view')
	<div class="col-md-2">
		<a href="{{ route('activitytracker.index') }}" class="panel-link" data-toggle="tooltip">
			<div class="panel panel-default dashboard-panel">
				<div class="panel-body">
					<div class="icon">
						<i class="fa fa-list"></i>
					</div>
					<p class="lead">Structure Activity Log</p>
					<small class="text-muted text-center">
						
						<p>This module is a full searchable history of every event recorded relation to structure entry.</p>

					</small>
				</div>
			</div>
		</a>
	</div>

	<div class="col-md-2">
		<a href="{{ route('activitytracker.metrics_index') }}" class="panel-link" data-toggle="tooltip">
			<div class="panel panel-default dashboard-panel">
				<div class="panel-body">
					<div class="icon">
						<i class="fa fa-area-chart"></i>
					</div>
					<p class="lead">Structure Activity Reports</p>
					<small class="text-muted text-center">
						
						<p>This module takes the information from the Structure Activity Log and presents it in a readable format.</p>

					</small>
				</div>
			</div>
		</a>
	</div>
	@endpermission


	@permission('moon.regional.report.view')
	<div class="col-md-2">
		<a href="{{ route('moons.regional_report') }}" class="panel-link" data-toggle="tooltip">
			<div class="panel panel-default dashboard-panel">
				<div class="panel-body">
					<div class="icon">
						<i class="fa fa-moon-o"></i>
					</div>
					<p class="lead">Moon Regional Reports</p>
					<small class="text-muted text-center">
						
						<p>This module aggregates the moon data from 2020 scans and creates a report per region to analyse the moon data</p>

					</small>
				</div>
			</div>
		</a>
	</div>

	<div class="col-md-2">
		<a href="{{ route('moons.moons_compare') }}" class="panel-link" data-toggle="tooltip">
			<div class="panel panel-default dashboard-panel">
				<div class="panel-body">
					<div class="icon">
						<i class="fa fa-line-chart"></i>
					</div>
					<p class="lead">Moons Comparisons Report </p>
					<small class="text-muted text-center">
						
						<p>This module compares both 2017 and 2020 data after CCP changed the DNA of the moon ores to give an overview of the changes made.</p>

					</small>
				</div>
			</div>
		</a>
	</div>
	@endpermission

	@permission('view.enemy.standings')
	<div class="col-md-2">
		<a href="{{ route('enemy_standings.index') }}" class="panel-link" data-toggle="tooltip">
			<div class="panel panel-default dashboard-panel">
				<div class="panel-body">
					<div class="icon">
						<i class="fa fa-folder-open"></i>
					</div>
					<p class="lead">Enemy Standings </p>
					<small class="text-muted text-center">
						
						<p>This module pulls enemy standings daily, allowing for corss checking of alliance relationships, fleet commands and neutral logistics.</p>

					</small>
				</div>
			</div>
		</a>
	</div>
	@endpermission


</div>
<div class="row col-md-12">

		@permission('public_contracts.view')
	<div class="col-md-2">
		<a href="{{ route('public_contracts.index') }}" class="panel-link" data-toggle="tooltip">
			<div class="panel panel-default dashboard-panel">
				<div class="panel-body">
					<div class="icon">
						<i class="fa fa-eye"></i>
					</div>
					<p class="lead">Public Contracts </p>
					<small class="text-muted text-center">
						
						<p>This module pulls pulls public contracts regularly for Delve/Querious/Fountain and Peroid Basis.</p>
						<p>If You are selling a carrier/dread/fax/super/titan or selling shit in NPC Delve, IT WILL FIND YOU.</p>

					</small>
				</div>
			</div>
		</a>
	</div>
	@endpermission

	@permission('keepstar_tree.view')
	<div class="col-md-2">
		<a href="{{ route('regional_tree.index') }}" class="panel-link" data-toggle="tooltip">
			<div class="panel panel-default dashboard-panel">
				<div class="panel-body">
					<div class="icon">
						<i class="fa fa-home"></i>
					</div>
					<p class="lead">Keepstars </p>
					<small class="text-muted text-center">
						
						<p>This module aggregates all the known keepstars within the structure dataset to provide a regional/alliance visualisation of all the known keepstars.</p>

					</small>
				</div>
			</div>
		</a>
	</div>

	@endpermission
	@permission('statistics.view')
	<div class="col-md-2">
		<a href="{{ route('structure_statistics.index') }}" class="panel-link" data-toggle="tooltip">
			<div class="panel panel-default dashboard-panel">
				<div class="panel-body">
					<div class="icon">
						<i class="fa fa-list-alt"></i>
					</div>
					<p class="lead">Structure Statistics </p>
					<small class="text-muted text-center">

						<p>This module aggregates all the known structures and spits them out allowing for a filterable visual display, beacuse meh to much text on the structures page.</p>

					</small>
				</div>
			</div>
		</a>
	</div>
	@endpermission

	@permission('adm_watch.view')
	<div class="col-md-2">
		<a href="{{ route('adm_watch.index') }}" class="panel-link" data-toggle="tooltip">
			<div class="panel panel-default dashboard-panel">
				<div class="panel-body">
					<div class="icon">
						<i class="fa fa-magic"></i>
					</div>
					<p class="lead">ADM Watch</p>
					<small class="text-muted text-center">

						<p>This module aggregates all of the sov and allows for adding of systems to 'watch' for ADMs</p>

					</small>
				</div>
			</div>
		</a>
	</div>
	@endpermission
	@permission('fc_hunter.view')
	<div class="col-md-2">
		<a href="{{ route('fc.hunter.index') }}" class="panel-link" data-toggle="tooltip">
			<div class="panel panel-default dashboard-panel">
				<div class="panel-body">
					<div class="icon">
						<i class="fa fa-crosshairs"></i>
					</div>
					<p class="lead">Fleet Commander Hunter</p>
					<small class="text-muted text-center">

						<p>This module aggregates all of character data within the tools and provides us a nice little table of fleet commanders, allowing us to track and update standings of hostile fleet commanders.</p>

					</small>
				</div>
			</div>
		</a>
	</div>
	@endpermission

	@permission('coalitions.view')
	<div class="col-md-2">
		<a href="{{ route('coalitions.list') }}" class="panel-link" data-toggle="tooltip">
			<div class="panel panel-default dashboard-panel">
				<div class="panel-body">
					<div class="icon">
						<i class="fa fa-empire"></i>
					</div>
					<p class="lead">Coalitions</p>
					<small class="text-muted text-center">

						<p>This module shows current coalitions and alliances/member count.</p>

					</small>
				</div>
			</div>
		</a>
	</div>
	@endpermission


</div>
<div class="row col-md-12">

		@permission('coordination.use')
	<div class="col-md-2">
		<a href="{{ route('coord.index') }}" class="panel-link" data-toggle="tooltip">
			<div class="panel panel-default dashboard-panel">
				<div class="panel-body">
					<div class="icon">
						<i class="fa fa-compass"></i>
					</div>
					<p class="lead">Coordination Dashboard</p>
					<small class="text-muted text-center">

						<p>This module of the tools, will allow you to monitor ALL the fleets that are currently deployed.</p>

					</small>
				</div>
			</div>
		</a>
	</div>
	@endpermission

	@permission('npc_kills.view')
	<div class="col-md-2">
		<a href="{{ route('npc_kills.regions') }}" class="panel-link" data-toggle="tooltip">
			<div class="panel panel-default dashboard-panel">
				<div class="panel-body">
					<div class="icon">
						<i class="fa fa-align-left"></i>
					</div>
					<p class="lead">NPC Kills Regional Tracker</p>
					<small class="text-muted text-center">

						<p>This module of the tools, will allow you to monitor NPC kills over time on a regional level.</p>

					</small>
				</div>
			</div>
		</a>
	</div>
	@endpermission

	@permission('jump_freighter.view')
	<div class="col-md-2">
		<a href="{{ route('jump_freighter.index') }}" class="panel-link" data-toggle="tooltip">
			<div class="panel panel-default dashboard-panel">
				<div class="panel-body">
					<div class="icon">
						<i class="fa fa-truck"></i>
					</div>
					<p class="lead">Jump Freighter Hunter</p>
					<small class="text-muted text-center">

						<p>This module aggregates all of character data within the tools and provides us a nice little table of jump freighters, allowing us to track and KILL them!</p>

					</small>
				</div>
			</div>
		</a>
	</div>
	@endpermission

	@permission('character.view')
	<div class="col-md-2">
		<a href="{{ route('character.index') }}" class="panel-link" data-toggle="tooltip">
			<div class="panel panel-default dashboard-panel">
				<div class="panel-body">
					<div class="icon">
						<i class="fa fa-male"></i>
					</div>
					<p class="lead">Character Search</p>
					<small class="text-muted text-center">

						<p>This module aggregates all of character data within the tools and provides us a nice little search function.</p>

					</small>
				</div>
			</div>
		</a>
	</div>
	@endpermission

	@permission('character.view')
	<div class="col-md-2">
		<a href="{{ route('character_reporting.index') }}" class="panel-link" data-toggle="tooltip">
			<div class="panel panel-default dashboard-panel">
				<div class="panel-body">
					<div class="icon">
						<i class="fa fa-book"></i>
					</div>
					<p class="lead">Character Reporting Search</p>
					<small class="text-muted text-center">

						<p>This module aggregates all of character reporting data within the tools and provides us with intelligence of each character.</p>

					</small>
				</div>
			</div>
		</a>
	</div>
	@endpermission
</div>




@stop

@section('styles')
{{ Html::style('css/intel.css') }}
@stop

