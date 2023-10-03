@extends('layouts.app')

@section('page-title', 'Alliance Stagers')

@section('content')


<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
			Staging Systems
			<small>- list of stagers/homes of alliances</small>
			<div class="pull-right">
				<ol class="breadcrumb">
					<li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
					<li class="active">Intelligence Dashboard</li>
					<li class="active">Staging Systems</li>
				</ol>
			</div>
		</h1>
	</div>
</div>


@include('partials.messages')


<div class="row tab-search">
	<div class="col-md-5"></div>
</div>

<div class="row col-md-12">
	<div class="panel panel-default">
		<div class="panel-heading">
			Staging Systems
			<div class="pull-right">
			<a href="{{ route('stager.update_standings')}}">Update Standings</a>
		</div>
		</div>
			
		<div class="panel-body">
			<div class="col-md-3 pull-right">
				<form method="GET" action="" accept-charset="UTF-8" id="stager-form">
					Search Everything
					<div class="input-group custom-search-form">
						<input type="text" class="form-control" name="search" value="{{ Input::get('search') }}" placeholder="Search Everything">
						<span class="input-group-btn">
							<button class="btn btn-default" type="submit" id="search-indices-btn">
								<span class="glyphicon glyphicon-search"></span>
							</button>
							@if (
								Input::has('search') && Input::get('search') != '' ||
								Input::has('standings') && Input::get('standings') != '' ||
								Input::has('region') && Input::get('region') != '' ||
								Input::has('tag') && Input::get('tag') != '' ||
								Input::has('alliance') && Input::get('alliance') != '')					
								<a href="{{ route('stager.index') }}" class="btn btn-danger" system="button">
									<span class="glyphicon glyphicon-remove"></span>
								</a>
								@endif
							</span>
						</div>
					</div>

					<div class="col-md-3">
						{!! $stagers->appends(\Request::except('stagers'))->render() !!}
					</div>


					<div class="col-md-2">
						Alliance
						{!! Form::select('alliance', $alliance, Input::get('alliance'), ['id' => 'alliance', 'class' => 'form-control']) !!}
					</div>

					<div class="col-md-1">
						Standings
						{!! Form::select('standings', $standings, Input::get('standings'), ['id' => 'standings', 'class' => 'form-control']) !!}
					</div>

					<div class="col-md-1">
						Region
						{!! Form::select('region', $region, Input::get('region'), ['id' => 'region', 'class' => 'form-control']) !!}
					</div>

					<div class="col-md-2">
						Tag
						{!! Form::select('tag', $tag, Input::get('tag'), ['id' => 'tag', 'class' => 'form-control']) !!}
					</div>


				</form>
			</div>
		</div>
	</div>


	<div class="row col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">Stager Information</div>
			<div class="panel-body">
				<div class="col-md-12">

					<div class="table-responsive top-border-table" id="stager-information">

						<table class="table" id="stagers">
							<thead>
								<th> @sortablelink('alliance_name', 'Alliance Name')</th>
								<th> @sortablelink('standing', 'Standings')</th>
								<th> @sortablelink('solar_system_name', 'Solar System')</th>
								<th> @sortablelink('constellation_name', 'Constellation')</th>
								<th> @sortablelink('region_name', 'Region')</th>
								<th> @sortablelink('tag', 'Tag')</th>
								<th> External Links</th>
								@permission('stager.remove')
								<th> Action</th>
								@endpermission

							</thead>

							<tbody>


								@if (isset($stagers))              
								@foreach($stagers as $stager)

								<tr>	
									<td style="vertical-align: middle"><a href="{{ route('alliance.view', $stager->alliance_id )}}"><img class="img-circle" src="https://images.evetech.net/alliances/{{ $stager->alliance_id }}/logo?size=32">&nbsp;{{ $stager->alliance_name }} ({{ $stager->alliance_ticker }})</a></td>

									@if($stager->standing <= 10 && $stager->standing >= 5)
									<td style="vertical-align: middle"><span class="label label-primary">{{ $stager->standing }}</span></td>
									@elseif($stager->standing <= 5 && $stager->standing >= 0)
									<td style="vertical-align: middle"><span class="label label-info">{{ $stager->standing }}</span></td>
									@elseif($stager->standing <= 0 && $stager->standing >= -5)
									<td style="vertical-align: middle"><span class="label label-warning">{{ $stager->standing }}</span></td>
									@else
									<td style="vertical-align: middle"><span class="label label-danger">{{ $stager->standing }}</span></td>
									@endif


									<td style="vertical-align: middle"><a href="{{ route('solar.system', $stager->solar_system_id )}}">{{  $stager->solar_system_name }}</a></td>		
									<td style="vertical-align: middle"><a href="{{ route('solar.constellation', $stager->constellation_id )}}">{{  $stager->constellation_name }}</a></td>		
									<td style="vertical-align: middle"><a href="{{ route('solar.region', $stager->region_id )}}">{{  $stager->region_name }}</a></td>		
									<td style="vertical-align: middle">{{  $stager->tag }}</td>		
									<td style="vertical-align: middle">

										<a href="https://zkillboard.com/system/{{ $stager->solar_system_id }}" class="label label-danger" data-toggle="tooltip" target="_blank">
											<span >ZKillboard </span>
										</a>
								

										<a href="https://evemaps.dotlan.net/system/{{  $stager->solar_system_id }}" class="label label-warning" data-toggle="tooltip" target="_blank">
											<span >DOT Lan </span>
										</a>
									

										@if($stager->tag == "Staging (Blops)")

										<a href="https://evemaps.dotlan.net/range/Sin,5/{{  $stager->solar_system_name }}" class="label label-success" data-toggle="tooltip" target="_blank">
											<span >Bridge Range</span>
										</a>
										@elseif($stager->tag == "Staging (Capitals)")
										<a href="https://evemaps.dotlan.net/range/Moros,5/{{  $stager->solar_system_name }}" class="label label-success" data-toggle="tooltip" target="_blank">
											<span >Jump Range</span>
										</a>
										@else
										<a href="https://evemaps.dotlan.net/range/Avatar,5/{{  $stager->solar_system_name }}" class="label label-success" data-toggle="tooltip" target="_blank">
											<span >Bridge Range</span>
										</a>
										@endif





									</td>

									@permission('stager.remove')
									<td style="vertical-align: middle"><a href="{{ route('stager.remove', $stager->id) }}" class="btn btn-danger btn-circle" title="Remove Stager"
										data-toggle="tooltip"
										data-placement="top"
										data-method="DELETE"
										data-confirm-title="Please Confirm"
										data-confirm-text="Are you sure?"
										data-confirm-delete="Yes Remove">
										<i class="glyphicon glyphicon-trash"></i>
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

</div>

@stop


@section('scripts')
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

	$("#search").change(function () {
		$("#stager-form").submit();
	});

	$("#standings").change(function () {
		$("#stager-form").submit();
	});

	$("#tag").change(function () {
		$("#stager-form").submit();
	});

	$("#alliance").change(function () {
		$("#stager-form").submit();
	});

	$("#region").change(function () {
		$("#stager-form").submit();
	});

</script>

@stop

