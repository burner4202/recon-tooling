@extends('layouts.app')

@section('page-title', 'Entosis Manager | Overview')

@section('content')

<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
			Active Campaigns
			<small></small>
			<div class="pull-right">
				<ol class="breadcrumb">
					<li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
					<li class="active">Entosis Manager / Active Campaigns</li>
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


<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">Active Campaigns</div>
			<div class="panel-body">


				<div class="table-responsive top-border-table" id="campaigns-table-wrapper">
					<table class="table" id="active-campaigns">
						<thead>
							<th style="vertical-align: middle">System</th>
							<th style="vertical-align: middle">Constellation</th>
							<th style="vertical-align: middle">Region</th>
							<th style="vertical-align: middle">Event Type</th>
							<th style="vertical-align: middle">Structure Type</th>
							<th style="vertical-align: middle">Availability</th>
							<th style="vertical-align: middle">Created By</th>
							<th style="vertical-align: middle">Created At</th>
							<th style="vertical-align: middle">View Campaign</th>
						</thead>

						<tbody>


							@if (isset($active_campaigns))              
							@foreach($active_campaigns as $campaign)

							<tr>
								<td style="vertical-align: middle">{!! $campaign->ec_target_system !!}</td>
								<td style="vertical-align: middle">{!! $campaign->ec_target_constellation !!}</td>
								<td style="vertical-align: middle">{!! $campaign->ec_target_region !!}</td>
								<td style="vertical-align: middle">{!! $campaign->ec_event_type !!}</td>
								<td style="vertical-align: middle">{!! $campaign->ec_structure_type !!}</td>
								<td style="vertical-align: middle">{{ $campaign->ec_availability }}</td>
								<td style="vertical-align: middle">{!! $campaign->ec_campaign_created_by !!}</td>
								<td style="vertical-align: middle">{!! \Carbon\Carbon::parse($campaign->ec_campaign_created_at)->format('d M y, H:m:s') !!}</td>
								<td style="vertical-align: middle">
									<a href="{{ route('entosis.view', $campaign->ec_campaign_id) }}" class="label label-info" data-toggle="tooltip" data-placement="top">
										<span >View Campaign</span>
									</a>
								</td>


							</td>
						</tr>

						@endforeach
						@else

						<tr>
							<td colspan="6"><em>No Active Campaigns</em></td>
						</tr>

						@endif




					</tbody>

				</table>


			</div>
		</div>
	</div>
</div>




@stop
