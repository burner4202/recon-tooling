@extends('layouts.app')

@section('page-title', 'Enemy Alliance Standings')

@section('content')


<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
			Enemy Alliance Standings
			<small>- uh huh</small>
			<div class="pull-right">
				<ol class="breadcrumb">
					<li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
					<li><a href="{{ route('intelligence.index') }}">Intelligence Dashboard</a></li>
					<li class="active">Enemy Alliance Standings</li>
				</ol>
			</div>
		</h1>
	</div>
</div>


@include('partials.messages')


<div class="row tab-search">
	<div class="col-md-5"></div>
</div>


<div class="col-md-12">
	<div class="panel panel-default">
		<div class="panel-heading">Alliances</div>
		<div class="panel-body">
			<p>This module of the tools pulls enemy standings through some magic wizardary, select each alliance to see their standings.
			</div>
		</div>
	</div>
</p>


@if (isset($alliances))              
@foreach($alliances as $alliance)

<div class="col-md-2">
	<a href="{{ route('enemy_standings.view', $alliance->as_enemy_alliance_id)}}" class="panel-link" data-toggle="tooltip">
		<div class="panel panel-default dashboard-panel">
			<div class="panel-body" align="center">
				<img class="img-circle" src="https://images.evetech.net/alliances/{{ $alliance->as_enemy_alliance_id }}/logo?size=128">
				<br>{{ $alliance->as_enemy_alliance_name }}
			</div>
		</div>
	</a>
</div>


@endforeach
@endif

@stop


