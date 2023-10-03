@extends('layouts.app')

@section('page-title', 'Dossier Manager')

@section('content')

<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
			Dossier Manager
			<small> - create dossiers for groups</small>
			<div class="pull-right">
				<ol class="breadcrumb">
					<li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
					<li class="active">Dossier Manager</li>
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

<div class="row col-md-12">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				Dossier Manager
			</div>
			<div class="panel-body">
				This section of the tools allows for the creation of a dossier of any selected group.<br>
				Begin by first selecting a target corporation, this is initally carried out by reviewing structure and system index data.
			</div>
		</div>
	</div>
</div>


<div class="row col-md-12">
	<div class="col-md-2">
		<div class="panel panel-default">
			<div class="panel-heading">Select Corporation</div>
			<div class="panel-body">

				<form method="post" action="{{ route('dossier.create')}}" enctype="multipart/form-data">
					{{ csrf_field() }}
					<div class="panel-body" >
						<div class="col-md-12">


							<div class="form-group">
								<label for="system">Corporation</label>
								<input type="text" class="typeahead-corporations form-control" name="corporation" id="corporation" placeholder="Search Corporation" autocomplete="off" >
							</div>


						</div>
					</div>

					<div class="form-group row">
						<div style="text-align: center;">
							<button type="submit" class="btn btn-success">Begin Creating a Dossier</button>
						</div>
					</div>
				</form>
			</div>					
		</div>
	</div>
	<div class="col-md-10">
		<div class="panel panel-default">
			<div class="panel-heading">Previous Dossiers
			</div>
			<div class="panel-body">
				<div class="table-responsive top-border-table" id="dossier-table-wrapper">

					<table class="table" id="dossier-table-wrapper">
						<thead>
							<th style="vertical-align: middle">Dossier Title</th>
							<th style="vertical-align: middle">Group</th>
							<th style="vertical-align: middle">Target Alliance</th>
							<th style="vertical-align: middle">Relationship Score</th>
							<th style="vertical-align: middle">Function</th>
							<th style="vertical-align: middle">Created By</th>
							<th style="vertical-align: middle">Reviewed By</th>
							<th style="vertical-align: middle">Created</th>
							<th style="vertical-align: middle">Reviewed</th>
							<th style="vertical-align: middle">State</th>
							<th> Action </th>
						</thead>

						<tbody>

							@if (isset($dossiers))              
							@foreach($dossiers as $dossier)

							<tr>
								<td style="vertical-align: middle"><a href="{{ route('dossier.view', $dossier->id)}}">{!! $dossier->dossier_title !!}</a></td>
								<td style="vertical-align: middle"><a href="{{ route('corporation.view', $dossier->corporation_id )}}"><img class="img-circle" src="https://images.evetech.net/corporations/{{ $dossier->corporation_id  }}/logo?size=32">&nbsp;{{ $dossier->corporation_name }}</a></td>
								<td style="vertical-align: middle"><a href="{{ route('alliance.view', $dossier->target_alliance_id) }}">{{ $dossier->target_alliance_name }}</a></td>
								<td style="vertical-align: middle">{!! $dossier->relationship_score !!}/100</td>
								<td style="vertical-align: middle">{!! $dossier->corporation_function !!}</td>
								<td style="vertical-align: middle">{!! $dossier->created_by_username !!}</td>
								<td style="vertical-align: middle">{!! $dossier->approved_by_username !!}</td>

								<td style="vertical-align: middle">{!! \Carbon\Carbon::parse($dossier->created_at)->format('d M y, H:m:s') !!}</td>

								@if($dossier->approved_date > 1)
								<td style="vertical-align: middle">{!! \Carbon\Carbon::parse($dossier->approved_date)->format('d M y, H:m:s') !!}</td>
								@else
								<td style="vertical-align: middle"></td>
								@endif
								@if($dossier->state == 1) 
								<td style="vertical-align: middle">Review</td>
								@else
								<td style="vertical-align: middle">Approved</td>
								@endif
								<td class="text-center">
									<a href="{{ route('dossier.delete', $dossier->id )}}" class="btn btn-danger btn-circle" title="Delete"
										data-toggle="tooltip"
										data-placement="right"
										data-method="GET"
										data-confirm-title="Confirm"
										data-confirm-text="Are you Sure"
										data-confirm-delete="Delete">
										<i class="glyphicon glyphicon-trash"></i>
									</a>
								</td>
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




@stop

@section('styles')
{!! HTML::style('assets/css/bootstrap-datetimepicker.min.css') !!}
@stop

@section('scripts')

<script>
	var path3 = "{{ route('autocomplete.corporations') }}";
	$('input.typeahead-corporations').typeahead({
		source:  function (corporation, process) {
			return $.get(path3, { corporation: corporation }, function (data3) {
				return process(data3);
			});
		}
	});
</script>

{!! HTML::script('assets/js/moment.min.js') !!}
{!! HTML::script('assets/js/bootstrap-datetimepicker.min.js') !!}


<script>
	$(document).ready(function(){
		$('#previous-dossiers').DataTable( {
			"paging":   false,
			"searching": true,
			"pageLength": 500,
		}

	});
</script>

@stop
