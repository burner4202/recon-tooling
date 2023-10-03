@extends('layouts.app')

@section('page-title', 'Coalitions')

@section('content')


<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
			Coalitions
			<small>- Press button.</small>
			<div class="pull-right">
				<ol class="breadcrumb">
					<li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
					<li><a href="{{ route('coalitions.list') }}">Coalitions</a></li>
					<li class="active">Coalitions Manage</li>
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
	<div class="col-md-3">
		<div class="panel panel-default">
			<div class="panel-heading">Add Coalition</div>
			<div class="panel-body">

				<form method="post" action="{{ route('coalitions.create')}}" enctype="multipart/form-data">
					{{ csrf_field() }}
					<div class="panel-body" >
						<div class="col-md-12">


							<div class="form-group">
								<label for="name">Name</label>
								<input type="text" class="form-control" name="name" id="name" placeholder="Coalition Name" autocomplete="off" >
							</div>

							<div class="form-group">
								<label for="notes">Coalition Notes</label>
								<textarea name="notes" type="text" class="form-control" id="notes" placeholder="Notes
								" rows="5"></textarea>
							</div>

						</div>
					</div>

					<div class="form-group row">
						<div style="text-align: center;">
							<button type="submit" class="btn btn-success">Add Coalition</button>
						</div>
					</div>
				</form>
			</div>					
		</div>
	</div>
	<div class="col-md-9">
		<div class="panel panel-default">
			<div class="panel-heading">Coalition List</div>
			<div class="panel-body">
				<div class="col-md-12">

					<div class="table-responsive top-border-table" id="location-table-wrapper">

						<table class="table" id="previous-audits">
							<thead>
								<th> Name</th>
								<th> Alliances</th>
								<th> Added By</th>
								<th> Created</th>
								<th> Last Updated</th>
								<th> Action</th>
							</thead>

							<tbody>


								@if (isset($coalitions))              
								@foreach($coalitions as $coalition)

								<tr>
									
									<td style="vertical-align: middle"><a href="{{ route('coalitions.view', $coalition->id) }}">{!! $coalition->name !!}</a></td>
									<td style="vertical-align: middle">{{ $alliances->where('alliance_coalition', $coalition->id)->count() }}</td>
									<td style="vertical-align: middle">{!! $coalition->added_by !!}</td>
									<td style="vertical-align: middle">{!! \Carbon\Carbon::parse($coalition->created_at)->format('d M Y') !!} : {!! \Carbon\Carbon::parse($coalition->created_at)->diffForHumans() !!}</td>
									<td style="vertical-align: middle">{!! \Carbon\Carbon::parse($coalition->updated_at)->format('d M Y') !!} : {!! \Carbon\Carbon::parse($coalition->updated_at)->diffForHumans() !!}</td>
									<td style="vertical-align: middle">
										<a href="{{ route('coalitions.delete', $coalition->id)}}" class="btn btn-danger btn-circle" title="Remove Coalition"
											data-toggle="tooltip"
											data-placement="right"
											data-method="DELETE"
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

