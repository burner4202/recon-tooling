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
<div class="alert alert-danger" role="alert">
	<b>Warning: This ACL has been set it public.</b>
</div>
@endif

<ul class="nav nav-tabs">
	<li class="active"><a data-toggle="tab" href="#summary">Summary</a></li>
	<li><a data-toggle="tab" href="#administrators">Administrators</a></li>
	<li><a data-toggle="tab" href="#managers">Managers</a></li>
	<li><a data-toggle="tab" href="#members">Members</a></li>
	<li><a data-toggle="tab" href="#removed">Removed</a></li>
	<li><a data-toggle="tab" href="#blocked">Blocked</a></li>
</ul>

<div class="tab-content">
	<div id="summary" class="tab-pane fade in active">

		@include('acl_audit.summary')
		
	</div>

	<div id="administrators" class="tab-pane fade">
		@include('acl_audit.administrators')

	</div>
	<div id="managers" class="tab-pane fade">

		@include('acl_audit.managers')
	</div>

	<div id="members" class="tab-pane fade">
		
		@include('acl_audit.members')

	</div>

	<div id="removed" class="tab-pane fade">
		
		@include('acl_audit.removed')

	</div>

	<div id="blocked" class="tab-pane fade">

		@include('acl_audit.blocked')

	</div>

	<div id="upwell-structures" class="tab-pane fade">



	</div>

	<div id="modules-rigs" class="tab-pane fade">



	</div>

	<div id="alliance-corporation" class="tab-pane fade">




	</div>

	<div id="standings" class="tab-pane fade">
		


	</div>

	<div id="task-manager" class="tab-pane fade">
		


	</div>

	<div id="capital-tracking" class="tab-pane fade">
		
		

	</div>
</div>




@stop