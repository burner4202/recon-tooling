@extends('layouts.app')

@section('page-title', 'Active Users')

@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">
            Active Users
            <small>list of users with active sessions</small>
            <div class="pull-right">
                <ol class="breadcrumb">
                    <li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
                    <li class="active">Active Users</li>
                </ol>
            </div>

        </h1>
    </div>
</div>

@foreach($users as $user)
    <div class="user media">
        <div class="media-left">
            <a href="{{ route('user.show', $user->present()->id) }}">
                <img class="media-object img-circle avatar" src="{{ $user->present()->avatar }}">
            </a>
        </div>
        <div class="media-body">
            <h4 class="media-heading">{{ $user->present()->name }}</h4>
            {{ $user->email }}
        </div>
    </div>
@endforeach

@stop

@section('styles')
    <style>
        .user.media {
            float: left;
            border: 1px solid #dfdfdf;
            padding: 10px;
            border-radius: 4px;
            margin-right: 15px;
        }
        .user.media .media-object {
            width: 64px;
            height: 64px;
        }
        .user.media .media-body {
            width: 180px;
        }
    </style>
@stop