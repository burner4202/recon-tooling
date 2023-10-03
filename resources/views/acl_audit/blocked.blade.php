<div class="row col-md-12">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">Alliances</div>
			<div class="panel-body">

				<table class="table" id="alliances">
					<thead>
						<th> Name</th>
					</thead>

					<tbody>


						@if (isset($acl_members))              
						@foreach($acl_members as $member)

						@if($member->aclc_role == 'blocked')

						@if($member->aclc_member_type == 'alliance')
						<tr>
							@include('acl_audit.partials_alliance')
						</tr>
						@endif

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
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">Corporations</div>
			<div class="panel-body">

				<table class="table" id="corporations">
					<thead>
						<th> Name</th>
						<th> Corporation</th>
					</thead>

					<tbody>


						@if (isset($acl_members))              
						@foreach($acl_members as $member)

						@if($member->aclc_role == 'blocked')

						@if($member->aclc_member_type == 'corporation')
						<tr>
							@include('acl_audit.partials_corporation')
						</tr>
						@endif

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
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">Characters</div>
			<div class="panel-body">

				<table class="table" id="characters">
					<thead>
						<th> Name</th>
						<th> Corporation</th>
						<th> Alliance</th>
					</thead>

					<tbody>


						@if (isset($acl_members))              
						@foreach($acl_members as $member)

						@if($member->aclc_role == 'blocked')

						@if($member->aclc_member_type == 'character')
						<tr>
							@include('acl_audit.partials_character')
						</tr>
						@endif

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