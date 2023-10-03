@extends('layouts.app')

@section('page-title', 'Alliances')

@section('content')

<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
			Alliances
			<small> - all new eden alliances. (updated daily)</small>
			<div class="pull-right">
				<ol class="breadcrumb">
					<li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
					<li><a href="#">Alliances</a></li>
				</ol>
			</div>

		</h1>
	</div>
</div>

@include('partials.messages')


<div class="row tab-search">
	<div class="col-md-6">
		<form method="GET" action="" accept-charset="UTF-8" id="alliances-form" autocomplete="off">
			<div class="col-md-6">
				<div class="input-group custom-search-form">
					<input type="text" class="form-control" name="search" value="{{ Input::get('search') }}" placeholder="Search" meta name="csrf-token" content="{{csrf_token() }}">
					<span class="input-group-btn">
						<button class="btn btn-default" type="submit" id="search-alliances-btn">
							<span class="glyphicon glyphicon-search"></span>
						</button>
						@if (Input::has('search') && Input::get('search') != '')
						<a href="{{ route('alliances.index') }}" class="btn btn-danger" type="button" >
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
	{!! $alliances->appends(\Request::except('alliances'))->render() !!}
</div>

<div class="col-md-12">
	<div class="panel panel-default">
		<div class="panel-heading">Alliances</div>
		<div class="panel-body">
			<div class="table-responsive top-border-table" id="srp-table-wrapper">

				<table class="table" id="alliances">
					<thead>
						<th>@sortablelink('alliance_name', 'Alliance Name')</th>
						<th>@sortablelink('alliance_ticker', 'Ticker')</th>
						<th>@sortablelink('created_at', 'Created')</th>
						<th>@sortablelink('updated_at', 'Last Updated')</th>


					</thead>
					<tbody>

						@if (isset($alliances))              
						@foreach($alliances as $alliance)

						<tr>

							<td><img class="img-circle" src="https://imageserver.eveonline.com/Alliance/{{ $alliance->alliance_alliance_id }}_32.png">&nbsp;<a href="{{ route('alliance.view', $alliance->alliance_alliance_id )}}">{{ $alliance->alliance_name }}</a></td>
							<td style="vertical-align: middle">{{ $alliance->alliance_ticker }}</a></td>
							<td style="vertical-align: middle">{{ $alliance->created_at->diffForHumans() }}</td>
							<td style="vertical-align: middle">{{ $alliance->updated_at->diffForHumans() }}</td>
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
		$("#alliances-form").submit();
	});


</script>

@stop