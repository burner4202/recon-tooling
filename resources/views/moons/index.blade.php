@extends('layouts.app')

@section('page-title', 'Moon Scans')

@section('content')


<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
			Moon Scans
			<small>- moon scanning.</small>
			<div class="pull-right">
				<ol class="breadcrumb">
					<li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
					<li class="active">Moon Scans</li>
				</ol>
			</div>
		</h1>
	</div>
</div>


@include('partials.messages')


<div class="row tab-search">
	<div class="col-md-5"></div>
</div>


<div class="col-md-9">
	<div class="table-responsive top-border-table" id="moon-table-wrapper">

		<table class="table" id="moons">
			<thead>
				<th> @sortablelink('moon_name', 'Moon Name')</th>
				<th> @sortablelink('moon_system_name', 'System Name')</th>
				<th> @sortablelink('moon_product', 'Product')</th>
				<th> @sortablelink('moon_quantity', 'Distribution %')</th>
				<th> @sortablelink('updated_at', 'Updated At')</th>

			</thead>
			<tbody>

				@if (isset($moons))              
				@foreach($moons as $moon)

				<tr>
					<td style="vertical-align: middle"><a href="#">{{ $moon->moon_name }}</a></td>
					<td style="vertical-align: middle"><a href="#">{{ $moon->moon_system_name }}</a></td>
					<td style="vertical-align: middle"><img class="img-circle" src="https://image.eveonline.com/Type/{{ $moon->moon_ore_type_id }}_32.png">&nbsp;{{ $moon->moon_product  }}</td>
					<td style="vertical-align: middle">{{ $moon->moon_quantity * 100}} %</td>
					<td style="vertical-align: middle">{{ $moon->updated_at }}</td>
				</tr>

				@endforeach
				@else

				<tr>
					<td colspan="6"><em>No Records Found</em></td>
				</tr>

				@endif

			</tbody>
		</table>

		{!! $moons->appends(\Request::except('page'))->render() !!}
	</div>
</div>

<div class="col-md-3">
	<div class="panel panel-default">
		<div class="panel-heading">Add Moon Scan
		</div>
		<div class="panel-body">


			<form method="post" action="/moons/2020/import/dscan/post" enctype="multipart/form-data">
				{{ csrf_field() }}
				<div class="form-group row">
					<div class="col-sm-12">
						<textarea name="dscan" type="text" class="form-control" id="dscan" placeholder="Paste It Baby" rows="15"></textarea>
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

<div class="col-md-3">
	<div class="panel panel-default">
		<div class="panel-heading">ADASH Import (Max 1000 Entries Please)</div>
		<div class="panel-body">


			<form method="post" action="/moons/2020/import/adash_import/post" enctype="multipart/form-data">
				{{ csrf_field() }}
				<div class="form-group row">
					<div class="col-sm-12">
						<textarea name="adash_import" type="text" class="form-control" id="adash_import" placeholder="Paste It Baby" rows="15"></textarea>
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

@stop
