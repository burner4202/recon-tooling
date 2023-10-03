@extends('layouts.app')

@section('page-title', 'Fittings')

@section('content')


<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
			Fittings
			<small>- Add a fitting from EVE by using EFT parse.</small>
			<div class="pull-right">
				<ol class="breadcrumb">
					<li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
					<li class="active">Fittings</li>
				</ol>
			</div>
		</h1>
	</div>
</div>


@include('partials.messages')


<div class="row tab-search">
	<div class="col-md-5"></div>
</div>

<div class="row col-md-12">
	<div class="col-md-3">
		<div class="panel panel-default">
			<div class="panel-heading">Add EFT Fitting.</div>
			<div class="panel-body">
				<div class="col-md-12">

					<form method="post" action="/fittings/post" enctype="multipart/form-data">
						{{ csrf_field() }}
						<div class="form-group row">
							<div class="col-sm-12">
								<label for="fitting">EFT Fitting</label>
								<textarea name="fitting" type="text" class="form-control" id="dscan" placeholder="Add Fitting from Ingame, EFT." rows="27"></textarea>
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

	<div class="col-md-9">
		<div class="panel panel-default">
			<div class="panel-heading">Fittings</div>
			<div class="panel-body">
				<div class="col-md-12">

					<div class="table-responsive top-border-table" id="location-table-wrapper">

						<table class="table" id="previous-kills">
							<thead>
								<th> @sortablelink('fitting_hull_name', 'Hull')</th>
								<th> @sortablelink('fitting_name', 'Fitting Name')</th>
								<th> @sortablelink('fitting_hull_value', 'Hull Value')</th>
								<th> @sortablelink('fitting_value', 'Total Value')</th>
								<th> @sortablelink('fitting_added_by', 'Added By')</th>
								<th> @sortablelink('created_at', 'Created')</th>
							</thead>

							<tbody>

								@if (isset($fittings))              
								@foreach($fittings as $fitting)

								<tr>
									<td><img class="img-circle" src="https://imageserver.eveonline.com/Type/{{ $fitting->fitting_hull_type_id }}_32.png">&nbsp;{{ $fitting->fitting_hull_name }}</a></td>
									<td style="vertical-align: middle"><a href="{{ route('fittings.view', $fitting->id )}}">{!! $fitting->fitting_name !!}</a></td>
									<td style="vertical-align: middle">{!! number_format($fitting->fitting_hull_value,2) !!}</td>
									<td style="vertical-align: middle">{!! number_format($fitting->fitting_value,2) !!}</td>
									<td style="vertical-align: middle">{!! $fitting->fitting_added_by !!}</td>
									<td style="vertical-align: middle">{!! \Carbon\Carbon::parse($fitting->created_at)->diffForHumans() !!} </td>

								</tr>

								@endforeach
								@else

								<tr>
									<td colspan="6"><em>No Records Found</em></td>
								</tr>

								@endif


								{!! $fittings->render() !!}

							</tbody>

						</table>

					</div>
				</div>
			</div>
		</div>
	</div>

</div>





@stop

