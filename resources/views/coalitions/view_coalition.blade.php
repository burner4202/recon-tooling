@extends('layouts.app')

@section('page-title', 'Coalitions')

@section('content')


<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
			{{ $coalition['coalition_name'] }}
			<small></small>
			<div class="pull-right">
				<ol class="breadcrumb">
					<li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
					<li class="active"><a href="{{ route('coalitions.list')}}">Coalitions</a></li>
					<li class="active">{{ $coalition['coalition_name'] }}</li>
				</ol>
			</div>
		</h1>
	</div>
</div>


@include('partials.messages')


<div class="row tab-search">
	<div class="col-md-5"></div>
</div>

<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">Alliances</div>
			<div class="panel-body">
				<div class="col-md-12">

					<div class="table-responsive top-border-table" id="location-table-wrapper">

						<table class="table" id="previous-audits">
							<thead>
								<th> Alliance</th>
								<th> Ticker</th>
								<th> Member Count</th>

							</thead>

							<tbody>


								@if (isset($alliances))              
								@foreach($alliances as $alliance)

								<tr>
									

									<td style="vertical-align: middle"><img class="img-circle" src="https://images.evetech.net/alliances/{{ $alliance->alliance_id }}/logo?size=32">&nbsp;{{ $alliance->alliance_name }}</td>
									<td style="vertical-align: middle">{{ $alliance->alliance_ticker }}</td>
									<td style="vertical-align: middle">{{ $corporations->where('coalition_id', $alliance->coalition_id)->where('alliance_id', $alliance->alliance_id)->sum('corporation_member_count') }}</td>

								</tr>

								@endforeach

								<tr>
									

									<td style="vertical-align: middle"></td>
									<td style="vertical-align: middle"></td>
									<td style="vertical-align: middle"><b>{{ $corporations->where('coalition_id', $coalition['coalition_id'])->sum('corporation_member_count') }}</b></td>

								</tr>

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


