@extends('layouts.app')

@section('page-title', 'Keepstar Map')

@section('content')

<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
			Keepstar Map
			<div class="pull-right">
				<ol class="breadcrumb">
					<li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
					<li class="active">Intelligence Dashboard</li>
					<li class="active">Keepstar Map</li>
				</ol>
			</div>
		</h1>
	</div>
</div>

@include('partials.messages')


<div class="row col-md-12">
	<div class="panel panel-default">
		<div class="panel-heading">Keepstar Map</div>
		<div class="panel-body">
			<p>This module aggregates all the known keepstars within the structure dataset to provide a regional/alliance visualisation of all the known keepstars.
			</div>
		</div>
	</div>
</p>


<div class="row col-md-12">
	<div class="panel panel-default indices-chart">
		<div class="panel-heading">Keepstar Map Visualisation</div>
		<div class="panel-body chart">

			<div class="col-md-12" id="tree-container"></div>

		</div>
	</div>
</div>


@section('styles')
{!! HTML::style('assets/css/d3treeview.css') !!}
@stop

@stop


@section('scripts')
<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="https://d3js.org/d3.v3.min.js"></script>
<script>
	var json_data = "{{ URL::asset('assets/js/d3/alliances/alliances.json') }}";
</script>
{!! HTML::script('assets/js/d3/treeview.js') !!}
@stop

