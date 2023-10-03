@extends('layouts.app')

@section('page-title', 'Observation')

@section('content')

<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
			Observation
			<small> - {{ $observation->unique_id }} </small>
			<div class="pull-right">
				<ol class="breadcrumb">
					<li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
					<li><a href="{{ route('observation.list') }}">Observation Manager</a></li>
					<li class="active">{{ $observation->unique_id }}</li>
				</ol>
			</div>

		</h1>
	</div>
</div>

@include('partials.messages')

<div class="row">

	<div class="col-md-3">
		<div class="panel panel-default">
			<div class="panel-heading">Review Observation</div>
			<div class="panel-body">

				<form method="post" action="{{ route('observation.reviewed', $observation->unique_id) }}" enctype="multipart/form-data">
					{{ csrf_field() }}
					<div class="panel-body" >
						<div class="col-md-12">

							{{ Form::hidden('observation_id', $observation->unique_id) }}

							<div class="form-group">
								<label for="solar_system_name">System</label>
								<input type="text" class="form-control typeahead-systems" name="solar_system_name" placeholder="If the observation is related to a system, add it." autocomplete="off">
							</div>

							<div class="form-group">
								<label for="alliance_name">Alliance</label>
								<input type="text" class="form-control typeahead-alliances" name="alliance_name" placeholder="If the observation is related to an alliance, add it." autocomplete="off">
							</div>

							<div class="form-group">
								<label for="corporation_name">Corporation Name</label>
								<input type="text" class="form-control typeahead-corporations" name="corporation_name" placeholder="If the observation is related to a corporation, add it." autocomplete="off">
							</div>

							<div class="form-group">
								<label for="prority">Prority</label>
								{!! Form::select('prority', ['1' => 'Useless', '2' => 'Low', '3' => 'High', '4' => 'Urgent'], Input::get('prority'), ['id' => 'prority', 'class' => 'form-control']) !!}
							</div>

							<div class="form-group">
								<label for="score">Score</label>
								{!! Form::select('score', ['1' => 'Awesome','2' => 'Good','3' => 'Meh', '4' => 'Pure Shit'], Input::get('score'), ['id' => 'score', 'class' => 'form-control']) !!}
							</div>
						</div>
					</div>

					<div class="form-group row">
						<div style="text-align: center;">
							<button type="submit" class="btn btn-success">Reviewed</button>
						</div>
					</div>
				</form>
				
			</div>					
		</div>
	</div>

	<div class="col-md-9">
		<div id="edit-user-panel" class="panel panel-default">
			<div class="panel-heading">
				Observation Information for - {{ $observation->unique_id }} created by <b>{{ $observation->created_by_username }}</b>
			</div>
			<div class="panel panel-body">

				{!! $observation->observation !!}

			</div>

		</div>
	</div>

	
</div>

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