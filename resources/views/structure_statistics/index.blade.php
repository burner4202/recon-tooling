@extends('layouts.app')

@section('page-title', 'Known Structure Statistics')

@section('content')

<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
			Known Structure Statistics
			<div class="pull-right">
				<ol class="breadcrumb">
					<li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
					<li><a href="{{ route('intelligence.index') }}">Intelligence Dashboard</a></li>
					<li class="active">Known Structures Statistics</li>
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
				What do I do?
			</div>
			<div class="panel-body">
				
				Looking at rows of structures can be difficult.. simple, want to know if an alliance has a structure in a region.. and how many.. just look here.<br>
				If no filter has been applied, it will show a count for current structures that are alive in the database.

			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				Search Critera
			</div>
			<div class="panel-body">

				<div class="row tab-search">

					<div class="col-md-12"></div>
					<form method="GET" action="" accept-charset="UTF-8" id="statistics-form">

						<div class="col-md-2">
							System
							<div class="input-group custom-search-form">
								<input type="text" class="form-control typeahead-systems" name="system" value="{{ Input::get('system') }}" placeholder="..." autocomplete="off">
								<span class="input-group-btn">
									<button class="btn btn-default" type="submit" id="search-systems-btn">
										<span class="glyphicon glyphicon-search"></span>
									</button>
									@if (Input::has('system') && Input::get('system') != '')
									<a href="{{ route('structure_statistics.index') }}" class="btn btn-danger" type="button" >
										<span class="glyphicon glyphicon-remove"></span>
									</a>
									@endif
								</span>
							</div>
						</div>



						<div class="col-md-2">
							Constellation
							<div class="input-group custom-search-form">
								<input type="text" class="form-control typeahead-constellations" name="constellation" value="{{ Input::get('constellation') }}" placeholder="..." autocomplete="off">
								<span class="input-group-btn">
									<button class="btn btn-default" type="submit" id="search-constellations-btn">
										<span class="glyphicon glyphicon-search"></span>
									</button>
									@if (Input::has('constellation') && Input::get('constellation') != '')
									<a href="{{ route('structure_statistics.index') }}" class="btn btn-danger" type="button" >
										<span class="glyphicon glyphicon-remove"></span>
									</a>
									@endif
								</span>
							</div>
						</div>



						<div class="col-md-2">
							Region
							<div class="input-group custom-search-form">
								<input type="text" class="form-control typeahead-regions" name="region" value="{{ Input::get('region') }}" placeholder="..." autocomplete="off">
								<span class="input-group-btn">
									<button class="btn btn-default" type="submit" id="search-regions-btn">
										<span class="glyphicon glyphicon-search"></span>
									</button>
									@if (Input::has('region') && Input::get('region') != '')
									<a href="{{ route('structure_statistics.index') }}" class="btn btn-danger" type="button" >
										<span class="glyphicon glyphicon-remove"></span>
									</a>
									@endif
								</span>
							</div>
						</div>



						<div class="col-md-2">
							Corporations
							<div class="input-group custom-search-form">
								<input type="text" class="form-control typeahead-corporations" name="corporation" value="{{ Input::get('corporation') }}" placeholder="..." autocomplete="off">
								<span class="input-group-btn">
									<button class="btn btn-default" type="submit" id="search-corporations-btn">
										<span class="glyphicon glyphicon-search"></span>
									</button>
									@if (Input::has('corporation') && Input::get('corporation') != '')
									<a href="{{ route('structure_statistics.index') }}" class="btn btn-danger" type="button" >
										<span class="glyphicon glyphicon-remove"></span>
									</a>
									@endif
								</span>
							</div>
						</div>



						<div class="col-md-2">
							Alliances
							<div class="input-group custom-search-form">
								<input type="text" class="form-control typeahead-alliances" name="alliance" value="{{ Input::get('alliance') }}" placeholder="..." autocomplete="off">
								<span class="input-group-btn">
									<button class="btn btn-default" type="submit" id="search-alliances-btn">
										<span class="glyphicon glyphicon-search"></span>
									</button>
									@if (Input::has('alliance') && Input::get('alliance') != '')
									<a href="{{ route('structure_statistics.index') }}" class="btn btn-danger" type="button" >
										<span class="glyphicon glyphicon-remove"></span>
									</a>
									@endif
								</span>
							</div>
						</div>

						<div class="col-md-1">
							Abandoned
							{!! Form::select('abandoned', ['' => 'All', 'Yes' => 'Yes'], Input::get('abandoned'), ['id' => 'abandoned', 'class' => 'form-control']) !!}
						</div>


					</div>
				</form>
			</div>

		</div>
	</div>
</div>
</div>






<div class="row">
	<div class="col-md-12">
		<div class="col-md-12">
			<div class="panel panel-default structure-stats-chart">

				<div class="panel-heading">
					Structure Stats
				</div>
				<div class="panel-body chart">

					<div>
						<canvas id="myChart" height="495"></canvas>
					</div>

				</div>
			</div>
		</div>
	</div>	
</div>
@stop


@section('styles')
<style>
	.structure-stats-chart .chart {
		zoom: 1.23;
	}
</style>
{!! HTML::style('assets/plugins/croppie/croppie.css') !!}
@stop

@section('scripts')
<script>
	var labels = {!! json_encode(array_keys($chart)) !!};
	var online = {!! json_encode(array_values($chart)) !!};
</script>

{!! HTML::script('assets/plugins/croppie/croppie.js') !!}
{!! HTML::script('assets/js/moment.min.js') !!}
{!! HTML::script('assets/js/as/btn.js') !!}
{!! HTML::script('assets/js/chart.min.js') !!}
{!! HTML::script('assets/js/as/upwell_statistics.js') !!}


<script>

	$("#search").change(function () {
		$("#statistics-form").submit();
	});

	$("#region").change(function () {
		$("#statistics-form").submit();
	});

	$("#alliance").change(function () {
		$("#statistics-form").submit();
	});

	$("#system").change(function () {
		$("#statistics-form").submit();
	});

	$("#constellation").change(function () {
		$("#statistics-form").submit();
	});

	$("#abandoned").change(function () {
		$("#statistics-form").submit();
	});
</script>
<script>

	var path1 = "{{ route('autocomplete.systems') }}";
	$('input.typeahead-systems').typeahead({
		source:  function (system, process) {
			return $.get(path1, { system: system }, function (data1) {
				return process(data1);
			});
		}
	});
</script>

<script>
	var path2 = "{{ route('autocomplete.regions') }}";
	$('input.typeahead-regions').typeahead({
		source:  function (region, process) {
			return $.get(path2, { region: region }, function (data2) {
				return process(data2);
			});
		}
	});


</script>
<script>
	var path3 = "{{ route('autocomplete.corporations') }}";
	$('input.typeahead-corporations').typeahead({
		source:  function (corporation, process) {
			return $.get(path3, { corporation: corporation }, function (data3) {
				return process(data3);
			});
		}
	});


</script>

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
<script>
	var path5 = "{{ route('autocomplete.alliance_tickers') }}";
	$('input.typeahead-alliance_tickers').typeahead({
		source:  function (alliance_ticker, process) {
			return $.get(path5, { alliance_ticker: alliance_ticker }, function (data5) {
				return process(data5);
			});
		}
	});


</script>

<script>
	var path6 = "{{ route('autocomplete.constellations') }}";
	$('input.typeahead-constellations').typeahead({
		source:  function (region, process) {
			return $.get(path6, { region: region }, function (data6) {
				return process(data6);
			});
		}
	});


</script>

@stop

