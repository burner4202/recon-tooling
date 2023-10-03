@extends('layouts.app')

@section('page-title', 'Corporations')

@section('content')

<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
			Corporations
			<small> - all new eden corporations. (updated daily)</small>
			<div class="pull-right">
				<ol class="breadcrumb">
					<li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
					<li><a href="#">Corporations</a></li>
				</ol>
			</div>

		</h1>
	</div>
</div>

@include('partials.messages')


<div class="row tab-search">
	<div class="col-md-12">
		<form method="GET" action="" accept-charset="UTF-8" id="corporations-form" autocomplete="off">
			<div class="col-md-3">
				<div class="input-group custom-search-form">
					<input type="text" class="form-control" name="search" value="{{ Input::get('search') }}" placeholder="Search" meta name="csrf-token" content="{{csrf_token() }}">
					<span class="input-group-btn">
						<button class="btn btn-default" type="submit" id="search-corporations-btn">
							<span class="glyphicon glyphicon-search"></span>
						</button>
						@if (Input::has('search') && Input::get('search') != '')
						<a href="{{ route('corporations.index') }}" class="btn btn-danger" type="button" >
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
	{!! $corporations->appends(\Request::except('corporations'))->render() !!}
</div>


	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">Corporations</div>
			<div class="panel-body">
				<div class="table-responsive top-border-table" id="srp-table-wrapper">

					<table class="table" id="corporations">
						<thead>
							<th>@sortablelink('corporation_name', 'Corporation Name')</th>
							<th>@sortablelink('alliance_name', 'Alliance Name')</th>
							<th>@sortablelink('corporation_ticker', 'Corporation Ticker')</th>
							<th>@sortablelink('alliance_ticker', 'Alliance Ticker')</th>
							<th>@sortablelink('corporation_member_count', 'Members')</th>
							<th>@sortablelink('created_at', 'Created')</th>
							<th>@sortablelink('updated_at', 'Last Updated')</th>


						</thead>
						<tbody>

							@if (isset($corporations))              
							@foreach($corporations as $corporation)

							<tr>

								<td><img class="img-circle" src="https://imageserver.eveonline.com/Corporation/{{ $corporation->corporation_corporation_id }}_32.png">&nbsp;<a href="{{ route('corporation.view', $corporation->corporation_corporation_id )}}">{{ $corporation->corporation_name }}</a></td>
								<td><img class="img-circle" src="https://imageserver.eveonline.com/Alliance/{{ $corporation->alliance_alliance_id }}_32.png">&nbsp;<a href="{{ route('alliance.view', $corporation->corporation_alliance_id )}}">{{ $corporation->alliance_name }}</a></td>
								<td style="vertical-align: middle">{{ $corporation->corporation_ticker }}</a></td>
								<td style="vertical-align: middle">{{ $corporation->alliance_ticker }}</a></td>
								<td style="vertical-align: middle">{{ $corporation->corporation_member_count }}</a></td>
								<td style="vertical-align: middle">{{ $corporation->created_at->diffForHumans() }}</td>
								<td style="vertical-align: middle">{{ $corporation->updated_at->diffForHumans() }}</td>
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

		$("#types").change(function () {
			$("#corporations-form").submit();
		});


	</script>

	@stop