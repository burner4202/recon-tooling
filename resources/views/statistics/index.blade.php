@extends('layouts.app')

@section('page-title', 'Statistics')

@section('content')

<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
			Realtime Known Structure Statistics
			<div class="pull-right">
				<ol class="breadcrumb">
					<li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
					<li class="active">Statistics</li>
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

<div class="row tab-search">

	<div class="col-md-12"></div>
	<form method="GET" action="" accept-charset="UTF-8" id="statistics-form">




		<div class="col-md-2">
			Type
			{!! Form::select('type', $type, Input::get('type'), ['id' => 'type', 'class' => 'form-control']) !!}
		</div>

		<div class="col-md-1">
			State
			{!! Form::select('state',  $state, Input::get('state'), ['id' => 'state', 'class' => 'form-control']) !!}
		</div>


		<div class="col-md-1">
			Status
			{!! Form::select('status',  $status, Input::get('status'), ['id' => 'status', 'class' => 'form-control']) !!}
		</div>

		<div class="col-md-2">
			System
			<div class="input-group custom-search-form">
				<input type="text" class="form-control typeahead-systems" name="system" value="{{ Input::get('system') }}" placeholder="..." autocomplete="off">
				<span class="input-group-btn">
					<button class="btn btn-default" type="submit" id="search-systems-btn">
						<span class="glyphicon glyphicon-search"></span>
					</button>
					@if (Input::has('system') && Input::get('system') != '')
					<a href="{{ route('statistics.index') }}" class="btn btn-danger" type="button" >
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
					<a href="{{ route('statistics.index') }}" class="btn btn-danger" type="button" >
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
					<a href="{{ route('statistics.index') }}" class="btn btn-danger" type="button" >
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
					<a href="{{ route('statistics.index') }}" class="btn btn-danger" type="button" >
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
					<a href="{{ route('statistics.index') }}" class="btn btn-danger" type="button" >
						<span class="glyphicon glyphicon-remove"></span>
					</a>
					@endif
				</span>
			</div>
		</div>

		
		<div class='col-md-2 pull-right'>
			<div class="form-group">
				To:
				<div class='input-group date' id='date-to' >
					<input type='text' class="form-control" name="date-to" value="{{ Input::get('date-to') ?? \Carbon\Carbon::now()->format('d-m-Y') }}" autocomplete="off"/>
					<span class="input-group-addon">

						<span class="glyphicon glyphicon-calendar"></span>

						
					</span>
					<span class="input-group-btn">
					<button class="btn btn-default" type="submit" id="search-alliances-btn">
						<span class="glyphicon glyphicon-search"></span>
					</button>
					@if (Input::has('date-to') && Input::get('date-to') != '')
					<a href="{{ route('statistics.index') }}" class="btn btn-danger" type="button" >
						<span class="glyphicon glyphicon-remove"></span>
					</a>
					@endif
				</span>
				</div>
			</div>
		</div>
		<div class='col-md-2 pull-right'>
			<div class="form-group">
				From:
				<div class='input-group date' id='date-from' >
					<input type='text' class="form-control" name="date-from" value="{{ Input::get('date-from') }}" autocomplete="off"/>
					<span class="input-group-addon">
						
						<span class="glyphicon glyphicon-calendar"></span>
						
					</span>
					<span class="input-group-btn">
					<button class="btn btn-default" type="submit" id="search-alliances-btn">
						<span class="glyphicon glyphicon-search"></span>
					</button>
					@if (Input::has('date-from') && Input::get('date-from') != '')
					<a href="{{ route('statistics.index') }}" class="btn btn-danger" type="button" >
						<span class="glyphicon glyphicon-remove"></span>
					</a>
					@endif
				</span>
				</div>
			</div>
		</div>
		

		@if (
			Input::has('search') && Input::get('search') != '' || 
			Input::has('state') && Input::get('state') != '' ||
			Input::has('type') && Input::get('type') != '' ||
			Input::has('status') && Input::get('status') != '' ||
			Input::has('corporation') && Input::get('corporation') != '')
			<a href="{{ route('statistics.index') }}" class="btn btn-danger" type="button" >
				<span class="glyphicon glyphicon-remove"></span>
			</a>
			@endif



		</div>
	</form>
