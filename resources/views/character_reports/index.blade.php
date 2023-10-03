@extends('layouts.app')

@section('page-title', 'Character Reporting Database')

@section('content')

<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
			Character Reporting Database
			<div class="pull-right">
				<ol class="breadcrumb">
					<li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
					<li><a href="{{ route('intelligence.index') }}">Intelligence Dashboard</a></li>
					<li class="active">Character Reporting Database</li>
				</ol>
			</div>
		</h1>
	</div>
</div>

@include('partials.messages')
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				Character Reporting Database
				<div class="pull-right">
					<a href="{{ route('character.index')}}">Search or Add Character</a>
				</div>
			</div>
			<div class="panel-body">
				Search the database for all reports, of any character.
				<div class="pull-right col-md-2">
					{!! $reports->appends(\Request::except('reports'))->render() !!}

				</div>
			</div>
		</div>
	</div>
</div>


<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				Character Reporting Database

			</div>
			<div class="panel-body">
				<div class="col-md-3 pull-right">
					<form method="GET" action="" accept-charset="UTF-8" id="character-report">
						Search Everything
						<div class="input-group custom-search-form">
							<input type="text" class="form-control" name="search" value="{{ Input::get('search') }}" placeholder="Search Everything">
							<span class="input-group-btn">
								<button class="btn btn-default" type="submit" id="search-indices-btn">
									<span class="glyphicon glyphicon-search"></span>
								</button>
								@if (
									Input::has('search') && Input::get('search') != '' ||
									Input::has('character') && Input::get('character') != '' ||
									Input::has('hull_type') && Input::get('hull_type') != '' ||
									Input::has('alliance') && Input::get('alliance') != '')					
									<a href="{{ route('character_reporting.index') }}" class="btn btn-danger" system="button" >
										<span class="glyphicon glyphicon-remove"></span>
									</a>
									@endif
								</span>
							</div>
						</div>

						<div class="col-md-2">
							Character Name
							<input type="text" class="form-control" name="character" value="{{ Input::get('character') }}" placeholder="Character Name">
						</div>

						<div class="col-md-1">
							Hull Type
							{!! Form::select('hull_type', $hull_type, Input::get('hull_type'), ['id' => 'hull_type', 'class' => 'form-control']) !!}
						</div>

						<div class="col-md-2">
							Alliance
							{!! Form::select('alliance', $alliance, Input::get('alliance'), ['id' => 'alliance', 'class' => 'form-control']) !!}
						</div>



					</form>
				</div>

			</div>
		</div>

	</div>


</div>


<div class="col-md-12">
	<div class="panel panel-default">
		<div class="panel-heading">
			Character Reporting
		</div>
		<div class="panel-body">

			<div class="table-responsive" id="contracts-table-wrapper">
				<table class="table table-borderless table-striped">
					<thead>
						<tr>
							<th> @sortablelink('character_name', 'Character Name')</th>
							<th> @sortablelink('corporation_name', 'Corporation At The Time')</th>
							<th> @sortablelink('alliance_name', 'Suspect Alliance')</th>
							<th> @sortablelink('system_name', 'System')</th>
							<th> @sortablelink('region_name', 'Region')</th>
							<th> @sortablelink('hull_type', 'Hull')</th>
							<th> @sortablelink('ship_hull_id', 'Ship Hull ID')</th>
							<th> @sortablelink('created_at', 'Added')</th>
							<th> External Links </th>
						</tr>
					</thead>
					<tbody>
						@if (count($reports))
						@foreach ($reports as $character)
						<tr>

							<td style="vertical-align: middle"><a href="{{ route('character.view', $character->character_name) }}" target="_blank"><img class="img-circle" src="https://image.eveonline.com/Character/{{ $character->character_id}}_32.jpg">&nbsp;{{ $character->character_name }}</a></td>
							<td style="vertical-align: middle"><img class="img-circle" src="https://image.eveonline.com/Corporation/{{ $character->corporation_id }}_32.png">&nbsp;{{ $character->corporation_name }}</td>
							@if($character->alliance_id > 1)
							<td style="vertical-align: middle"><img class="img-circle" src="https://image.eveonline.com/Alliance/{{ $character->alliance_id}}_32.png">&nbsp;{{ $character->alliance_name }}</td>
							@else
							<td></td>
							@endif

							<td style="vertical-align: middle">{{ $character->system_name}}</td>
							<td style="vertical-align: middle">{{ $character->region_name}}</td>
							<td style="vertical-align: middle">{{ $character->hull_type}}</td>
							<td style="vertical-align: middle">{{ $character->ship_hull_id}}</td>
							<td style="vertical-align: middle">{{ $character->created_at }} : {{ $character->created_at->diffForHumans() }}</td>

							<td style="vertical-align: middle">
								<a href="https://zkillboard.com/character/{{ $character->character_id }}" target="_blank">
									<span class="label label-pill label-danger">
										Zkill
									</span>
								</a>

								<a href="https://evewho.com/pilot/{{ $character->character_name }}"  target="_blank">
									<span class="label label-pill label-success">
										Evewho
									</span>
								</a>
							</td>

						</tr>
						@endforeach
						@else
						<tr>
							<td colspan="7"><em>No Records Found</em></td>
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
		$("#character-report").submit();
	});

	$("#character").change(function () {
		$("#character-report").submit();
	});

	$("#alliance").change(function () {
		$("#character-report").submit();
	});

	$("#hull_type").change(function () {
		$("#character-report").submit();
	});

</script>


@stop




























