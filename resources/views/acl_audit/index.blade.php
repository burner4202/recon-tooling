@extends('layouts.app')

@section('page-title', 'ACL Audit')

@section('content')


<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
			ACL Audit Tool
			<small>- Press button.</small>
			<div class="pull-right">
				<ol class="breadcrumb">
					<li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
					<li class="active">ACL Audit Tool</li>
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
	<div class="col-md-9">

		<div class="panel panel-default">
			<div class="panel-heading">Information / Summary</div>
			<div class="panel-body">
				<div class="col-md-12">

					<h5>This module of Recon Tools parses an ACL Access Log from the EVE client and checks the members to ensure they are a member of the Imperium via ESI Alliances/Standings.</h5><br>
					1. Begin by pasting in to 'Parse ACL Log' Input, the ACL Access Log.<br>
					2. The ACL is automatically identified and if existing records are found they will be updated.<br>
					3. Set the Name of the ACL by viewing the ACL.<br>
					4. ACLs are audited daily to ensure compilance.<br>
					5. Issues will be flagged accordingly.<br>

				</div>
			</div>

		</div>
	</div>




	<div class="col-md-3">
		<div class="panel panel-default">
			<div class="panel-heading">Parse ACL Log</div>
			<div class="panel-body">
				<div class="col-md-12">

					<form method="post" action="/acl/audit/post" enctype="multipart/form-data">
						{{ csrf_field() }}
						<div class="form-group row">
							<div class="col-sm-12">
								<label for="acl_characters">ACL Audit Log</label>
								<textarea name="acl_characters" type="text" class="form-control" id="dscan" placeholder="Add Log to Audit." rows="3"></textarea>
							</div>
						</div>
						<div class="form-group row">
							<div class="offset-sm-3 col-sm-9">
								<button type="submit" class="btn btn-primary">Submit</button>
							</div>
						</div>
					</form>

				</div>
			</div>
		</div>
	</div>

	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">ACL List</div>
			<div class="panel-body">
				<div class="col-md-12">

					<div class="table-responsive top-border-table" id="location-table-wrapper">

						<table class="table" id="previous-audits">
							<thead>
								<th> Name</th>
								<th> Last Audited By</th>
								<th> Administrators</th>
								<th> Managers</th>
								<th> Members</th>
								<th> Removed</th>
								<th> Blocked</th>
								<th> ACL Created</th>
								<th> ACL Imported</th>
								<th> Audit Last Updated</th>
							</thead>

							<tbody>


								@if (isset($acls))              
								@foreach($acls as $acl)

								<tr>
									@if($acl->acl_name =="")
									<td style="vertical-align: middle"><a href="{{ route('acl_audit.view', $acl->acl_hash)}}">I NEED A NAME!</a></td>
									@else
									<td style="vertical-align: middle"><a href="{{ route('acl_audit.view', $acl->acl_hash)}}">{!! $acl->acl_name !!}</a></td>
									@endif

									<td style="vertical-align: middle">{!! $acl->acl_added_by !!}</td>
									<td style="vertical-align: middle">{!! $acl_members->where('aclc_acl_hash', $acl->acl_hash)->where('aclc_role', 'admin')->where('aclc_state', 'added')->count() !!}</td>
									<td style="vertical-align: middle">{!! $acl_members->where('aclc_acl_hash', $acl->acl_hash)->where('aclc_role', 'manager')->where('aclc_state', 'added')->count() !!}</td>
									<td style="vertical-align: middle">{!! $acl_members->where('aclc_acl_hash', $acl->acl_hash)->where('aclc_role', 'member')->where('aclc_state', 'added')->count() !!}</td>
									<td style="vertical-align: middle">{!! $acl_members->where('aclc_acl_hash', $acl->acl_hash)->where('aclc_state', 'removed')->count() !!}</td>
									<td style="vertical-align: middle">{!! $acl_members->where('aclc_acl_hash', $acl->acl_hash)->where('aclc_role', 'blocked')->count() !!}</td>
									<td style="vertical-align: middle">{!! \Carbon\Carbon::parse($acl->acl_created_time)->format('d M Y') !!} : {!! \Carbon\Carbon::parse($acl->acl_created_time)->diffForHumans() !!}</td>
									<td style="vertical-align: middle">{!! \Carbon\Carbon::parse($acl->created_at)->format('d M Y') !!} : {!! \Carbon\Carbon::parse($acl->created_at)->diffForHumans() !!}</td>
									<td style="vertical-align: middle">{!! \Carbon\Carbon::parse($acl->updated_at)->format('d M Y') !!} : {!! \Carbon\Carbon::parse($acl->updated_at)->diffForHumans() !!}</td>

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