</div>




<div class="row">
	<div class="col-md-12">
		<div class="col-md-3">
			<div class="panel panel-default">

				<div class="panel-heading">
					Upwell Structures
				</div>
				<div class="panel-body">
					<table class="table" id="statistics-details">
						<thead>
							<tr>
								<th>Statistics</th>
								<th>Total</th>
								<th>Online</th>
								<th>Dead/Unanchored</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>Total Number of Structures</td>
								<td style="vertical-align: middle">{!! count($structures) !!}</td>
								<td style="vertical-align: middle">{!! $structures->where('str_destroyed', 0)->count() !!}</td>
								<td style="vertical-align: middle">{!! $structures->where('str_destroyed', 1)->count() !!}</td>
							</tr>
							<tr>
								<td>Keepstar</td>
								<td style="vertical-align: middle">{!! $structures->where('str_type', "Keepstar")->count() !!}</td>
								<td style="vertical-align: middle">{!! $structures->where('str_type', "Keepstar")->where('str_destroyed', 0)->count() !!}</td>
								<td style="vertical-align: middle">{!! $structures->where('str_type', "Keepstar")->where('str_destroyed', 1)->count() !!}</td>
							</tr>
							<tr>
								<td>Sotiyo</td>
								<td style="vertical-align: middle">{!! $structures->where('str_type', "Sotiyo")->count() !!}</td>
								<td style="vertical-align: middle">{!! $structures->where('str_type', "Sotiyo")->where('str_destroyed', 0)->count() !!}</td>
								<td style="vertical-align: middle">{!! $structures->where('str_type', "Sotiyo")->where('str_destroyed', 1)->count() !!}</td>
							</tr>
							<tr>
								<td>Fortizar</td>
								<td style="vertical-align: middle">{!! $structures->where('str_type', 'Fortizar')->count() !!}</td>
								<td style="vertical-align: middle">{!! $structures->where('str_type', 'Fortizar')->where('str_destroyed', 0)->count() !!}</td>
								<td style="vertical-align: middle">{!! $structures->where('str_type', 'Fortizar')->where('str_destroyed', 1)->count() !!}</td>
							</tr>
							<tr>
								<td>Azbel</td>
								<td style="vertical-align: middle">{!! $structures->where('str_type', "Azbel")->count() !!}</td>
								<td style="vertical-align: middle">{!! $structures->where('str_type', "Azbel")->where('str_destroyed', 0)->count() !!}</td>
								<td style="vertical-align: middle">{!! $structures->where('str_type', "Azbel")->where('str_destroyed', 1)->count() !!}</td>
							</tr>
							<tr>
								<td>Tatara</td>
								<td style="vertical-align: middle">{!! $structures->where('str_type', "Tatara")->count() !!}</td>
								<td style="vertical-align: middle">{!! $structures->where('str_type', "Tatara")->where('str_destroyed', 0)->count() !!}</td>
								<td style="vertical-align: middle">{!! $structures->where('str_type', "Tatara")->where('str_destroyed', 1)->count() !!}</td>
							</tr>
							<tr>
								<td>Astrahus</td>
								<td style="vertical-align: middle">{!! $structures->where('str_type', "Astrahus")->count() !!}</td>
								<td style="vertical-align: middle">{!! $structures->where('str_type', "Astrahus")->where('str_destroyed', 0)->count() !!}</td>
								<td style="vertical-align: middle">{!! $structures->where('str_type', "Astrahus")->where('str_destroyed', 1)->count() !!}</td>
							</tr>
							<tr>
								<td>Athanor</td>
								<td style="vertical-align: middle">{!! $structures->where('str_type', "Athanor")->count() !!}</td>
								<td style="vertical-align: middle">{!! $structures->where('str_type', "Athanor")->where('str_destroyed', 0)->count() !!}</td>
								<td style="vertical-align: middle">{!! $structures->where('str_type', "Athanor")->where('str_destroyed', 1)->count() !!}</td>
							</tr>
							<tr>
								<td>Raitaru</td>
								<td style="vertical-align: middle">{!! $structures->where('str_type', "Raitaru")->count() !!}</td>
								<td style="vertical-align: middle">{!! $structures->where('str_type', "Raitaru")->where('str_destroyed', 0)->count() !!}</td>
								<td style="vertical-align: middle">{!! $structures->where('str_type', "Raitaru")->where('str_destroyed', 1)->count() !!}</td>
							</tr>
							<tr>
								<td>Ansiblex Jump Gate</td>
								<td style="vertical-align: middle">{!! $structures->where('str_type', "Ansiblex Jump Gate")->count() !!}</td>
								<td style="vertical-align: middle">{!! $structures->where('str_type', "Ansiblex Jump Gate")->where('str_destroyed', 0)->count() !!}</td>
								<td style="vertical-align: middle">{!! $structures->where('str_type', "Ansiblex Jump Gate")->where('str_destroyed', 1)->count() !!}</td>
							</tr>
							<tr>
								<td>Pharolux Cyno Beacon</td>
								<td style="vertical-align: middle">{!! $structures->where('str_type', "Pharolux Cyno Beacon")->count() !!}</td>
								<td style="vertical-align: middle">{!! $structures->where('str_type', "Pharolux Cyno Beacon")->where('str_destroyed', 0)->count() !!}</td>
								<td style="vertical-align: middle">{!! $structures->where('str_type', "Pharolux Cyno Beacon")->where('str_destroyed', 1)->count() !!}</td>
							</tr>
							<tr>
								<td>Tenebrex Cyno Jammer</td>
								<td style="vertical-align: middle">{!! $structures->where('str_type', "Tenebrex Cyno Jammer")->count() !!}</td>
								<td style="vertical-align: middle">{!! $structures->where('str_type', "Tenebrex Cyno Jammer")->where('str_destroyed', 0)->count() !!}</td>
								<td style="vertical-align: middle">{!! $structures->where('str_type', "Tenebrex Cyno Jammer")->where('str_destroyed', 1)->count() !!}</td>
							</tr>


						</tbody>
					</table>
				</div>
			</div>
		</div>				


		<div class="col-md-9">
			<div class="panel panel-default upwell-chart">

				<div class="panel-heading">
					Online Structures in Database by Type
				</div>
				<div class="panel-body chart">

					<div>
						<canvas id="myChart" height="395"></canvas>
					</div>

				</div>
			</div>
		</div>
	</div>	
