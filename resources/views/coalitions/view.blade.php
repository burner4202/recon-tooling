@extends('layouts.app')

@section('page-title', 'Coalitions')

@section('content')


<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
			Manage Coalition
			<small>- {{ $coalition->name }}</small>
			<div class="pull-right">
				<ol class="breadcrumb">
					<li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
					<li class="active"><a href="{{ route('coalitions.manage.index')}}">Manage Coalitions</a></li>
					<li class="active">{{ $coalition->name }}</li>
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
	<div class="col-md-2">
		<div class="panel panel-default">
			<div class="panel-heading">Add Alliance</div>
			<div class="panel-body">

				<form method="post" action="{{ route('coalitions.add_alliance', $coalition->id)}}" enctype="multipart/form-data">
					{{ csrf_field() }}

					<div class="panel-body" >
						<div class="col-md-12">
							<div class="input-group custom-search-form">
								Alliance
								<input type="text" class="form-control typeahead-alliances" name="alliance" value="{{ Input::get('alliance') }}" placeholder="..." autocomplete="off">
							</div>

						</div>
					</div>

					<div class="form-group row">
						<div style="text-align: center;">
							<button type="submit" class="btn btn-success">Add Alliance</button>
						</div>
					</div>
				</form>
			</div>					
		</div>
	</div>
	<div class="col-md-10">
		<div class="panel panel-default">
			<div class="panel-heading">Alliances</div>
			<div class="panel-body">
				<div class="col-md-12">

					<div class="table-responsive top-border-table" id="location-table-wrapper">

						<table class="table" id="previous-audits">
							<thead>
								<th> Alliance</th>
								<th> Ticker</th>
								<th> Remove</th>
							</thead>

							<tbody>


								@if (isset($alliances))              
								@foreach($alliances as $alliance)

								<tr>
									

									<td style="vertical-align: middle"><img class="img-circle" src="https://images.evetech.net/alliances/{{ $alliance->alliance_alliance_id }}/logo?size=32">&nbsp;{{ $alliance->alliance_name }}</td>
									<td style="vertical-align: middle">{{ $alliance->alliance_ticker }}</td>
									<td style="vertical-align: middle">
										<a href="{{ route('coalitions.remove_alliance', $alliance->alliance_alliance_id)}}" class="btn btn-danger btn-circle" title="Remove Alliance"
											data-toggle="tooltip"
											data-placement="right"
											data-method="GET"
											data-confirm-title="@lang('app.please_confirm')"
											data-confirm-text="Are you sure"
											data-confirm-delete="Yes">
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
	</div>

</div>

@stop
@section('scripts')
<script>
	var path4 = "{{ route('autocomplete.alliances') }}";
	$('input.typeahead-alliances').typeahead({
		source:  function (alliance, process) {
			return $.get(path4, { alliance: alliance }, function (data4) {
				return process(data4);
			});
		}
	});


</script>
@stop


