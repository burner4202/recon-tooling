@extends('layouts.app')

@section('page-title', 'Jump Freighter Hunter')

@section('content')

<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
			Jump Freighter Hunter
			<div class="pull-right">
				<ol class="breadcrumb">
					<li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
					<li><a href="{{ route('intelligence.index') }}">Intelligence Dashboard</a></li>
					<li class="active">Jump Freighters Hunter</li>
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
				Jump Freighter Hunter
			</div>
			<div class="panel-body">
				A list of scouted jump freighters along with some intel.. add as you please.


				<div class="col-md-3 pull-right">
					<form method="GET" action="" accept-charset="UTF-8" id="jf-hunter-form">
						<div class="input-group custom-search-form">
							<input type="text" class="form-control" name="search" value="{{ Input::get('search') }}" placeholder="Search Everything">
							<span class="input-group-btn">
								<button class="btn btn-default" type="submit" id="search-indices-btn">
									<span class="glyphicon glyphicon-search"></span>
								</button>
								@if (
									Input::has('search') && Input::get('search') != '')					
									<a href="{{ route('jump_freighter.index') }}" class="btn btn-danger" system="button" >
										<span class="glyphicon glyphicon-remove"></span>
									</a>
									@endif
								</span>
							</div>
						</div>
					</form>
				</div>
			</div>


		</div>
	</div>

	<div class="col-md-6">
		{!! $jump_freighters->appends(\Request::except('jump_freighters'))->render() !!}
	</div>
	
</div>





<div class="col-md-12">
	<div class="panel panel-default">
		<div class="panel-heading">
			Jump Freighter Characters
		</div>
		<div class="panel-body">

			<div class="table-responsive" id="contracts-table-wrapper">
				<table class="table table-borderless table-striped">
					<thead>
						<tr>
							<th> @sortablelink('character_name', 'Character Name')</th>
							<th> @sortablelink('character_corporation_name', 'Corporation')</th>
							<th> @sortablelink('character_alliance_name', 'Alliance')</th>
							<th> External Links </th>
						</tr>
					</thead>
					<tbody>
						@if (count($jump_freighters))
						@foreach ($jump_freighters as $jf)
						<tr>

							<td style="vertical-align: middle"><a href="{{ route('character.view', $jf->character_name) }}" target="_blank"><img class="img-circle" src="https://image.eveonline.com/Character/{{ $jf->character_character_id}}_32.jpg">&nbsp;{{ $jf->character_name }}</a></td>
							<td style="vertical-align: middle"><img class="img-circle" src="https://image.eveonline.com/Corporation/{{ $jf->character_corporation_id }}_32.png">&nbsp;{{ $jf->character_corporation_name }}</td>
							@if($jf->character_alliance_id > 1)
							<td style="vertical-align: middle"><img class="img-circle" src="https://image.eveonline.com/Alliance/{{ $jf->character_alliance_id}}_32.png">&nbsp;{{ $jf->character_alliance_name }}</td>
							@else
							<td></td>
							@endif

							<td style="vertical-align: middle">
								<a href="https://zkillboard.com/character/{{ $jf->character_character_id }}" target="_blank">
									<span class="label label-pill label-danger">
										Zkill
									</span>
								</a>

								<a href="https://evewho.com/pilot/{{ $jf->character_name }}"  target="_blank">
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

</script>


@stop




























