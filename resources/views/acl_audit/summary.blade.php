<div class="row">
	<div class="col-md-12">

		<div class="col-md-4">
			<div id="edit-user-panel" class="panel panel-default">
				<div class="panel-heading">
					ACL Information
				</div>
				<div class="panel-body panel-profile">

					<table class="table table-hover">
						<thead>
							<tr>
								<th>Description</th>
								<th>Amount</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>Administrators</td>
								<td>{{ $acl_members->where('aclc_role', 'admin')->where('aclc_state', 'added')->count() }}</td>
							</tr>
							<tr>
								<td>Managers</td>
								<td>{{ $acl_members->where('aclc_role', 'manager')->where('aclc_state', 'added')->count() }}</td>
							</tr>
							<tr>
								<td>Members</td>
								<td>{{ $acl_members->where('aclc_role', 'member')->where('aclc_state', 'added')->count() }}</td>
							</tr>
							<tr>
								<td>Removed</td>
								<td>{{ $acl_members->where('aclc_state', 'removed')->count() }}</td>
							</tr>
							<tr>
								<td>Blocked</td>
								<td>{{ $acl_members->where('aclc_state', 'blocked')->count() }}</td>
							</tr>
							<tr>
								<td>ACL Created</td>
								<td>{{ $acl->acl_created_time }} : {{ \Carbon\Carbon::parse($acl->acl_created_time)->diffForHumans() }}</td>
							</tr>
							<tr>
								<td>Last Audit</td>
								<td>{{ $acl->updated_at }} : {{ $acl->updated_at->diffForHumans() }}</td>
							</tr>
							<tr>
								<td>Added By</td>
								<td>{{ $acl->acl_added_by }}</td>
							</tr>
							<tr>
								<td>Public</td>
								@if($acl->acl_public == 1)
								<td>Yes</td>
								@else
								<td>No</td>
								@endif
							</tr>

						</tbody>
						
					</table>
				</div>

			</div>
		</div>

		<div class="col-md-4">
			<div class="panel panel-default">
				<div class="panel-heading">ACL Information - Add Name to this ACL.</div>
				<div class="panel-body">
					<form method="post" action="{{ route('acl_audit.add_name', $acl->acl_hash)}}" enctype="multipart/form-data">
						{{ csrf_field() }}

						<div class="col-md-10">
							<input type="text" class="form-control" id="acl_name" name="acl_name" placeholder="ACL Name...">

						</div>

						<div class="col-md-2">
							<button type="submit" class="btn btn-primary">Submit </button>

						</div>


					</form>
				</div>
			</div>
		</div>

	</div>
</div>