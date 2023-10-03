@extends('layouts.app')

@section('page-title', 'Route Planning')

@section('content')

<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
			Active Characters
			<small> - location / ship / online.</small>
			<div class="pull-right">
				<ol class="breadcrumb">
					<li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
					<li class="active">Active Characters</li>
				</ol>
			</div>

		</h1>
	</div>
</div>

@include('partials.messages')

<div class="row tab-search">
	<div class="col-md-12">
		
	</div>
</div>

<div class="row tab-search">
	<div class="col-md-2">
		<a href="{{ route('route.update_mine') }}" class="btn btn-primary active" title="Click here to Update your Characters" id="add-system">
			<i class="glyphicon glyphicon-eye"></i>
			Update Character Information
		</a>
	</div>
	<div class="col-md-8"></div>
	<form method="GET" action="" accept-charset="UTF-8" id="system-form">
		<div class="col-md-2">
			<div class="input-group custom-search-form">
				<input type="text" class="typeahead form-control" name="search" id="search" value="{{ Input::get('search') }}" placeholder="Search System & Click The Play Button!" autocomplete="off" >
				<span class="input-group-btn">
					<button class="btn btn-default" type="submit" id="search-users-btn">
						<span class="glyphicon glyphicon-play"></span>
					</button>
					@if (Input::has('search') && Input::get('search') != '')
					<a href="{{ route('route.planning') }}" class="btn btn-danger" type="button" >
						<span class="glyphicon glyphicon-remove"></span>
					</a>
					@endif
				</span>
			</div>
		</div>
	</form>
</div>

<div class="row tab-search">
	<div class="col-md-5"></div>
</div>

<div class="table-responsive top-border-table" id="location-table-wrapper">

	<table class="table" id="characters">
		<thead>
			<th> @sortablelink('slo_character_name', 'Character Name')</th>
			<th> @sortablelink('slo_corporation_name', 'Corporation Name')</th>
			<th> @sortablelink('slo_solar_system_name', 'Solar System')</th>
			<th> @sortablelink('slo_region_name', 'Region Name')</th>
			<th> @sortablelink('slo_last_login', 'Last Login')</th>
			<th> @sortablelink('slo_last_logout', 'Last Logout')</th>
			<th> @sortablelink('slo_online', 'Online')</th>
			<th> @sortablelink('slo_logins', 'Logins')</th>
			<th> @sortablelink('slo_ship_name', 'Ship Name')</th>
			<th> @sortablelink('slo_ship_type_id_name', 'Ship Type')</th>
			<th> @sortablelink('slo_desto_solar_system_name', 'Destination System')</th>
			<th> @sortablelink('slo_desto_solar_system_jumps', 'Jumps')</th>
			<th> @sortablelink('updated_at', 'Last Updated')</th>
		</thead>
		<tbody>

			@if (isset($characters))              
			@foreach($characters as $character)

			<tr>
				<td><a href="#"><img class="img-circle" src="https://imageserver.eveonline.com/Character/{{ $character->slo_character_id }}_32.jpg">&nbsp;{{ $character->slo_character_name }}</a></td>
				<td><img class="img-circle" src="https://imageserver.eveonline.com/Corporation/{{ $character->slo_corporation_id }}_32.png">&nbsp;{{ $character->slo_corporation_name }}</a></td>
				<td style="vertical-align: middle"><a href="{{  route('solar.system', $character->slo_solar_system_id )}}">{{ $character->slo_solar_system_name }}</a></td>
				<td style="vertical-align: middle"><a href="{{  route('solar.region', $character->slo_region_id )}}">{{ $character->slo_region_name }}</a></td>
				<td style="vertical-align: middle">{{ $character->slo_last_login }} : {{ \Carbon\Carbon::parse($character->slo_last_login)->diffForHumans() }}</a></td>
				<td style="vertical-align: middle">{{ $character->slo_last_logout }} : {{ \Carbon\Carbon::parse($character->slo_last_logout)->diffForHumans() }}</a></td>
				<td style="vertical-align: middle">
					@if ($character->slo_online)
					<span class="label label-success }}">Online</span>
					@else
					<span class="label label-danger }}">Offline</span>
					@endif
				</td>
				<td style="vertical-align: middle">{{ $character->slo_logins }}</a></td>
				<td style="vertical-align: middle">{{ $character->slo_ship_name }}</a></td>
				<td style="vertical-align: middle">{{ $character->slo_ship_type_id_name }}</a></td>
				<td style="vertical-align: middle"><a href="{{  route('solar.system', $character->slo_desto_solar_system_id )}}">{{ $character->slo_desto_solar_system_name }}</a></td>
				<td style="vertical-align: middle">@if ($character->slo_desto_solar_system_jumps > 1) {{ $character->slo_desto_solar_system_jumps }} @else 0 @endif</a></td>
				<td style="vertical-align: middle">{{ $character->updated_at }} : {{ $character->updated_at->diffForHumans() }}</a></td>

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
@stop


@section('scripts')


<script>

	var path = "{{ route('route.planning_mine_autocomplete') }}";
	$('input.typeahead').typeahead({
		source:  function (query, process) {
			return $.get(path, { query: query }, function (data) {
				return process(data);
			});
		}
	});

</script>

@stop

