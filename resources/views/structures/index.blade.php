@extends('layouts.app')

@section('page-title', 'Known Structures')

@section('content')

<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
			Known Structures
			<small> - Scanned Structures of new Eden</small>
			<div class="pull-right">
				<ol class="breadcrumb">
					<li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
					<li><a href="#">Known Structures</a></li>
				</ol>
			</div>

		</h1>
	</div>
</div>

@include('partials.messages')

<div class="row tab-search">
	<div class="col-md-12"></div>
	<form method="GET" action="" accept-charset="UTF-8" id="structures-form">
		<div class="col-md-2">
			Search Everything
			<div class="input-group custom-search-form">
				<input type="text" class="form-control" name="search" value="{{ Input::get('search') }}" placeholder="Search Everything">
				<span class="input-group-btn">
					<button class="btn btn-default" type="submit" id="search-structures-btn">
						<span class="glyphicon glyphicon-search"></span>
					</button>
					@if (
						Input::has('search') && Input::get('search') != '' || 
						Input::has('state') && Input::get('state') != '' ||
						Input::has('type') && Input::get('type') != '' ||
						Input::has('status') && Input::get('status') != '' ||
						Input::has('corporation') && Input::get('corporation') != '')
						<a href="{{ route('structures.index') }}" class="btn btn-danger" type="button" >
							<span class="glyphicon glyphicon-remove"></span>
						</a>
						@endif
					</span>
				</div>
			</div>



			<div class="col-md-2">
				Type
				{!! Form::select('type', $type, Input::get('type'), ['id' => 'type', 'class' => 'form-control']) !!}
			</div>

			<div class="col-md-1">
				State
				{!! Form::select('state', $state, Input::get('state'), ['id' => 'state', 'class' => 'form-control']) !!}
			</div>


			<div class="col-md-1">
				Status
				{!! Form::select('status', $status, Input::get('status'), ['id' => 'status', 'class' => 'form-control']) !!}
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
						<a href="{{ route('structures.index') }}" class="btn btn-danger" type="button" >
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
						<a href="{{ route('structures.index') }}" class="btn btn-danger" type="button" >
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
						<a href="{{ route('structures.index') }}" class="btn btn-danger" type="button" >
							<span class="glyphicon glyphicon-remove"></span>
						</a>
						@endif
					</span>
				</div>
			</div>
			<div class="col-md-1">
				Per Page
				{!! Form::select('no_per_page', $no_per_page, Input::get('no_per_page'), ['id' => 'no_per_page', 'class' => 'form-control']) !!}
			</div>

			<div class="col-md-1">
				Age (Updated)
				{!! Form::select('how_old', $how_old, Input::get('how_old'), ['id' => 'how_old', 'class' => 'form-control']) !!}
			</div>
			<div class="col-md-1">
				Standings
				{!! Form::select('standings', $standings, Input::get('standings'), ['id' => 'standings', 'class' => 'form-control']) !!}
			</div>

			<div class="col-md-1">
				Moon Rarity
				{!! Form::select('rarity', $rarity, Input::get('rarity'), ['id' => 'rarity', 'class' => 'form-control']) !!}
			</div>
			<div class="col-md-1">
				Cored
				{!! Form::select('cored', $cored, Input::get('cored'), ['id' => 'cored', 'class' => 'form-control']) !!}
			</div>
			@permission('deliver.package')
			<div class="col-md-1">
				Package Delivery
				{!! Form::select('package', $package, Input::get('package'), ['id' => 'package', 'class' => 'form-control']) !!}
			</div>
			@endpermission

			<div class="col-md-1">
				Size
				{!! Form::select('size', $size, Input::get('size'), ['id' => 'size', 'class' => 'form-control']) !!}
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
						<a href="{{ route('structures.index') }}" class="btn btn-danger" type="button" >
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
						<a href="{{ route('structures.index') }}" class="btn btn-danger" type="button" >
							<span class="glyphicon glyphicon-remove"></span>
						</a>
						@endif
					</span>
				</div>
			</div>


			<div class="col-md-12">	
				<div class="panel">			
					<div class="panel-body">
						<div class="col-md-12">
							<div class="col-md-1">
								<div class="checkbox">
									{!! Form::checkbox('t2rigged', Input::get('t2rigged'), Input::get('t2rigged'), ['id' => 't2rigged', 'class' => 'form-control']) !!}
									<label class="no-content">T2 Rigged</label>
								</div>

							</div>
							<div class="col-md-1">
								<div class="checkbox">
									{!! Form::checkbox('moon_reactions', Input::get('moon_reactions'), Input::get('moon_reactions'), ['id' => 'moon_reactions', 'class' => 'form-control']) !!}
									<label class="no-content">Moon Reactions</label>
								</div>
							</div>
							<div class="col-md-1">
								<div class="checkbox">
									{!! Form::checkbox('reprocesing', Input::get('reprocesing'), Input::get('reprocesing'), ['id' => 'reprocesing', 'class' => 'form-control']) !!}
									<label class="no-content">Ore Reprocessing</label>
									
								</div>
							</div>
							<div class="col-md-1">
								<div class="checkbox">
									{!! Form::checkbox('moon_drilling', Input::get('moon_drilling'), Input::get('moon_drilling'), ['id' => 'moon_drilling', 'class' => 'form-control']) !!}
									<label class="no-content">Moon Drilling</label>
								</div>
							</div>
							<div class="col-md-1">
								<div class="checkbox">
									{!! Form::checkbox('hybrid', Input::get('hybrid'), Input::get('hybrid'), ['id' => 'hybrid', 'class' => 'form-control']) !!}
									<label class="no-content">T3 Production</label>
								</div>
							</div>
							<div class="col-md-1">
								<div class="checkbox">
									{!! Form::checkbox('invention', Input::get('invention'), Input::get('invention'), ['id' => 'invention', 'class' => 'form-control']) !!}
									<label class="no-content">Invention</label>
								</div>
							</div>
							<div class="col-md-1">
								<div class="checkbox">
									{!! Form::checkbox('researching', Input::get('researching'), Input::get('researching'), ['id' => 'researching', 'class' => 'form-control']) !!}
									<label class="no-content">Researching</label>

								</div>
							</div>
							<div class="col-md-1">
								<div class="checkbox">
									{!! Form::checkbox('hyasyoda', Input::get('hyasyoda'), Input::get('hyasyoda'), ['id' => 'hyasyoda', 'class' => 'form-control']) !!}
									<label class="no-content">Hyasyoda</label>

								</div>
							</div>
							<div class="col-md-1">
								<div class="checkbox">
									{!! Form::checkbox('market', Input::get('market'), Input::get('market'), ['id' => 'market', 'class' => 'form-control']) !!}
									<label class="no-content">Market Hub</label>

								</div>
							</div>
							<div class="col-md-1">
								<div class="checkbox">
									{!! Form::checkbox('cloning', Input::get('cloning'), Input::get('cloning'), ['id' => 'cloning', 'class' => 'form-control']) !!}
									<label class="no-content">Clone Bay</label>

								</div>
							</div>
							<div class="col-md-1">
								<div class="checkbox">
									{!! Form::checkbox('titan_production', Input::get('titan_production'), Input::get('titan_production'), ['id' => 'titan_production', 'class' => 'form-control']) !!}
									<label class="no-content">Titan Production</label>
									
								</div>
							</div>
							<div class="col-md-1">
								<div class="checkbox">
									{!! Form::checkbox('cap_production', Input::get('cap_production'), Input::get('cap_production'), ['id' => 'cap_production', 'class' => 'form-control']) !!}
									<label class="no-content">Cap Production</label>
									
								</div>
							</div>

							<div class="col-md-1">
								<div class="checkbox">
									{!! Form::checkbox('dooms_day', Input::get('dooms_day'), Input::get('dooms_day'), ['id' => 'dooms_day', 'class' => 'form-control']) !!}
									<label class="no-content">Dooms Day</label>
									
								</div>
							</div>
							<div class="col-md-1">
								<div class="checkbox">
									{!! Form::checkbox('point_defense', Input::get('point_defense'), Input::get('point_defense'), ['id' => 'point_defense', 'class' => 'form-control']) !!}
									<label class="no-content">Point Defense</label>
									
								</div>
							</div>
							<div class="col-md-1">
								<div class="checkbox">
									{!! Form::checkbox('guide_bombs', Input::get('guide_bombs'), Input::get('guide_bombs'), ['id' => 'guide_bombs', 'class' => 'form-control']) !!}
									<label class="no-content">Guided Bombs</label>
									
								</div>
							</div>
							<div class="col-md-1">
								<div class="checkbox">
									{!! Form::checkbox('anti_cap', Input::get('anti_cap'), Input::get('anti_cap'), ['id' => 'anti_cap', 'class' => 'form-control']) !!}
									<label class="no-content">Anti Cap Fit</label>
									
								</div>
							</div>
							<div class="col-md-1">
								<div class="checkbox">
									{!! Form::checkbox('anti_sub_cap', Input::get('anti_sub_cap'), Input::get('anti_sub_cap'), ['id' => 'anti_sub_cap', 'class' => 'form-control']) !!}
									<label class="no-content">Anti Subcap Fit</label>
									
								</div>
							</div>
							@permission('structure.hitlist')
							<div class="col-md-1">
								<div class="checkbox">
									{!! Form::checkbox('on_hitlist', Input::get('on_hitlist'), Input::get('on_hitlist'), ['id' => 'on_hitlist', 'class' => 'form-control']) !!}
									<label class="no-content">On Hitlist</label>
									
								</div>
							</div>
							@endpermission
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>




