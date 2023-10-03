@extends('layouts.app')

@section('page-title', 'Alliance Standings | Index')

@section('content')

<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
			Alliance Standings
			<small> - yes, we hate alot of people.</small>
			<div class="pull-right">
				<ol class="breadcrumb">
					<li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
					<li class="active">Alliance Standings</li>
				</ol>
			</div>

		</h1>
	</div>
</div>

@include('partials.messages')

<div class="row tab-search">
	<div class="col-md-12"></div>
	<form method="GET" action="" accept-charset="UTF-8" id="activity-form">
		<div class="col-md-2">
			Search Everything
			<div class="input-group custom-search-form">
				<input type="text" class="form-control" name="search" value="{{ Input::get('search') }}" placeholder="Search Everything">
				<span class="input-group-btn">
					<button class="btn btn-default" type="submit" id="search-standings-btn">
						<span class="glyphicon glyphicon-search"></span>
					</button>
					@if (
						Input::has('search') && Input::get('search') != ''

						)
						<a href="{{ route('standings.index') }}" class="btn btn-danger" system="button" >
							<span class="glyphicon glyphicon-remove"></span>
						</a>
						@endif
					</span>
				</div>
			</div>


		</form>


		
	</div>

</div>



<div class="col-md-12">
	{!! $standings->appends(\Request::except('standings'))->render() !!}
	<div class="panel panel-default">
		<div class="panel-heading">Alliance Standings</div>
		<div class="panel-body">

			
			<div class="table-responsive top-border-table" id="standings-table-wrapper">

				<table class="table" id="standings">
					<thead>
						<th> @sortablelink('as_character_name', 'Character')</th>
						<th> @sortablelink('as_corporation_name', 'Corporation Name')</th>
						<th> @sortablelink('as_alliance_name', 'Alliance Name')</th>
						<th> @sortablelink('as_standing', 'Standing')</th>
						<th> @sortablelink('as_updated_at', 'Updated At')</th>
					</thead>

					<tbody>

						@if (isset($standings))              
						@foreach($standings as $contact)

						<tr>
							@if($contact->as_contact_type == "character")
							<td style="vertical-align: middle"><img class="img-circle" src="https://image.eveonline.com/Character/{{ $contact->as_contact_id}}_32.jpg">&nbsp;{{ $contact->as_character_name }}</td>
							<td style="vertical-align: middle"><img class="img-circle" src="https://image.eveonline.com/Corporation/{{ $contact->as_corporation_id}}_32.png">&nbsp;{{ $contact->as_corporation_name }}</td>
							@if($contact->as_alliance_id == 0)
							<td>-</td>
							@else
							<td style="vertical-align: middle"><img class="img-circle" src="https://image.eveonline.com/Alliance/{{ $contact->as_alliance_id}}_32.png">&nbsp;{{ $contact->as_alliance_name }}</td>
							@endif
							@elseif($contact->as_contact_type == "corporation")
							<td>-</td>
							<td style="vertical-align: middle"><img class="img-circle" src="https://image.eveonline.com/Corporation/{{ $contact->as_contact_id}}_32.png">&nbsp;{{ $contact->as_corporation_name }}</td>
							@if($contact->as_alliance_id == 0)
							<td>-</td>
							@else
							<td style="vertical-align: middle"><img class="img-circle" src="https://image.eveonline.com/Alliance/{{ $contact->as_alliance_id}}_32.png">&nbsp;{{ $contact->as_alliance_name }}</td>
							@endif
							@elseif($contact->as_contact_type == "alliance")
							<td>-</td>
							<td>-</td>
							<td style="vertical-align: middle"><img class="img-circle" src="https://image.eveonline.com/Alliance/{{ $contact->as_contact_id}}_32.png">&nbsp;{{ $contact->as_alliance_name }}</td>
							@endif

							@if($contact->as_standing <= 10 && $contact->as_standing >= 5)
							<td style="vertical-align: middle"><span class="label label-primary">{{ $contact->as_standing }}</span></td>
							@elseif($contact->as_standing <= 5 && $contact->as_standing >= 0)
							<td style="vertical-align: middle"><span class="label label-info">{{ $contact->as_standing }}</span></td>
							@elseif($contact->as_standing <= 0 && $contact->as_standing >= -5)
							<td style="vertical-align: middle"><span class="label label-warning">{{ $contact->as_standing }}</span></td>
							@else
							<td style="vertical-align: middle"><span class="label label-danger">{{ $contact->as_standing }}</span></td>
							@endif
							<td style="vertical-align: middle">{!! \Carbon\Carbon::parse($contact->updated_at) !!} : {!! \Carbon\Carbon::parse($contact->updated_at)->diffForHumans() !!} </td>
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

@stop

@section('scripts')


<script>

	$("#search").change(function () {
		$("#activity-form").submit();
	});

	$("#username").change(function () {
		$("#activity-form").submit();
	});

	$("#action").change(function () {
		$("#activity-form").submit();
	});

	$("#system").change(function () {
		$("#activity-form").submit();
	});


</script>
<script>

	var path1 = "{{ route('autocomplete.systems') }}";
	$('input.typeahead-systems').typeahead({
		source:  function (system, process) {
			return $.get(path1, { system: system }, function (data1) {
				return process(data1);
			});
		}
	});
</script>





@stop


