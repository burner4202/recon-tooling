@extends('layouts.app')

@section('page-title', 'Fleet Commander Hunter')

@section('content')

<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
			Fleet Commander Hunter
			<div class="pull-right">
				<ol class="breadcrumb">
					<li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
					<li><a href="{{ route('intelligence.index') }}">Intelligence Dashboard</a></li>
					<li class="active">Fleet Commander Hunter</li>
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
				Fleet Commander Hunter
			</div>
			<div class="panel-body">
				If your on this list and your a hostile, tough fucking shit.<p>
				If no standings exist, you should consider having a diplomat update alliance standings.</p>
			</div>
		</div>
	</div>
</div>


<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				Fleet Commander Hunter
			</div>
			<div class="panel-body">
				<div class="col-md-3 pull-right">
					<form method="GET" action="" accept-charset="UTF-8" id="fc-hunter-form">
						Search Everything
						<div class="input-group custom-search-form">
							<input type="text" class="form-control" name="search" value="{{ Input::get('search') }}" placeholder="Search Everything">
							<span class="input-group-btn">
								<button class="btn btn-default" type="submit" id="search-indices-btn">
									<span class="glyphicon glyphicon-search"></span>
								</button>
								@if (
									Input::has('search') && Input::get('search') != '' ||
									Input::has('standings') && Input::get('standings') != '' ||
									Input::has('alliance') && Input::get('alliance') != '')					
									<a href="{{ route('public_contracts.index') }}" class="btn btn-danger" system="button" >
										<span class="glyphicon glyphicon-remove"></span>
									</a>
									@endif
								</span>
							</div>
						</div>

						<div class="col-md-1">
							Standings
							{!! Form::select('standings', $standings, Input::get('standings'), ['id' => 'standings', 'class' => 'form-control']) !!}
						</div>

						<div class="col-md-2">
							Alliance
							{!! Form::select('alliance', $alliance, Input::get('alliance'), ['id' => 'alliance', 'class' => 'form-control']) !!}
						</div>

						<div class="col-md-2">
							{!! $fleet_commanders->appends(\Request::except('contracts'))->render() !!}
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
			Fleet Commanders
		</div>
		<div class="panel-body">

			<div class="table-responsive" id="contracts-table-wrapper">
				<table class="table table-borderless table-striped">
					<thead>
						<tr>
							<th> @sortablelink('character_name', 'Character Name')</th>
							<th> @sortablelink('character_corporation_name', 'Corporation')</th>
							<th> @sortablelink('character_alliance_name', 'Alliance')</th>
							<th> @sortablelink('as_standing', 'Standings')</th>
							<th> External Links </th>
						</tr>
					</thead>
					<tbody>
						@if (count($fleet_commanders))
						@foreach ($fleet_commanders as $fc)
						<tr>

							<td style="vertical-align: middle"><a href="{{ route('character.view', $fc->character_name) }}" target="_blank"><img class="img-circle" src="https://image.eveonline.com/Character/{{ $fc->character_character_id}}_32.jpg">&nbsp;{{ $fc->character_name }}</a></td>
							<td style="vertical-align: middle"><img class="img-circle" src="https://image.eveonline.com/Corporation/{{ $fc->character_corporation_id }}_32.png">&nbsp;{{ $fc->character_corporation_name }}</td>
							@if($fc->character_alliance_id > 1)
							<td style="vertical-align: middle"><img class="img-circle" src="https://image.eveonline.com/Alliance/{{ $fc->character_alliance_id}}_32.png">&nbsp;{{ $fc->character_alliance_name }}</td>
							@else
							<td></td>
							@endif

							@if($fc->as_standing <= 10 && $fc->as_standing >= 5)
							<td style="vertical-align: middle"><span class="label label-primary">{{ $fc->as_standing }}</span></td>
							@elseif($fc->as_standing <= 5 && $fc->as_standing >= 0)
							<td style="vertical-align: middle"><span class="label label-info">{{ $fc->as_standing }}</span></td>
							@elseif($fc->as_standing <= 0 && $fc->as_standing >= -5)
							<td style="vertical-align: middle"><span class="label label-warning">{{ $fc->as_standing }}</span></td>
							@else
							<td style="vertical-align: middle"><span class="label label-danger">{{ $fc->as_standing }}</span></td>
							@endif

							<td style="vertical-align: middle">
								<a href="https://zkillboard.com/character/{{ $fc->character_character_id }}" target="_blank">
									<span class="label label-pill label-danger">
										Zkill
									</span>
								</a>

								<a href="https://evewho.com/pilot/{{ $fc->character_name }}"  target="_blank">
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
		$("#fc-hunter-form").submit();
	});

	$("#standings").change(function () {
		$("#fc-hunter-form").submit();
	});

	$("#alliance").change(function () {
		$("#fc-hunter-form").submit();
	});

</script>


@stop




























