@extends('layouts.app')

@section('page-title', 'EVE Token')

@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">
            EVE Token
            <small>@lang('app.list_of_registered_users')</small>
            <div class="pull-right">
                <ol class="breadcrumb">
                    <li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
                    <li class="active">Add Token</li>
                </ol>
            </div>

        </h1>
    </div>
</div>

@include('partials.messages')

<div class="row tab-search">
    <div class="col-md-2">
        <a href="{{ route('sso.login') }}" class="btn btn-success" id="add-token">
            <i class="glyphicon glyphicon-plus"></i>
            Add Token
        </a>
    </div>
</div>

@stop

