@extends('layouts.app')

@section('page-title', $corporation->corporation_name)

@section('content')

<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
			{{ $corporation->corporation_name }}
			<small></small>
			<div class="pull-right">
				<ol class="breadcrumb">
					<li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
					<li><a href="{{ route('corporations.index') }}">Corporations</a></li>
					<li class="active">{{ $corporation->corporation_name }}</li>
				</ol>
			</div>

		</h1>
	</div>

	<div class="col-md-3">
	</div>



	<div class="row tab-search">
		<div class="col-md-6">
			<form method="GET" action="" accept-charset="UTF-8" id="structures-form" autocomplete="off">
				<div class="col-md-6">
					<div class="input-group custom-search-form">
						<input type="text" class="form-control" name="search" value="{{ Input::get('search') }}" placeholder="Search Structures" meta name="csrf-token" content="{{csrf_token() }}">
						<span class="input-group-btn">
							<button class="btn btn-default" type="submit" id="search-structures-btn">
								<span class="glyphicon glyphicon-search"></span>
							</button>
							@if (Input::has('search') && Input::get('search') != '')
							<a href="{{ route('corporation.view', $corporation->corporation_corporation_id) }}" class="btn btn-danger" type="button" >
								<span class="glyphicon glyphicon-remove"></span>
							</a>
							@endif
						</span>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-2">
		<div id="edit-user-panel" class="panel panel-default">
			<div class="panel-heading">
				Corporation Information
			</div>

			<div class="panel-body panel-profile">
				<div class="image">
					<img alt="image" class="img-circle avatar" src="https://imageserver.eveonline.com/Corporation/{{ $corporation->corporation_corporation_id }}_128.png">
				</div>
				<div class="name"><a href="{{ route('structures.index')}}?search=&corporation={!! $corporation->corporation_name !!}&sort=str_value&direction=desc" target="_blank"><strong>{{ $corporation->corporation_name }}</strong></a></div>

				<br>

				<div class="col-md-12">
							<div class="col-md-4">
								<a href="https://evewho.com/corporation/{{ $corporation->corporation_corporation_id }}" class="label label-info" data-toggle="tooltip" target="_blank">
									<span >EVE Who</span>
								</a>
							</div>
							<div class="col-md-4">
								<a href="https://zkillboard.com/corporation/{{ $corporation->corporation_corporation_id }}" class="label label-danger" data-toggle="tooltip" target="_blank">
									<span >ZKill</span>
								</a>
							</div>

							<div class="col-md-4">
								<a href="https://evemaps.dotlan.net/corp/{{ $corporation->corporation_corporation_id }}" class="label label-warning" data-toggle="tooltip" target="_blank">
									<span >DOT Lan</span>
								</a>
							</div>
						</div>

						<br>

				<table class="table table-hover">
					<thead>
						<tr>
							<th colspan="3">Information</th>
						</tr>
					</thead>
					<tbody>
						@if(isset($alliance))
						<tr>
							<td>Alliance</td>
							<td><a href="{{ route('alliance.view', $alliance->alliance_alliance_id) }}">{{ $alliance->alliance_name }}</a></td>
						</tr>
						@else
						<tr>
							<td>Alliance</td>
							<td>None</td>
						</tr>
						@endif
						<tr>
							<td>Ticker</td>
							<td>{{ $corporation->corporation_ticker }}</td>
						</tr>
						<tr>
							<td>Member Count</td>
							<td>{{ $corporation->corporation_member_count }}</td>
						</tr>
						<tr>
							<td>Updated</td>
							<td>{{ $corporation->updated_at->diffForHumans() }}</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>





	<div class="col-md-10">
		<div class="panel panel-default">
			<div class="panel-heading">Known Structures</div>
			<div class="panel-body">
				<div class="table-responsive top-border-table" id="srp-table-wrapper">

					<table class="table" id="structures">
						<thead>
							<th> @sortablelink('str_name', 'Structure Name')</th>
							<th> @sortablelink('str_type', 'Type')</th>
							<th> @sortablelink('str_state', 'State')</th>
							<th> @sortablelink('str_status', 'Status')</th>
							<th> @sortablelink('str_system', 'System')</th>
							<th> @sortablelink('str_region_name', 'Region')</th>
							<th> @sortablelink('str_owner_corporation_name', 'Corporation Owner')</th>
							<th> @sortablelink('str_owner_alliance_name', 'Alliance')</th>
							<th> @sortablelink('str_owner_alliance_ticker', 'Alliance TICKER')</th>
							<th> @sortablelink('str_value', 'Fitting Value',)</th>
							<th> @sortablelink('created_at', 'Created')</th>
							<th> @sortablelink('updated_at', 'Last Updated')</th>


						</thead>
						<tbody>

							@if (isset($structures))              
							@foreach($structures as $structure)

							<tr>

								<td style="vertical-align: middle"><a href="{{  route('structures.view', $structure->str_structure_id_md5 )}}">{{ $structure->str_name }}</a></td>
								<td><img class="img-circle" src="https://image.eveonline.com/Type/{{ $structure->str_type_id }}_32.png">&nbsp;{{ $structure->str_type }}</td>

								

								@if ($structure->str_state === "High Power")
								<td style="vertical-align: middle"><span class="label label-success }}">High Power</span></td>
								@elseif ($structure->str_state === "Low Power")
								<td style="vertical-align: middle"><span class="label label-danger }}">Low Power</span></td>
								@elseif ($structure->str_state === "Anchoring")
								<td style="vertical-align: middle"><span class="label label-warning }}">Anchoring</span></td>
								@elseif ($structure->str_state === "Unanchoring")
								<td style="vertical-align: middle"><span class="label label-primary }}">Unanchoring</span></td>
								@elseif ($structure->str_state === "Reinforced")
								<td style="vertical-align: middle"><span class="label label-info }}">Reinforced</span></td>
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



								<td style="vertical-align: middle"><a href="{{  route('solar.system', $structure->str_system_id) }}">{{ $structure->str_system }}</a></td>
								<td style="vertical-align: middle"><a href="{{  route('solar.region', $structure->str_region_id) }}">{{ $structure->str_region_name }}</a></td>
								<td style="vertical-align: middle"><a href="{{ route('corporation.view', $structure->str_owner_corporation_id )}}"><img class="img-circle" src="https://imageserver.eveonline.com/Corporation/{{ $structure->str_owner_corporation_id }}_32.png">&nbsp;{{ $structure->str_owner_corporation_name }}</a>
									<td style="vertical-align: middle"><a href="{{ route('alliance.view', $structure->str_owner_alliance_id )}}"><img class="img-circle" src="https://imageserver.eveonline.com/Alliance/{{ $structure->str_owner_alliance_id }}_32.png">&nbsp;{{ $structure->str_owner_alliance_name }}</a>
										<td style="vertical-align: middle">{{ $structure->str_owner_alliance_ticker }}</td>
										<td style="vertical-align: middle">{{ number_format($structure->str_value,2) }}</td>
										<td style="vertical-align: middle">{{ $structure->created_at->diffForHumans() }}</td>
										<td style="vertical-align: middle">{{ $structure->updated_at->diffForHumans() }}</td>
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
						{!! $structures->appends(\Request::except('structures'))->render() !!}
					</div>
				</div>
			</div>
		</div>
		@stop