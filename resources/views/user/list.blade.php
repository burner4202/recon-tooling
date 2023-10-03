@extends('layouts.app')

@section('page-title', trans('app.users'))

@section('content')

@inject('ledger', 'Vanguard\Http\Controllers\Web\UsersController')

<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
			@lang('app.users')
			<small>@lang('app.list_of_registered_users')</small>
			<div class="pull-right">
				<ol class="breadcrumb">
					<li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
					<li class="active">@lang('app.users')</li>
				</ol>
			</div>

		</h1>
	</div>
</div>

@include('partials.messages')

<div class="row tab-search">
	<div class="col-md-2">
		<a href="{{ route('user.create') }}" class="btn btn-success" id="add-user">
			<i class="glyphicon glyphicon-plus"></i>
			@lang('app.add_user')
		</a>
	</div>
	<div class="col-md-5"></div>
	<form method="GET" action="" accept-charset="UTF-8" id="users-form">
		<div class="col-md-2">
			{!! Form::select('status', $statuses, Input::get('status'), ['id' => 'status', 'class' => 'form-control']) !!}
		</div>
		<div class="col-md-3">
			<div class="input-group custom-search-form">
				<input type="text" class="form-control" name="search" value="{{ Input::get('search') }}" placeholder="@lang('app.search_for_users')">
				<span class="input-group-btn">
					<button class="btn btn-default" type="submit" id="search-users-btn">
						<span class="glyphicon glyphicon-search"></span>
					</button>
					@if (Input::has('search') && Input::get('search') != '')
					<a href="{{ route('user.list') }}" class="btn btn-danger" type="button" >
						<span class="glyphicon glyphicon-remove"></span>
					</a>
					@endif
				</span>
			</div>
		</div>
	</form>
</div>

<div class="table-responsive top-border-table" id="users-table-wrapper">
	<table class="table" id="users">
		<thead>
			<th>@lang('app.username')</th>
			<th> Role </th>
			<th> Last Logged In </th>
			<th>@lang('app.registration_date')</th>
			<th>@lang('app.status')</th>
			<th class="text-center">@lang('app.action')</th>
		</thead>
		<tbody>
			@if (count($users))
			@foreach ($users as $user)
			<tr>
				<td><a href="{{ route('user.show', $user->id) }}">{{ $user->username ?: trans('app.n_a') }}</a></td>

				@foreach($roles as $role)
				@if($user->role_id == $role->id)
				<td> {{ $role->display_name }} </td>
				@endif
				@endforeach

				<td>{{ $user->present()->lastLogin() }}</td>

				<td>{{ $user->created_at->format(config('app.date_format')) }}</td>
				<td>
					<span class="label label-{{ $user->present()->labelClass }}">{{ trans("app.{$user->status}") }}</span>
				</td>

				<td class="text-center">

					@if (config('session.driver') == 'database')
					<a href="{{ route('user.sessions', $user->id) }}" class="btn btn-info btn-circle"
						title="@lang('app.user_sessions')" data-toggle="tooltip" data-placement="top">
						<i class="fa fa-list"></i>
					</a>
					@endif
					<a href="{{ route('user.show', $user->id) }}" class="btn btn-success btn-circle"
						title="@lang('app.view_user')" data-toggle="tooltip" data-placement="top">
						<i class="glyphicon glyphicon-eye-open"></i>
					</a>
					<a href="{{ route('user.edit', $user->id) }}" class="btn btn-primary btn-circle edit" title="@lang('app.edit_user')"
						data-toggle="tooltip" data-placement="top">
						<i class="glyphicon glyphicon-edit"></i>
					</a>
					<a href="{{ route('user.delete', $user->id) }}" class="btn btn-danger btn-circle" title="@lang('app.delete_user')"
						data-toggle="tooltip"
						data-placement="top"
						data-method="DELETE"
						data-confirm-title="@lang('app.please_confirm')"
						data-confirm-text="@lang('app.are_you_sure_delete_user')"
						data-confirm-delete="@lang('app.yes_delete_him')">
						<i class="glyphicon glyphicon-trash"></i>
					</a>
				</td>
			</tr>
			@endforeach
			@else
			<tr>
				<td colspan="6"><em>@lang('app.no_records_found')</em></td>
			</tr>
			@endif
		</tbody>
	</table>

	{!! $users->render() !!}
</div>

@stop

@section('scripts')
<script>
	$("#status").change(function () {
		$("#users-form").submit();
	});


	$(document).ready(function(){
		$('#users').DataTable( {
			"paging":   false,
			"searching": false,
			"order": [[ 0, "asc" ]]
		}
		);

	});

</script>

@stop