<div class="row col-md-12">
	<div class="col-md-3">{!! $structures->appends(\Request::except('structures'))->render() !!}</div>
</div>
<div class="row col-md-12">
	@permission('export.structures.to.excel')
	<div class="col-md-1">	
		<a href="{{ route('structures.export_to_excel')}}" class="btn btn-success" id="export-excel">
			Export to Excel
		</a>
		<p></p>
	</div>
	@endpermission

	@permission('structure.hitlist')
	<div class="col-md-1">	
		<a href="{{ route('structures.hitlist_export')}}" class="btn btn-success" id="export-hitlist">
			Export Hitlist
		</a>
		<p></p>
	</div>
	@endpermission

	@php ($dotlan_systems = [])
	@php ($dotlan_regions = [])
	@foreach($structures as $structure)
	@php ($dotlan_systems[] = $structure->str_system)
	@php ($dotlan_regions[] = $structure->str_region_name)
	@endforeach
	<div class="col-md-1">	
		<a href="http://evemaps.dotlan.net/map/{{ str_replace(" ", "_", implode('', array_unique($dotlan_regions))) }}/{{ implode(',', array_unique($dotlan_systems)) }}#adm" target="_blank" class="btn btn-primary" id="dotlan-link"  data-toggle="tooltip" title="Exports Selected Systems to a Single Region Dotlan Map. Pick Region/Filter Per Page." data-placement="top">
			DOTLAN ADM.
		</a>

		<p></p>
	</div>
	<div class="col-md-1">	
		<a href="http://evemaps.dotlan.net/map/{{ str_replace(" ", "_", implode('', array_unique($dotlan_regions))) }}/{{ implode(',', array_unique($dotlan_systems)) }}#sov" target="_blank" class="btn btn-primary" id="dotlan-link"  data-toggle="tooltip" title="Exports Selected Systems to a Single Region Dotlan Map. Pick Region/Filter Per Page." data-placement="top">
			DOTLAN SOV.
		</a>

		<p></p>
	</div>
