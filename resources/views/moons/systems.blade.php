@extends('layouts.app')

@section('page-title', 'Systems')

@section('content')

<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
			{{ $region->moon_region_name }}
			<small> - Moons in this system</small>
			<div class="pull-right">
				<ol class="breadcrumb">
					<li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
					<li><a href="{{ route('moons.regions') }}">Regions</a></li>
					<li class="active">{{ $region->moon_region_name }}</li>
				</ol>
			</div>

		</h1>
	</div>
</div>

@include('partials.messages')


<div class="row tab-search">
	<div class="col-md-12">
		<form method="GET" action="" accept-charset="UTF-8" id="universe-form" autocomplete="off">
			<div class="col-md-3">
				<div class="input-group custom-search-form">
					<input type="text" class="form-control" name="search" value="{{ Input::get('search') }}" placeholder="Search System" meta name="csrf-token" content="{{csrf_token() }}">
					<span class="input-group-btn">
						<button class="btn btn-default" type="submit" id="search-universe-btn">
							<span class="glyphicon glyphicon-search"></span>
						</button>
						@if (Input::has('search') && Input::get('search') != '')
						<a href="#" class="btn btn-danger" type="button" >
							<span class="glyphicon glyphicon-remove"></span>
						</a>
						@endif
					</span>
				</div>
			</div>
		</form>
	</div>
</div>

<div class="col-md-6">
	{!! $systems->appends(\Request::except('systems'))->render() !!}
</div>

<div class="col-md-12">
	<div class="panel panel-default">
		<div class="panel-heading">Systems</div>
		<div class="panel-body">

			<div class="table-responsive top-border-table" id="srp-table-wrapper">

				<table class="table" id="constellation">
					<thead>
						<th>@sortablelink('moon_system_name', 'System')</th>
						<th>@sortablelink('moon_constellation_name', 'Constellation Name')</th>
						<th>Scanned Moons / Total Moons</th>
					</thead>
					<tbody>

						@if (isset($systems))              
						@foreach($systems as $system)

						<tr>
							<td><a href="{{ route('moons.system', $system->moon_system_id)}}">{{ $system->moon_system_name }}&nbsp;</a>


								<td>{{ $system->moon_constellation_name }}</td>
								@php($total_moons = 0)
								@foreach ($moons as $moon)
								@if($moon->moon_system_id == $system->moon_system_id)
								@php($total_moons += 1)
								@endif
								@endforeach

								@php($scanned_moons = 0)
								@foreach ($moon_scans as $moon)
								@if($moon->moon_system_id == $system->moon_system_id)
								@php($scanned_moons += 1)
								@endif
								@endforeach

								<td>{{ $scanned_moons }} / {{ $total_moons }}</td>

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
</div>
@stop


@section('scripts')


<script>

	$("#types").change(function () {
		$("#universe-form").submit();
	});


</script>

@stop