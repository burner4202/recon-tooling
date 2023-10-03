@extends('layouts.app')

@section('page-title', 'System Indices')

@section('content')

<div class="row">
	<div class="col-md-12">
		<h1 class="page-header">
			System Indices
			<small> - EVE system indices, sortable, databased & trended.</small>
			<div class="pull-right">
				<ol class="breadcrumb">
					<li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
					<li class="active">System Indices</li>
				</ol>
			</div>

		</h1>
	</div>
</div>


<div class="col-md-12">

	<div class="col-md-3 ">
		{!! $indices->appends(\Request::except('indices'))->render() !!}
	</div>

	<div class="col-md-3 pull-right">
		<form method="GET" action="" accept-charset="UTF-8" id="indices-form">
			Search Everything
			<div class="input-group custom-search-form">
				<input type="text" class="form-control" name="search" value="{{ Input::get('search') }}" placeholder="Search Everything">
				<span class="input-group-btn">
					<button class="btn btn-default" type="submit" id="search-indices-btn">
						<span class="glyphicon glyphicon-search"></span>
					</button>
					@if (
						Input::has('search') && Input::get('search') != '' ||
						Input::has('security_status') && Input::get('security_status') != '')					
						<a href="{{ route('indices.index') }}" class="btn btn-danger" system="button" >
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

			<div class="col-md-2 pull-right">
				Security Status
				{!! Form::select('security_status', $security_status, Input::get('security_status'), ['id' => 'security_status', 'class' => 'form-control']) !!}
			</div>
		</form>
	</div>
</div>
<p></p>




<div class="row col-md-12">
	<div class="panel panel-default">
		<div class="panel-heading">Daily System Indices - Click on Solar System for metrics.</div>
		<div class="panel-body">

			<div class="table-responsive top-border-table" id="indices-table-wrapper">

				<table class="table" id="indices">
					<thead>
						<th>@sortablelink('sci_solar_system_name', 'System Name')</th>
						<th>@sortablelink('sci_security_status', 'Security Status')</th>
						<th>@sortablelink('sci_solar_constellation_name', 'Constellation Name')</th>
						<th>@sortablelink('sci_solar_region_name', 'Region')</th>
						<th>@sortablelink('sci_manufacturing', 'Manufacturing')</th>	
						<th>@sortablelink('sci_manufacturing_delta', 'Delta')</th>	
						<th>@sortablelink('sci_researching_time_efficiency', 'Research Time Efficiency')</th>	
						<th>@sortablelink('sci_researching_time_efficiency_delta', 'Delta')</th>	
						<th>@sortablelink('sci_researching_material_efficiency', 'Research Material Efficiency')</th>	
						<th>@sortablelink('sci_researching_material_efficiency_delta', 'Delta')</th>	
						<th>@sortablelink('sci_copying', 'Copying')</th>	
						<th>@sortablelink('sci_copying_delta', 'Delta')</th>	
						<th>@sortablelink('sci_invention', 'Invention')</th>	
						<th>@sortablelink('sci_invention_delta', 'Delta')</th>	
						<th>@sortablelink('sci_reaction', 'Reactions')</th>	
						<th>@sortablelink('sci_reaction_delta', 'Delta')</th>
						<th>@sortablelink('sci_date', 'Last Updated')</th>	
					</thead>
					<tbody>

						@if (isset($indices))              
						@foreach($indices as $system)

						<tr>
							<td style="vertical-align: middle"><a href="{{ route('solar.system', $system->sci_solar_system_id) }}">{{ $system->sci_solar_system_name }}</a></td>
							<td style="vertical-align: middle">{{ $system->sci_security_status }}</td>
							<td style="vertical-align: middle"><a href="{{ route('solar.constellation', $system->sci_solar_constellation_id) }}">{{ $system->sci_solar_constellation_name }}</a></td>	
							<td style="vertical-align: middle"><a href="{{ route('solar.region', $system->sci_solar_region_id) }}">{{ $system->sci_solar_region_name }}</a></td>
							<td style="vertical-align: middle">{{ $system->sci_manufacturing * 100}}%</td>
							<td style="vertical-align: middle">{{ $system->sci_manufacturing_delta }}%</td>
							<td style="vertical-align: middle">{{ $system->sci_researching_time_efficiency * 100}}%</td>
							<td style="vertical-align: middle">{{ $system->sci_researching_time_efficiency_delta }}%</td>
							<td style="vertical-align: middle">{{ $system->sci_researching_material_efficiency * 100}}%</td>
							<td style="vertical-align: middle">{{ $system->sci_researching_material_efficiency_delta }}%</td>
							<td style="vertical-align: middle">{{ $system->sci_copying * 100}}%</td>
							<td style="vertical-align: middle">{{ $system->sci_copying_delta }}%</td>
							<td style="vertical-align: middle">{{ $system->sci_invention * 100}}%</td>
							<td style="vertical-align: middle">{{ $system->sci_invention_delta }}%</td>
							<td style="vertical-align: middle">{{ $system->sci_reaction * 100}}%</td>
							<td style="vertical-align: middle">{{ $system->sci_reaction_delta }}%</td>
							<td style="vertical-align: middle">{{ $system->sci_date }}</td>
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
		$("#indices-form").submit();
	});

	$("#security_status").change(function () {
		$("#indices-form").submit();
	});

	$("#no_per_page").change(function () {
		$("#indices-form").submit();
	});

</script>


@stop