</div>



<div class="col-md-12">
	<div class="panel panel-default">
		<div class="panel-heading">Known Structures
			<div class="pull-right" style="vertical-align:middle;">
				<span class="glyphicon glyphicon-info-sign fa-1x" data-toggle="tooltip" title="All Structures, Select Filter & Search. For Dotlan Export, Single Region Must Be Selected." data-placement="left"></span>
			</div>
		</div>
		<div class="panel-body">
			<div class="table-responsive top-border-table" id="srp-table-wrapper">

				@include('structures.table', $structures)
			</div>
		</div>
	</div>
</div>



@stop


@section('scripts')


<script>

	$("#search").change(function () {
		$("#structures-form").submit();
	});

	$("#type").change(function () {
		$("#structures-form").submit();
	});

	$("#state").change(function () {
		$("#structures-form").submit();
	});

	$("#status").change(function () {
		$("#structures-form").submit();
	});

	$("#size").change(function () {
		$("#structures-form").submit();
	});

	$("#package").change(function () {
		$("#structures-form").submit();
	});

	$("#no_per_page").change(function () {
		$("#structures-form").submit();
	});

	$("#t2rigged").change(function () {
		$("#structures-form").submit();
	});

	$("#moon_reactions").change(function () {
		$("#structures-form").submit();
	});

	$("#reprocesing").change(function () {
		$("#structures-form").submit();
	});

	$("#moon_drilling").change(function () {
		$("#structures-form").submit();
	});

	$("#hybrid").change(function () {
		$("#structures-form").submit();
	});

	$("#invention").change(function () {
		$("#structures-form").submit();
	});

	$("#researching").change(function () {
		$("#structures-form").submit();
	});

	$("#hyasyoda").change(function () {
		$("#structures-form").submit();
	});

	$("#market").change(function () {
		$("#structures-form").submit();
	});

	$("#cloning").change(function () {
		$("#structures-form").submit();
	});

	$("#titan_production").change(function () {
		$("#structures-form").submit();
	});

	$("#cap_production").change(function () {
		$("#structures-form").submit();
	});

	$("#dooms_day").change(function () {
		$("#structures-form").submit();
	});

	$("#point_defense").change(function () {
		$("#structures-form").submit();
	});

	$("#guide_bombs").change(function () {
		$("#structures-form").submit();
	});

	$("#anti_cap").change(function () {
		$("#structures-form").submit();
	});

	$("#anti_sub_cap").change(function () {
		$("#structures-form").submit();
	});

	$("#on_hitlist").change(function () {
		$("#structures-form").submit();
	});

	$("#how_old").change(function () {
		$("#structures-form").submit();
	});

	$("#standings").change(function () {
		$("#structures-form").submit();
	});

	$("#cored").change(function () {
		$("#structures-form").submit();
	});

	$("#rarity").change(function () {
		$("#structures-form").submit();
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