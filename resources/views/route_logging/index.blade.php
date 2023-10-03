@extends('layouts.app')

@section('page-title', 'Route Logging')

@section('content')

<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
			Route Logging
			<small> - summary of viewed routes</small>
			<div class="pull-right">
				<ol class="breadcrumb">
					<li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
					<li class="active">Route Logging</li>
				</ol>
			</div>

		</h1>
	</div>
</div>

@include('partials.messages')

<div class="row tab-search">
	<div class="col-md-12"></div>
	<form method="GET" action="" accept-charset="UTF-8" id="route-logging-form">
		<div class="col-md-2">
			Search Everything
			<div class="input-group custom-search-form">
				<input type="text" class="form-control" name="search" value="{{ Input::get('search') }}" placeholder="Search Everything">
				<span class="input-group-btn">
					<button class="btn btn-default" type="submit" id="search-route-logging-btn">
						<span class="glyphicon glyphicon-search"></span>
					</button>
					@if (
						Input::has('search') && Input::get('search') != '')
						<a href="{{ route('route_logging.index') }}" class="btn btn-danger" type="button" >
							<span class="glyphicon glyphicon-remove"></span>
						</a>
						@endif
					</span>
				</div>
			</div>

			</div>
		</div>
	</form>




<div class="col-md-12">
	{!! $logging->appends(\Request::except('logging'))->render() !!}
	<div class="panel panel-default">
		<div class="panel-heading">Route Logs</div>
		<div class="panel-body">

			
			<div class="table-responsive top-border-table" id="location-table-wrapper">

				<table class="table" id="route_logging">
					<thead>
						<th> @sortablelink('username', 'Username')</th>
						<th> @sortablelink('ip', 'Ip')</th>
						<th> @sortablelink('url', 'URL')</th>
						<th> @sortablelink('created_at', 'Created')</th>
					</thead>

					<tbody>

						@if (isset($logging))              
						@foreach($logging as $route)

						<tr>
							<td style="vertical-align: middle">{!! $route->username !!}</td>
							<td style="vertical-align: middle">{!! $route->ip !!}</td>
							<td style="vertical-align: middle"><a href="{!! $route->url !!}">{!! $route->url !!}</a></td>
							<td style="vertical-align: middle">{!! \Carbon\Carbon::parse($route->created_at) !!} : {!! \Carbon\Carbon::parse($route->created_at)->diffForHumans() !!} </td>
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
			$("#route_logging-form").submit();
		});

	</script>
	@stop


