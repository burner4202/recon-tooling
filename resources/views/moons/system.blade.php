@extends('layouts.app')

@section('page-title', 'Systems')

@section('content')

<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
			{{ $system->moon_system_name }}
			<small> - Moons in this system</small>
			<div class="pull-right">
				<ol class="breadcrumb">
					<li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
					<li><a href="{{ route('moons.regions') }}">Regions</a></li>
					<li><a href="{{ route('moons.systems', $system->moon_region_id) }}">{{ $system->moon_region_name }}</a></li>
					<li class="active">{{ $system->moon_system_name }}</li>
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
	{!! $moons->appends(\Request::except('system'))->render() !!}
</div>

<div class="col-md-12">
	<div class="panel panel-default">
		<div class="panel-heading">System : No of Moons in System <b>{{ count($moons) }}</b></div>
		<div class="panel-body">

			<div class="table-responsive top-border-table" id="srp-table-wrapper">

				<table class="table" id="constellation">
					<thead>
						<th>@sortablelink('moon_name', 'Moon')</th>
						<th>@sortablelink('moon_constellation_name', 'Constellation Name')</th>
						<th>@sortablelink('moon_r_value', 'Rarity')</th>
						<th>Moon Scanned</th>
					</thead>
					<tbody>

						@if (isset($moons))              
						@foreach($moons as $moon)

						<tr>
							<td><a href="#">{{ $moon->moon_name }}&nbsp;</a>

								
								<td>{{ $moon->moon_constellation_name }}</td>
								<td>{{ $moon->moon_r_rating }}</td>
								

								@if (count($scanned_moons))
								@foreach ($scanned_moons as $scan)
							
								@if ($scan->moon_id == $moon->moon_id)
								<td>Yes</td>
								@endif
								
								@endforeach
						
								<td></td>
								@endif

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

			$("#types").change(function () {
				$("#universe-form").submit();
			});


		</script>

		@stop