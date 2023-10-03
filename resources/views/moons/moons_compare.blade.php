@extends('layouts.app')

@section('page-title', 'Moons | moons')

@section('content')

<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
			2020 New Eden Moons Comparison.
			<small> - compares between the 2017 and 2020 data. CCP fucked it.. yes... ; will only show moons that has 2017 & 2020 data.</small>
			<div class="pull-right">
				<ol class="breadcrumb">
					<li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
					<li class="active">Moon Comparison</li>
				</ol>
			</div>

		</h1>
	</div>
</div>

@include('partials.messages')

<div class="row tab-search">
	<div class="col-md-12"></div>
	<form method="GET" action="" accept-charset="UTF-8" id="moons-form">
		<div class="col-md-2">
			Search Everything
			<div class="input-group custom-search-form">
				<input type="text" class="form-control" name="search" value="{{ Input::get('search') }}" placeholder="Search Everything">
				<span class="input-group-btn">
					<button class="btn btn-default" type="submit" id="search-moons-btn">
						<span class="glyphicon glyphicon-search"></span>
					</button>
					@if (
						Input::has('search') && Input::get('search') != '')
						<a href="{{ route('moons.moons_compare') }}" class="btn btn-danger" type="button" >
							<span class="glyphicon glyphicon-remove"></span>
						</a>
						@endif
					</span>
				</div>
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
						<a href="{{ route('moons.moons_compare') }}" class="btn btn-danger" type="button" >
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
						<a href="{{ route('moons.moons_compare') }}" class="btn btn-danger" type="button" >
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
						<a href="{{ route('moons.moons_compare') }}" class="btn btn-danger" type="button" >
							<span class="glyphicon glyphicon-remove"></span>
						</a>
						@endif
					</span>
				</div>
			</div>

			<div class="col-md-3">
			</div>

		</div>
	</form>
</div>



<div class="col-md-12">
	{!! $moons->appends(\Request::except('moons'))->render() !!}
	<div class="panel panel-default">
		<div class="panel-heading">Moons</div>
		<div class="panel-body">

			
			<div class="table-responsive top-border-table" id="location-table-wrapper">

				<table class="table" id="moons">
					<thead>
						<th> @sortablelink('moon_name', 'Moon')</th>
						<th> Moon Breakdowns</th>
						<th> @sortablelink('moon_system_name', 'System')</th>
						<th> @sortablelink('moon_constellation_name', 'Constellation')</th>
						<th> @sortablelink('moon_region_name', 'Region')</th>
						<th> @sortablelink('moon_old_r_rating', 'Old Rarity')</th>
						<th> @sortablelink('moon_new_r_rating', 'New Rarity')</th>
						<th> @sortablelink('moon_old_value_56_day', 'Old Value (56 Days)')</th>
						<th> @sortablelink('moon_new_value_56_day', 'New Value (56 Days)')</th>
						<th> @sortablelink('moon_percentage_difference', 'Percentage Difference')</th>
					</thead>

					<tbody>

						@if (isset($moons))              
						@foreach($moons as $moon)

						<tr>						
							<td style="vertical-align: middle">{{ $moon->moon_name }}</td>
							<td style="vertical-align: middle">
								<a href="{{ route('moons.view_old_moon', $moon->moon_id)}}" target="_blank">2017</a> ::: 
								<a href="{{ route('moons.view_moon', $moon->moon_id)}}" target="_blank">2020</a>
							</td>

							<td style="vertical-align: middle">{!! $moon->moon_system_name !!}</a></td>
							<td style="vertical-align: middle">{!! $moon->moon_constellation_name !!}</a></td>
							<td style="vertical-align: middle">{!! $moon->moon_region_name !!}</a></td>
							@if($moon->moon_old_r_rating < 4)
							<td></td>
							@else
							<td style="vertical-align: middle">R{!! $moon->moon_old_r_rating !!}</a></td>
							@endif

							@if($moon->moon_new_r_rating < 4)
							<td></td>
							@else
							<td style="vertical-align: middle">R{!! $moon->moon_new_r_rating !!}</a></td>
							@endif

							<td style="vertical-align: middle">{!! number_format($moon->moon_old_value_56_day,2) !!}</a></td>
							<td style="vertical-align: middle">{!! number_format($moon->moon_new_value_56_day,2) !!}</a></td>
							@if($moon->moon_old_value_56_day > 0)
							<td style="vertical-align: middle">{!! number_format($moon->moon_percentage_difference,2) !!} %</a></td>
							@else
							<td> {!! number_format(0,2) !!} %</td>
							@endif
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

@stop

@section('scripts')


<script>

	$("#search").change(function () {
		$("#moons-form").submit();
	});

	$("#system").change(function () {
		$("#moons-form").submit();
	});

	$("#constellation").change(function () {
		$("#moons-form").submit();
	});

	$("#region").change(function () {
		$("#moons-form").submit();
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