</div>
@stop


@section('styles')
<style>
	.upwell-chart .chart {
		zoom: 1.22;
	}
</style>
{!! HTML::style('assets/css/bootstrap-datetimepicker.min.css') !!}
{!! HTML::style('assets/plugins/croppie/croppie.css') !!}
@stop

@section('scripts')
<script>
	var labels = {!! json_encode(array_keys($online_structures)) !!};
	var online = {!! json_encode(array_values($online_structures)) !!};
</script>
{!! HTML::script('assets/plugins/croppie/croppie.js') !!}
{!! HTML::script('assets/js/moment.min.js') !!}
{!! HTML::script('assets/js/bootstrap-datetimepicker.min.js') !!}
{!! HTML::script('assets/js/as/btn.js') !!}
{!! HTML::script('assets/js/chart.min.js') !!}
{!! HTML::script('assets/js/as/upwell_statistics.js') !!}


<script type="text/javascript">
	$(function () {
		$('#date-from').datetimepicker({
			format: 'DD-MM-YYYY'
		});
		$('#date-to').datetimepicker({
			format: 'DD-MM-YYYY',
            useCurrent: true //Important! See issue #1075
        });
		$("#date-from").on("dp.change", function (e) {
			$('#date-to').data("DateTimePicker").minDate(e.date);
		});
		$("#date-to").on("dp.change", function (e) {
			$('#date-from').data("DateTimePicker").maxDate(e.date);
		});
	});
</script>

<script>

	$("#search").change(function () {
		$("#statistics-form").submit();
	});

	$("#type").change(function () {
		$("#statistics-form").submit();
	});

	$("#state").change(function () {
		$("#statistics-form").submit();
	});

	$("#status").change(function () {
		$("#statistics-form").submit();
	});

	$("#size").change(function () {
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

