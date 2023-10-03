@extends('layouts.app')

@section('page-title', 'Help Dashboard')

@section('content')

<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
			Help Dashboard
			<div class="pull-right">
				<ol class="breadcrumb">
					<li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
					<li class="active">Help</li>
				</ol>
			</div>
		</h1>
	</div>
</div>

<ul class="nav nav-tabs">
	<li class="active"><a data-toggle="tab" href="#about">About</a></li>
	<li><a data-toggle="tab" href="#characters">Characters</a></li>
	<li><a data-toggle="tab" href="#location-tools">Location Tools</a></li>
	<li><a data-toggle="tab" href="#universe">Universe</a></li>
</ul>

<div class="tab-content">
	<div id="about" class="tab-pane fade in active">

		@include('help.about.index')
		
	</div>

	<div id="characters" class="tab-pane fade">

		@include('help.characters.index')

	</div>
	<div id="location-tools" class="tab-pane fade">

		@include('help.location-tools.index')

	</div>

	<div id="universe" class="tab-pane fade">
		
		@include('help.universe.index')

	</div>

	<div id="system-indices" class="tab-pane fade">

		@include('help.system-indices.index')

	</div>

	<div id="upwell-structures" class="tab-pane fade">

		@include('help.upwell-structures.index')

	</div>

	<div id="modules-rigs" class="tab-pane fade">

		@include('help.module-rigs.index')

	</div>

	<div id="alliance-corporation" class="tab-pane fade">

		@include('help.alliance-corporation.index')


	</div>

	<div id="standings" class="tab-pane fade">
		
		@include('help.standings.index')

	</div>

	<div id="task-manager" class="tab-pane fade">
		
		@include('help.task-manager.index')

	</div>

	<div id="capital-tracking" class="tab-pane fade">
		
		@include('help.capital-tracking.index')

	</div>
</div>




@stop