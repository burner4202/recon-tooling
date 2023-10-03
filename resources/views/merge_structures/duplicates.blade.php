@extends('layouts.app')

@section('page-title', 'Duplicate Structures')

@section('content')

<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
			Duplicate Structures
			<small> - structures must be merged to maintain database integrity</small>
			<div class="pull-right">
				<ol class="breadcrumb">
					<li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
					<li class="active">Duplicate Structures</li>
				</ol>
			</div>

		</h1>
	</div>
</div>
<div class="row">
	@include('partials.messages')
</div>


<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				Duplicate Structures
				<div class="pull-right" style="vertical-align:middle;">
					<span class="glyphicon glyphicon-info-sign fa-1x" data-toggle="tooltip" title=".. place holder..." data-placement="left"></span>
				</div>

			</div>
			<div class="panel-body">
				<table class="table duplicate-structures" id="duplicate-structures">
					<thead>
						<tr>
							<th>Structure Name</th>
							<th>Structure Id</th>
							<th>Type</th>
							<th>Owner</th>
							<th>Alliance</th>
							<th>Fitting Value</th>
							<th>System</th>
							<th>Created</th>
						</tr>
					</thead>
					<tbody>

						@if(isset($duplicate_structures))
						@foreach($duplicate_structures as $dup_structure)

						<tr>
							<td><a href="{{ route('structures.view', $dup_structure->str_structure_id_md5) }}" target="_blank">{{ $dup_structure->str_name }}</a></td>
							<td>{{ $dup_structure->str_structure_id }}</td>
							<td>{{ $dup_structure->str_type }}</td>
							@if ($dup_structure->str_owner_corporation_name == "")
							<td>
								No Corporation
							</td>
							@else
							<td><a href="{{ route('corporation.view', $dup_structure->str_owner_corporation_id )}}"><img class="img-circle" src="https://images.evetech.net/corporations/{{ $dup_structure->str_owner_corporation_id }}/logo?size=32">&nbsp;{{ $dup_structure->str_owner_corporation_name }}</a>
							</td>
							@endif
							@if ($dup_structure->str_owner_alliance_name === "")
							<td>
								No Alliance
							</td>
							@else
							<td><a href="{{ route('alliance.view', $dup_structure->str_owner_alliance_id )}}"><img class="img-circle" src="https://images.evetech.net/alliances/{{ $dup_structure->str_owner_alliance_id }}/logo?size=32">&nbsp;{{ $dup_structure->str_owner_alliance_name }} ({{ $dup_structure->str_owner_alliance_ticker }})</a>
							</td>
							@endif
							<td><b>{{ number_format($dup_structure->str_value,2) }} isk</b></td>
							<td><a href="{{ route('solar.system', $dup_structure->str_system_id )}}">{{ $dup_structure->str_system }}</a></td>
							<td>{{ $dup_structure->created_at }} : {{ \Carbon\Carbon::parse($dup_structure->created_at)->diffForHumans() }}</td>
						</tr>

						@endforeach
						@else

						<tr>
							<td colspan="6"><em>No Duplicate Records Found</em></td>
						</tr>

						@endif
					</tbody>
				</table>

			</div>
		</div>
	</div>
</div>



@stop