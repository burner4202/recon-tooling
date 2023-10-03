@extends('layouts.app')

@section('page-title', 'Search Structures')

@section('content')

<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
			Structure Search
			<small> - find structure information</small>
			<div class="pull-right">
				<ol class="breadcrumb">
					<li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
					<li><a href="#">Structure Search</a></li>
				</ol>
			</div>

		</h1>
	</div>
</div>

@include('partials.messages')


<div class="row tab-search">
	<div class="col-md-12"></div>
	<form method="GET" action="" accept-charset="UTF-8" id="structures-form">
		<div class="col-md-3">
			Search Structure (Exact Name)
			<div class="input-group custom-search-form">
				<input type="text" class="form-control" name="search" value="{{ Input::get('search') }}" placeholder="Search Structure Name">
				<span class="input-group-btn">
					<button class="btn btn-default" type="submit" id="search-structures-btn">
						<span class="glyphicon glyphicon-search"></span>
					</button>
					@if (
						Input::has('search') && Input::get('search') != '')
						<a href="{{ route('structures.index') }}" class="btn btn-danger" type="button" >
							<span class="glyphicon glyphicon-remove"></span>
						</a>
						@endif
					</span>
				</div>
			</div>
		</div>
	</form>
</div>




<div class="row col-md-12">
	<div class="col-md-3">{!! $structures->appends(\Request::except('structures'))->render() !!}</div>
</div>

<div class="col-md-12">
	<div class="panel panel-default">
		<div class="panel-heading"><b>Structure Search</b><br>
			Search for a structure using the exact name to find out details, if the structure is found, please contact Recon for more information regarding the structure.
			<div class="pull-right" style="vertical-align:middle;">
				<span class="glyphicon glyphicon-info-sign fa-1x" data-toggle="tooltip" title="Structure Search" data-placement="left"></span>
			</div>
		</div>
		<div class="panel-body">
			<div class="table-responsive top-border-table" id="srp-table-wrapper">

				<table class="table" id="structures">
	<thead>
		<th> @sortablelink('str_name', 'Structure Name')</th>
		<th> @sortablelink('str_type', 'Type')</th>
		<th> Fitting Summary </th>
		<th> @sortablelink('str_state', 'State')</th>
		<th> @sortablelink('str_status', 'Status')</th>

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

			<td style="vertical-align: middle">{{ $structure->str_name }}</td>
			<td style="vertical-align: middle"><img class="img-circle" src="https://image.eveonline.com/Type/{{ $structure->str_type_id }}_32.png">&nbsp;{{ $structure->str_type }}</td>

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
			@elseif ($structure->str_state === "Anchoring")
			<td style="vertical-align: middle"><span class="label label-warning">Anchoring</span></td>
			@elseif ($structure->str_state === "Unanchoring")
			<td style="vertical-align: middle"><span class="label label-primary">Unanchoring</span></td>
			@elseif ($structure->str_state === "Reinforced")
			<td style="vertical-align: middle"><span class="label label-info">Reinforced</span></td>
			@else
			<td style="vertical-align: middle">State Not Set</td>
			@endif

			@if ($structure->str_status === "Unanchoring")
			<td style="vertical-align: middle"><span class="label label-primary">Unanchoring</span></td>
			@elseif ($structure->str_status === "Armor")
			<td style="vertical-align: middle"><span class="label label-warning">Reinforced Armor</span></td>
			@elseif ($structure->str_status === "Hull")
			<td style="vertical-align: middle"><span class="label label-danger">Reinforced Hull</span></td>
			@else
			<td style="vertical-align: middle">Status Not Set</td>
			@endif

			<td style="vertical-align: middle">{{ $structure->str_system }}</td>
			<td style="vertical-align: middle">{{ $structure->str_constellation_name }}</td>
			<td style="vertical-align: middle">{{ $structure->str_region_name }}</td>
			@if($structure->str_owner_corporation_id > 1)
			<td style="vertical-align: middle"><img class="img-circle" src="https://imageserver.eveonline.com/Corporation/{{ $structure->str_owner_corporation_id }}_32.png">&nbsp;{{ $structure->str_owner_corporation_name }}</td>
			@else
			<td></td>
			@endif
			@if($structure->str_owner_alliance_id > 1)
			<td style="vertical-align: middle"><img class="img-circle" src="https://imageserver.eveonline.com/Alliance/{{ $structure->str_owner_alliance_id }}_32.png">&nbsp;{{ $structure->str_owner_alliance_name }} ({{ $structure->str_owner_alliance_ticker }})</td>
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