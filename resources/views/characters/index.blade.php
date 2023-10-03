@extends('layouts.app')

@section('page-title', 'Intelligence | Characters')

@section('content')

<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
			Character Search
			<small> - find character information</small>
			<div class="pull-right">
				<ol class="breadcrumb">
					<li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
					<li><a href="{{ route('intelligence.index') }}">Intelligence Dashboard</a></li>
					<li>Character Search</li>
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
				Character Dashboard
			</div>
			<div class="panel-body">
				This section of the tools allows for the analysis of the recon character dataset.<br>
				Search for a character to view intelligence on each character.
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				Search for a Character
			</div>
			<div class="panel-body">
				<div class="row tab-search">
					<div class="col-md-12"></div>
					<form method="GET" action="" accept-charset="UTF-8" id="character-form">
						<div class="col-md-4">
							Character Name
							<div class="input-group custom-search-form">
								<input type="text" class="form-control" name="search" value="{{ Input::get('search') }}" placeholder="Search Character Name">

								<span class="input-group-btn">
									<button class="btn btn-default" type="submit" id="search-standings-btn"><span class="glyphicon glyphicon-search"></span></button>
								</span>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

@stop