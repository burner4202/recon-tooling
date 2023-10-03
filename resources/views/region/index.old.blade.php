@extends('layouts.app')

@section('page-title', 'Regional Reports')

@section('content')


<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
			Regions 
			<small>- select a region for its report.</small>
			<div class="pull-right">
				<ol class="breadcrumb">
					<li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
					<li class="active">Region Report Index</li>
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


	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">Regions</div>
			<div class="panel-body">
				<div class="col-md-12">

					<div class="table-responsive top-border-table" id="location-table-wrapper">

						<table class="table" id="regions">
							<thead>
								<th> Region Name</th>
								<th> Alive Structures</th>
								<th> Keepstar</th>
								<th> Sotiyo</th>
								<th> Capital Production</th>
								<th> Super Capital Production</th>
								<th> T2 Rigged</th>
								<th> Moon Drilling</th>
								<th> Packages Delivered</th>
								<th> Packages Not Delivered</th>
							</thead>

							<tbody>

								@if (isset($regions))              
								@foreach($regions as $region)

								<tr>
									<td style="vertical-align: middle"><a href="{{ route('regional.report.view', $region->str_region_name)}}"> {!! $region->str_region_name !!}</a></td>
									<td style="vertical-align: middle">{!! $structures_alive->where('str_region_name', $region->str_region_name)->count() !!}</td>
									<td style="vertical-align: middle">{!! $structures_alive->where('str_region_name', $region->str_region_name)->where('str_type', 'Keepstar')->count() !!}</td>
									<td style="vertical-align: middle">{!! $structures_alive->where('str_region_name', $region->str_region_name)->where('str_type', 'Sotiyo')->count() !!}</td>
									<td style="vertical-align: middle">{!! $structures_alive->where('str_region_name', $region->str_region_name)->where('str_capital_shipyard', 1)->count() !!}</td>
									<td style="vertical-align: middle">{!! $structures_alive->where('str_region_name', $region->str_region_name)->where('str_supercapital_shipyard', 1)->count() !!}</td>
									<td style="vertical-align: middle">{!! $structures_alive->where('str_region_name', $region->str_region_name)->where('str_t2_rigged', 1)->count() !!}</td>
									<td style="vertical-align: middle">{!! $structures_alive->where('str_region_name', $region->str_region_name)->where('str_moon_drilling', 1)->count() !!}</td>
									<td style="vertical-align: middle">{!! $structures_alive->where('str_region_name', $region->str_region_name)->where('str_package_delivered', '=', 'Package Delivered')->count() !!}</td>
									<td style="vertical-align: middle">{!! $structures_alive->where('str_region_name', $region->str_region_name)->where('str_package_delivered', '=', '')->count() !!}</td>
								</tr>

								@endforeach
								@else

								<tr>
									<td colspan="6"><em>No Records Found</em></td>
								</tr>

								@endif

								{!! $regions->render() !!}

							</tbody>

						</table>

					</div>
				</div>
			</div>
		</div>
	</div>

</div>





@stop

