@extends('layouts.app')

@section('page-title', 'Known Structures')

@section('content')

<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
			Abandoned Structures
			<small> - check this daily, purged automatically, anything that is left, has most than likely gone high powered again.</small>
			<div class="pull-right">
				<ol class="breadcrumb">
					<li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
					<li><a href="#">Abandoned Structures</a></li>
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
						Input::has('vulnerable') && Input::get('vulnerable') != '')
						<a href="{{ route('structures.abandoned') }}" class="btn btn-danger" type="button" >
							<span class="glyphicon glyphicon-remove"></span>
						</a>
						@endif
					</span>
				</div>
			</div>



			<div class="col-md-1">
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
		</form>


		
	</div>

</div>


<div class="row col-md-12">
	<div class="col-md-3">{!! $structures->appends(\Request::except('structures'))->render() !!}</div>
</div>

@permission('export.structures.to.excel')
<div class="row col-md-12">
	<div class="col-md-2">	
		<a href="{{ route('structures.export_to_excel')}}" class="btn btn-success" id="export-excel">
			Export to Excel
		</a>
		<p></p>
	</div>
</div>
@endpermission




<div class="col-md-12">
	<div class="panel panel-default">
		<div class="panel-heading">Known Structures</div>
		<div class="panel-body">
			<div class="table-responsive top-border-table" id="structures-table-wrapper">

				<table class="table" id="structures">
					<thead>
						<th> @sortablelink('str_name', 'Structure Name')</th>
						<th> @sortablelink('str_type', 'Type')</th>
						<th> Fitting Summary </th>
						<th> @sortablelink('str_state', 'State')</th>
						<th> @sortablelink('str_abandoned_time', 'Abandoned')</th>
						<th> @sortablelink('str_system', 'System')</th>
						<th> @sortablelink('str_constellation_name', 'Constellation')</th>
						<th> @sortablelink('str_region_name', 'Region')</th>
						<th> @sortablelink('str_owner_corporation_name', 'Corporation Owner')</th>
						<th> @sortablelink('str_owner_alliance_name', 'Alliance')</th>
						<th> @sortablelink('str_value', 'Fitting Value',)</th>
						<th> @sortablelink('updated_at', 'Last Updated')</th>
						@permission('set.waypoint')
						<th> Set Waypoint </th>
						@endpermission


					</thead>
					<tbody>

						@if (isset($structures))              
						@foreach($structures as $structure)

						<tr>

							<td style="vertical-align: middle">
								@permission('structure.hitlist')
								@if($structure->str_hitlist == 1)

								<i class="glyphicon glyphicon-flag"></i>

								@endif
								@endpermission

								<a href="{{  route('structures.view', $structure->str_structure_id_md5 )}}">{{ $structure->str_name }}
								</a>


							</td>
							<td style="vertical-align: middle"><img class="img-circle" src="https://images.evetech.net/types/{{ $structure->str_type_id }}/render?size=32">&nbsp;{{ $structure->str_type }}</td>

							<td style="vertical-align: middle; max-width:180px;">

								@if($structure->str_dooms_day)
								<span class="label label-danger">Dooms Day</span>
								@endif
								@if($structure->str_point_defense)
								<span class="label label-danger">Point Defense</span>
								@endif
								@if($structure->str_anti_cap)
								<span class="label label-danger">Anti Cap Fit</span>
								@endif
								@if($structure->str_anti_subcap)
								<span class="label label-danger">Anti Subcap Fit</span>
								@endif
								@if($structure->str_guide_bombs)
								<span class="label label-warning">Guided Bombs</span>
								@endif


								@if($structure->str_market)
								<span class="label label-primary">Market Hub</span>
								@endif
								@if($structure->str_cloning)
								<span class="label label-primary">Clone Bay</span>
								@endif
								@if($structure->str_capital_shipyard)
								<span class="label label-warning">Capital Production</span>
								@endif
								@if($structure->str_supercapital_shipyard)
								<span class="label label-danger">Titan Production</span>
								@endif
								@if($structure->str_hyasyoda)
								<span class="label label-primary">Hyasyoda</span>
								@endif
								@if($structure->str_invention)
								<span class="label label-primary">Invention</span>
								@endif
								@if($structure->str_manufacturing)
								<span class="label label-primary">Manufacturing</span>
								@endif
								@if($structure->str_research)
								<span class="label label-primary">Researching</span>
								@endif
								@if($structure->str_biochemical)
								<span class="label label-primary">Booster Production</span>
								@endif
								@if($structure->str_composite)
								<span class="label label-primary">Moon Reactions</span>
								@endif
								@if($structure->str_hybrid)
								<span class="label label-primary">Tech 3 Production</span>
								@endif
								@if($structure->str_moon_drilling)
								<span class="label label-primary">Moon Drilling</span>
								@endif
								@if($structure->str_reprocessing)
								<span class="label label-primary">Reprocessing</span>
								@endif
								@if($structure->str_t2_rigged)
								<span class="label label-success">T2 Rigged</span>
								@endif

							</td>
							@if ($structure->str_state === "High Power")
							<td style="vertical-align: middle"><span class="label label-success">High Power</span></td>
							@elseif ($structure->str_state === "Low Power")
							<td style="vertical-align: middle"><span class="label label-danger">Low Power</span></td>
							@elseif ($structure->str_state === "Abandoned")
							<td style="vertical-align: middle"><span class="label label-warning">Abandoned</span></td>
							@elseif ($structure->str_state === "Anchoring")
							<td style="vertical-align: middle"><span class="label label-warning">Anchoring</span></td>
							@elseif ($structure->str_state === "Unanchoring")
							<td style="vertical-align: middle"><span class="label label-primary">Unanchoring</span></td>
							@elseif ($structure->str_state === "Reinforced")
							<td style="vertical-align: middle"><span class="label label-info">Reinforced</span></td>
							@else
							<td style="vertical-align: middle">-</td>
							@endif
							
							<td style="vertical-align: middle">{{ $structure->str_abandoned_time }} : {{ \Carbon\Carbon::parse($structure->str_abandoned_time)->diffForHumans() }}</td>
						
							<td style="vertical-align: middle"><a href="{{  route('solar.system', $structure->str_system_id) }}">{{ $structure->str_system }}</a></td>
							<td style="vertical-align: middle"><a href="{{  route('solar.constellation', $structure->str_constellation_id) }}">{{ $structure->str_constellation_name }}</a></td>
							<td style="vertical-align: middle"><a href="{{  route('solar.region', $structure->str_region_id) }}">{{ $structure->str_region_name }}</a></td>
							@if($structure->str_owner_corporation_id > 1)
							<td style="vertical-align: middle"><a href="{{ route('corporation.view', $structure->str_owner_corporation_id )}}"><img class="img-circle" src="https://images.evetech.net/corporations/{{ $structure->str_owner_corporation_id }}/logo?size=32">&nbsp;{{ $structure->str_owner_corporation_name }}</a></td>
							@else
							<td></td>
							@endif
							@if($structure->str_owner_alliance_id > 1)
							<td style="vertical-align: middle"><a href="{{ route('alliance.view', $structure->str_owner_alliance_id )}}"><img class="img-circle" src="https://images.evetech.net/alliances/{{ $structure->str_owner_alliance_id }}/logo?size=32">&nbsp;{{ $structure->str_owner_alliance_name }} ({{ $structure->str_owner_alliance_ticker }})</a></td>
							@else
							<td></td>
							@endif

							<td style="vertical-align: middle">{{ number_format($structure->str_value,2) }}</td>

							<td style="vertical-align: middle">{{ $structure->updated_at->diffForHumans() }}</td>

							@permission('set.waypoint')
							<td class="text-center" style="vertical-align: middle">
								<a href="{{ route('structures.setwaypoint', $structure->str_structure_id)}}" class="btn btn-success btn-circle edit" title="Set Waypoint" data-toggle="tooltip" data-placement="top">
									<i class="glyphicon glyphicon-play"></a></i>
								</a>
							</td>
							@endpermission

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

	$("#vulnerable").change(function () {
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



@stop