@extends('layouts.app')

@section('page-title', 'Intelligence | Characters')

@section('content')

<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
			Character Import
			<small> - mass import of character data</small>
			<div class="pull-right">
				<ol class="breadcrumb">
					<li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
					<li><a href="{{ route('intelligence.index') }}">Intelligence Dashboard</a></li>
					<li>Character Import</li>
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
				Character Dashboard
			</div>
			<div class="panel-body">
				This section of the tools allows for the mass import of character reporting<br>
				Format should be Ship Info > Character Info
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-6">
		<div class="panel panel-default">
			<div class="panel-heading">Paste Character Meta Data
				<div class="pull-right" style="vertical-align:middle;">
					<span class="glyphicon glyphicon-info-sign fa-1x" data-toggle="tooltip" title="Dump Character Meta Data" data-placement="left"></span>
				</div>

			</div>
			<div class="panel-body">


				<form method="post" action="/intelligence/character/metadump" enctype="multipart/form-data">
					{{ csrf_field() }}

					<div class="form-group row">
						<div class="col-sm-6">
							<label for="system">Reported System *</label>
							<input type="text" class="typeahead-systems form-control" name="system" id="system" placeholder="A system is required to allocate the dump to." autocomplete="off" >
						</div>

						<div class="col-sm-6">
							<label for="alliance">Associated Alliance *</label>
							<input type="text" class="typeahead-alliances form-control" name="alliance" id="alliance" placeholder="An Alliance is required to allocate the dump to." autocomplete="off" >
						</div>
					</div>

					<div class="form-group row">
						<div class="col-sm-12">
							<label for="system">List of Character Meta Data *</label>
							<textarea name="title" type="text" class="form-control" id="data" placeholder="[18:20:46] scopehone > <url=showinfo:19720//1030502976551>Revelation</url>  <url=showinfo:1377//1455452920>Zalmithius</url>" rows="30"></textarea>
						</div>
					</div>

					<div class="form-group row">
						<div class="offset-sm-3 col-sm-9">
							<button type="submit" class="btn btn-primary">Submit</button>
						</div>
					</div>

					<div class="form-group row">
						<div class="col-sm-12">
							<label for="system">* Required Field</label>
						</div>
					</div>
				</form>


			</div>
		</div>
	</div>

</div>

@stop

@section('scripts')

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