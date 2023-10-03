@extends('layouts.app')

@section('page-title', 'Observation List')

@section('content')

<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
			Observations
			<small> - summary of all observations made. </small>
			<div class="pull-right">
				<ol class="breadcrumb">
					<li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
					<li class="active">Observations List</li>
				</ol>
			</div>

		</h1>
	</div>
</div>

@include('partials.messages')
<div class="col-md-12">
	<div class="panel panel-default">
		<div class="panel-heading">Observation Manager</div>
		<div class="panel-body">
			As observations are made, they are to be reviewed on this page, scoring & tagging is essential to review good/bad information, it can be carried out by going into the observation and reviewing it.
		</div>
	</div>
</div>

<div class="col-md-12">
	<div class="panel panel-default">
		<div class="panel-heading">Observations</div>
		<div class="panel-body">

			
			<div class="table-responsive top-border-table" id="observations-table-wrapper">

				<table class="table" id="outstanding-tasks">
					<thead>
						<th> @sortablelink('observation', 'Observation')</th>
						<th> @sortablelink('created_by_username', 'Submitted By')</th>
						<th> @sortablelink('state', 'State')</th>
						<th> @sortablelink('prority', 'Prority')</th>
						<th> @sortablelink('score', 'Score')</th>
						<th> @sortablelink('system_name', 'System')</th>
						<th> @sortablelink('corporation_name', 'Corporation')</th>
						<th> @sortablelink('alliance_name', 'Alliance')</th>
						<th> @sortablelink('reviewed_by_username', 'Reviewed By')</th>
						<th> @sortablelink('created_at', 'Created At')</th>
						<th> Delete </th>
					</thead>

					<tbody>

						@if (isset($observations))              
						@foreach($observations as $observation)

						<tr>
							<td style="vertical-align: middle"><a href="{{ route('observation.view', $observation->unique_id) }}">{{ substr(strip_tags($observation->observation),0 ,20) }}...</a></td>
							<td style="vertical-align: middle">{{ $observation->created_by_username }}</td>
							@if($observation->state == 0)
							<td style="vertical-align: middle"><span class="label label-warning">For Review.</span></td>
							@elseif($observation->state == 1)
							<td style="vertical-align: middle"><span class="label label-success">Reviewed.</span></td>
							@else
							<td style="vertical-align: middle"><span class="label label-danger">Deleted</span></td>
							@endif

							@if($observation->prority == 0)
							<td style="vertical-align: middle"><span class="label label-warning">For Review.</span></td>
							@elseif($observation->prority == 1)
							<td style="vertical-align: middle"><span class="label label-danger">Useless</span></td>
							@elseif($observation->prority == 2)
							<td style="vertical-align: middle"><span class="label label-warning">Low</span></td>
							@elseif($observation->prority == 3)
							<td style="vertical-align: middle"><span class="label label-info">High</span></td>
							@elseif($observation->prority == 4)
							<td style="vertical-align: middle"><span class="label label-success">Urgent</span></td>
							@else
							<td style="vertical-align: middle"></td>
							@endif

							@if($observation->score == 1)
							<td style="vertical-align: middle"><span class="label label-success">Awesome</span></td>
							@elseif($observation->score == 2)
							<td style="vertical-align: middle"><span class="label label-info">Good</span></td>
							@elseif($observation->score == 3)
							<td style="vertical-align: middle"><span class="label label-warning">Meh</span></td>
							@elseif($observation->score == 4)
							<td style="vertical-align: middle"><span class="label label-danger">Pure Shit</span></td>
							@else
							<td style="vertical-align: middle"></td>
							@endif

							<td style="vertical-align: middle">{{ $observation->system_name }}</td>
							<td style="vertical-align: middle">{{ $observation->corporation_name }}</td>
							<td style="vertical-align: middle">{{ $observation->alliance_name }}</td>
							
							<td style="vertical-align: middle">{{ $observation->reviewed_by_username }}</td>	
							<td style="vertical-align: middle">{{ $observation->created_at }} : {{ \Carbon\Carbon::parse($observation->created_at)->diffForHumans() }}</td>
							<td class="text-center">
								<a href="{{ route('observation.remove', $observation->unique_id )}}" class="btn btn-danger btn-circle" title="Delete"
									data-toggle="tooltip"
									data-placement="right"
									data-method="GET"
									data-confirm-title="Confirm"
									data-confirm-text="Are you Sure"
									data-confirm-delete="Delete">
									<i class="glyphicon glyphicon-trash"></i>
								</a>
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

		</div>

	</div>
</div>

<div class="pull-right">{!! $observations->render() !!}</div>
@stop


