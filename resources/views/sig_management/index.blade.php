@extends('layouts.app')

@section('page-title', 'Sig Management')

@section('content')

<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
			Sig Management
			<small> - administration of Scouts Members</small>
			<div class="pull-right">
				<ol class="breadcrumb">
					<li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
					<li class="active">Sig Management</li>

				</ol>
			</div>
		</h1>
	</div>
</div>

@include('partials.messages')

<div class="col-md-12">

	<div class="col-md-3">
		{!! $scouts->appends(\Request::except('scouts'))->render() !!}
	</div>

	<div class="col-md-3 pull-right">
		<form method="GET" action="" accept-charset="UTF-8" id="scouts-form">
			Search Everything
			<div class="input-group custom-search-form">
				<input type="text" class="form-control" name="search" value="{{ Input::get('search') }}" placeholder="Search Everything">
				<span class="input-group-btn">
					<button class="btn btn-default" type="submit" id="search-scouts-btn">
						<span class="glyphicon glyphicon-search"></span>
					</button>
					@if (
						Input::has('search') && Input::get('search') != '' ||
						Input::has('security_status') && Input::get('security_status') != '')					
						<a href="{{ route('scouts.index') }}" class="btn btn-danger" system="button" >
							<span class="glyphicon glyphicon-remove"></span>
						</a>
						@endif
					</span>
				</div>
			</div>
		</form>
	</div>
</div>
<p></p>

	<div class="col-md-9">
		<div class="panel panel-default">
			<div class="panel-heading">Scouts</div>
			<div class="panel-body">

				<div class="table-responsive top-border-table" id="scouts-table-wrapper">

					<table class="table" id="scouts">
						<thead>
							<th>@sortablelink('name', 'Name')</th>
							<th>@sortablelink('check_in', 'Check In')</th>
							<th>@sortablelink('active', 'Active')</th>
							<th>@sortablelink('registered_on_rt', 'Registered on Recon Tools')</th>
						</thead>
						<tbody>

							@if (isset($scouts))              
							@foreach($scouts as $scout)

							<tr>
								@if($scout->registered_on_rt)
								<td style="vertical-align: middle"><a href="{{ route('user.show', $scout->user_id)}}">{{ $scout->name }}</a></td>
								@else
								<td style="vertical-align: middle">{{ $scout->name }}</td>
								@endif
								<td style="vertical-align: middle">{{ $scout->check_in }}</td>
								@if($scout->active)
								<td style="vertical-align: middle"><span class="glyphicon glyphicon-ok"></span></td>
								@else
								<td style="vertical-align: middle"></td>
								@endif
								@if($scout->registered_on_rt)
								<td style="vertical-align: middle"><span class="glyphicon glyphicon-ok"></span></td>
								@else
								<td style="vertical-align: middle"></td>
								@endif
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

	<div class="col-md-3">
		<div class="panel panel-default">
			<div class="panel-heading">Import Scouts

				<div class="pull-right" style="vertical-align:middle;">
					<span class="glyphicon glyphicon-info-sign fa-1x" data-toggle="tooltip" title="Import Sig Membership from Manager" data-placement="left"></span>
				</div>


			</div>
			<div class="panel-body">
				<form method="post" action="/sig/management/import/scouts" enctype="multipart/form-data">
					{{ csrf_field() }}
					<div class="form-group row">
						<div class="col-sm-12">
							<textarea name="title" type="text" class="form-control" id="data" placeholder="Name	Check-in	Last Mumble	Last Jabber	Last Forums	Fleet Ops (30d)	Fleet Ops (60d)	Fleet Ops (90d)
							The Mittani		2020 Apr 20	2020 Apr 22	2020 Apr 04	0	0	0" rows="30"></textarea>
						</div>
					</div>
					<div class="form-group row">
						<div class="offset-sm-3 col-sm-9">
							<button type="submit" class="btn btn-primary">Import</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>





@stop

@section('scripts')


<script>

	$("#search").change(function () {
		$("#scouts-form").submit();
	});

</script>


@stop




