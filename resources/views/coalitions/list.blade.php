@extends('layouts.app')

@section('page-title', 'Coalitions')

@section('content')


<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
			Coalitions
			<small>- List of all Coalitions & Alliances</small>
			<div class="pull-right">
				<ol class="breadcrumb">
					<li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
					<li class="active">Coalitions</li>
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
			<div class="panel-heading">Coalition List</div>
			<div class="panel-body">
				<div class="col-md-12">

					<div class="table-responsive top-border-table" id="location-table-wrapper">

						<table class="table" id="previous-audits">
							<thead>
								<th> Name</th>
								<th> Alliances</th>
								<th> Corporations</th>
								<th> Member Count</th>
							</thead>

							<tbody>


								@if (count($chart))              
								@foreach($chart as $coalition)

								<tr>
									<td style="vertical-align: middle"><b><a href="{{ route('coalitions.view_coalition', $coalition['coalition_id']) }}">{!! $coalition['name'] !!}</b></a></td>
									<td style="vertical-align: middle">{!! $coalition['alliance_count'] !!}</td>
									<td style="vertical-align: middle">{!! $coalition['corporation_count'] !!}</td>
									<td style="vertical-align: middle">{!! $coalition['corporation_member_count'] !!}</td>
							
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
	</div>

</div>






@stop

