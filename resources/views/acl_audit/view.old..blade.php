@extends('layouts.app')

@section('page-title', 'ACL Audit')

@section('content')


<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
			ACL Audit
			<small>- {!! $acl->acl_name !!}</small>
			<div class="pull-right">
				<ol class="breadcrumb">
					<li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
					<li><a href="{{ route('acl_audit.index') }}">ACL Audit</a></li>
					<li class="active">{!! $acl->acl_hash !!}</li>
				</ol>
			</div>
		</h1>
	</div>
</div>


@include('partials.messages')

@if($acl->acl_public == 1)
<div class="alert alert-warning" role="alert">
	Warning: This ACL has been set it public.
</div>
@endif


<div class="row col-md-12">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">ACL Information - Add Name to this ACL.</div>
			<div class="panel-body">
				<form method="post" action="{{ route('acl_audit.add_name', $acl->acl_hash)}}" enctype="multipart/form-data">
					{{ csrf_field() }}

					<div class="col-md-3">
						<input type="text" class="form-control" id="acl_name" name="acl_name" placeholder="ACL Name...">

					</div>

					<div class="col-md-1">
						<button type="submit" class="btn btn-primary">Submit </button>

					</div>


				</form>
			</div>
		</div>
	</div>
</div>

<div class="row col-md-12">
	<div class="col-md-3">
		<div class="panel panel-default">
			<div class="panel-heading">Administrators</div>
			<div class="panel-body">

				<table class="table" id="administrators">
					<thead>
						<th> Name</th>
						<th> Corporation</th>
						<th> Alliance</th>
					</thead>

					<tbody>


						@if (isset($acl_members))              
						@foreach($acl_members as $member)

						@if($member->aclc_role == 'admin' && $member->aclc_state == 'added')

						@include('acl_audit.partials')

						@endif

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
	<div class="col-md-3">
		<div class="panel panel-default">
			<div class="panel-heading">Managers</div>
			<div class="panel-body">

				<table class="table" id="managers">
					<thead>
						<th> Name</th>
						<th> Corporation</th>
						<th> Alliance</th>
					</thead>

					<tbody>


						@if (isset($acl_members))              
						@foreach($acl_members as $member)

						@if($member->aclc_role == 'manager' && $member->aclc_state == 'added')

						@include('acl_audit.partials')

						@endif

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

	<div class="col-md-3">
		<div class="panel panel-default">
			<div class="panel-heading">Members</div>
			<div class="panel-body">

				<div class="panel-body">

					<table class="table" id="members">
						<thead>
							<th> Name</th>
							<th> Corporation</th>
							<th> Alliance</th>
						</thead>

						<tbody>


							@if (isset($acl_members))              
							@foreach($acl_members as $member)

							@if($member->aclc_role == 'member' && $member->aclc_state == 'added' || $member->aclc_role == 'member' && $member->aclc_state == 'changed')

							@include('acl_audit.partials')

							@endif

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
			<div class="panel-heading">Removed</div>
			<div class="panel-body">

				<div class="panel-body">

					<table class="table" id="removed">
						<thead>
							<th> Name</th>
							<th> Corporation</th>
							<th> Alliance</th>
						</thead>

						<tbody>


							@if (isset($acl_members))              
							@foreach($acl_members as $member)

							@if($member->aclc_state == 'removed')

							@include('acl_audit.partials')

							@endif

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
			<div class="panel-heading">Blocked</div>
			<div class="panel-body">

				<div class="panel-body">

					<table class="table" id="blocked">
						<thead>
							<th> Name</th>
							<th> Corporation</th>
							<th> Alliance</th>
						</thead>

						<tbody>


							@if (isset($acl_members))              
							@foreach($acl_members as $member)

							@if($member->aclc_role == 'blocked')

							@include('acl_audit.partials')

							@endif

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

<div class="row col-md-12">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">Event Log</div>
			<div class="panel-body">


			</div>
		</div>
	</div>
</div>





@stop

