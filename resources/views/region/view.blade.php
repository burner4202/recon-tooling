@extends('layouts.app')

@section('page-title', 'Regional Report')

@section('content')


<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
			{!! $region_name !!} Report 
			<small>- information all about {!! $region_name !!}</small>
			<div class="pull-right">
				<ol class="breadcrumb">
					<li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
					<li><a href="{{ route('regional.report.index') }}">Regional Reports</a></li>
					<li class="active">{!! $region_name !!}</li>
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
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">Region Report</div>
			<div class="panel-body">
				<div class="col-md-12">
					Information provided under this regional report, is a summary of aggregated data within the structure database & other various sources. If you require up to date information please contact Recon Directors.<br>
					There is <a href="{{ route('structures.index')}}?region={!! $region_name !!}&no_per_page=1000&sort=str_value&direction=desc" target="_blank"><b>{!! $total_structures !!}</b></a> known structures in this region. The oldest structure in this region was updated <b>{!! $oldest_structure->updated_at->diffForHumans() !!}</b>.<br>
					

				</div>
			</div>
		</div>
	</div>

	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">Systems of Interest, Based on Daily System Indexes.</div>
			<div class="panel-body">
				<div class="col-md-2">

					<div class="table-responsive top-border-table" id="location-table-wrapper">

						<table class="table" id="manufacturing">
							<thead>
								<th> System Name</th>
								<th> Manufacturing</th>
							</thead>

							<tbody>

								@if (isset($manufacturing))             
								@foreach($manufacturing as $man)


								<tr>
									<td style="vertical-align: middle"><a target="_blank" href="{{ route('solar.system', $man->sci_solar_system_id)}}">{!! $man->sci_solar_system_name !!}</a></td>
									<td style="vertical-align: middle">{!! number_format($man->sci_manufacturing * 100 ,2)  !!}%</td>
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

				<div class="col-md-2">

					<div class="table-responsive top-border-table" id="location-table-wrapper">

						<table class="table" id="researching_te">
							<thead>
								<th> System Name</th>
								<th> Research TE</th>
							</thead>

							<tbody>

								@if (isset($researching_te))             
								@foreach($researching_te as $man)


								<tr>
									<td style="vertical-align: middle"><a target="_blank" href="{{ route('solar.system', $man->sci_solar_system_id)}}">{!! $man->sci_solar_system_name !!}</a></td>
									<td style="vertical-align: middle">{!! number_format($man->sci_researching_time_efficiency * 100 ,2)  !!}%</td>
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

				<div class="col-md-2">

					<div class="table-responsive top-border-table" id="location-table-wrapper">

						<table class="table" id="researching_me">
							<thead>
								<th> System Name</th>
								<th> Research ME</th>
							</thead>

							<tbody>

								@if (isset($researching_me))             
								@foreach($researching_me as $man)


								<tr>
									<td style="vertical-align: middle"><a target="_blank" href="{{ route('solar.system', $man->sci_solar_system_id)}}">{!! $man->sci_solar_system_name !!}</a></td>
									<td style="vertical-align: middle">{!! number_format($man->sci_researching_material_efficiency * 100 ,2)  !!}%</td>
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

				<div class="col-md-2">

					<div class="table-responsive top-border-table" id="location-table-wrapper">

						<table class="table" id="copying">
							<thead>
								<th> System Name</th>
								<th> Copying</th>
							</thead>

							<tbody>

								@if (isset($copying))             
								@foreach($copying as $man)


								<tr>
									<td style="vertical-align: middle"><a target="_blank" href="{{ route('solar.system', $man->sci_solar_system_id)}}">{!! $man->sci_solar_system_name !!}</a></td>
									<td style="vertical-align: middle">{!! number_format($man->sci_copying * 100 ,2)  !!}%</td>
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

				<div class="col-md-2">

					<div class="table-responsive top-border-table" id="location-table-wrapper">

						<table class="table" id="invention">
							<thead>
								<th> System Name</th>
								<th> Invention</th>
							</thead>

							<tbody>

								@if (isset($invention))             
								@foreach($invention as $man)


								<tr>
									<td style="vertical-align: middle"><a target="_blank" href="{{ route('solar.system', $man->sci_solar_system_id)}}">{!! $man->sci_solar_system_name !!}</a></td>
									<td style="vertical-align: middle">{!! number_format($man->sci_invention * 100 ,2)  !!}%</td>
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

				<div class="col-md-2">

					<div class="table-responsive top-border-table" id="location-table-wrapper">

						<table class="table" id="reactions">
							<thead>
								<th> System Name</th>
								<th> Reactions</th>
							</thead>

							<tbody>

								@if (isset($reactions))             
								@foreach($reactions as $man)


								<tr>
									<td style="vertical-align: middle"><a target="_blank" href="{{ route('solar.system', $man->sci_solar_system_id)}}">{!! $man->sci_solar_system_name !!}</a></td>
									<td style="vertical-align: middle">{!! number_format($man->sci_reaction * 100 ,2)  !!}%</td>
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

	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">Key Structures - Maximum 10 Structures, Sorted by Value.</div>
			<div class="panel-body">
				<div class="col-md-4">
					<div class="table-responsive top-border-table" id="location-table-wrapper">

						<table class="table" id="manufacturing">
							<thead>
								<th> Capital Production </th>
								<th> Alliance </th>
								<th> System </th>
								<th> Fitting Value </th>
							</thead>

							<tbody>

								@if (isset($capital_production))             
								@foreach($capital_production as $man)


								<tr>
									<td style="vertical-align: middle"><a target="_blank" href="{{ route('structures.view', $man->str_structure_id_md5)}}">{!! $man->str_name !!}</a></td>
									@if($man->str_owner_alliance_id > 1)
									<td style="vertical-align: middle"><a href="{{ route('alliance.view', $man->str_owner_alliance_id )}}"><img class="img-circle" src="https://images.evetech.net/alliances/{{ $man->str_owner_alliance_id }}/logo?size=32">&nbsp;{{ $man->str_owner_alliance_name }}</a></td>
									@else
									<td></td>
									@endif
									<td style="vertical-align: middle"><a target="_blank" href="{{ route('solar.system', $man->str_system_id)}}">{!! $man->str_system !!}</a></td>
									<td style="vertical-align: middle">{!! number_format($man->str_value,2) !!}</td>
								</tr>


								@endforeach
								@else

								<tr>
									<td colspan="6"><em>No Capital Production Found In This Region</em></td>
								</tr>

								@endif

							</tbody>

						</table>

					</div>
				</div>

				<div class="col-md-4">
					<div class="table-responsive top-border-table" id="location-table-wrapper">

						<table class="table" id="manufacturing">
							<thead>
								<th> Super Capital Production </th>
								<th> Alliance </th>
								<th> System </th>
								<th> Fitting Value </th>
							</thead>

							<tbody>

								@if (isset($super_capital_production))             
								@foreach($super_capital_production as $man)


								<tr>
									<td style="vertical-align: middle"><a target="_blank" href="{{ route('structures.view', $man->str_structure_id_md5)}}">{!! $man->str_name !!}</a></td>
									@if($man->str_owner_alliance_id > 1)
									<td style="vertical-align: middle"><a href="{{ route('alliance.view', $man->str_owner_alliance_id )}}"><img class="img-circle" src="https://images.evetech.net/alliances/{{ $man->str_owner_alliance_id }}/logo?size=32">&nbsp;{{ $man->str_owner_alliance_name }}</a></td>
									@else
									<td></td>
									@endif
									<td style="vertical-align: middle"><a target="_blank" href="{{ route('solar.system', $man->str_system_id)}}">{!! $man->str_system !!}</a></td>
									<td style="vertical-align: middle">{!! number_format($man->str_value,2) !!}</td>
								</tr>


								@endforeach
								@else

								<tr>
									<td colspan="6"><em>No Super Capital Production Found In This Region</em></td>
								</tr>

								@endif

							</tbody>

						</table>

					</div>
				</div>

				<div class="col-md-4">
					<div class="table-responsive top-border-table" id="location-table-wrapper">

						<table class="table" id="manufacturing">
							<thead>
								<th> T2 Rigged </th>
								<th> Alliance </th>
								<th> System </th>
								<th> Fitting Value </th>
							</thead>

							<tbody>

								@if (isset($t2_rigged))             
								@foreach($t2_rigged as $man)


								<tr>
									<td style="vertical-align: middle"><a target="_blank" href="{{ route('structures.view', $man->str_structure_id_md5)}}">{!! $man->str_name !!}</a></td>
									@if($man->str_owner_alliance_id > 1)
									<td style="vertical-align: middle"><a href="{{ route('alliance.view', $man->str_owner_alliance_id )}}"><img class="img-circle" src="https://images.evetech.net/alliances/{{ $man->str_owner_alliance_id }}/logo?size=32">&nbsp;{{ $man->str_owner_alliance_name }}</a></td>
									@else
									<td></td>
									@endif
									<td style="vertical-align: middle"><a target="_blank" href="{{ route('solar.system', $man->str_system_id)}}">{!! $man->str_system !!}</a></td>
									<td style="vertical-align: middle">{!! number_format($man->str_value,2) !!}</td>
								</tr>


								@endforeach
								@else

								<tr>
									<td colspan="6"><em>No T2 Rigged Structures Found In This Region</em></td>
								</tr>

								@endif

							</tbody>

						</table>

					</div>
				</div>


			</div>
		</div>
	</div>


	<div class="col-md-2">
		<div class="panel panel-default">
			<div class="panel-heading">Known Alliances</div>
			<div class="panel-body">
				<div class="col-md-12">

					<div class="table-responsive top-border-table" id="location-table-wrapper">

						<table class="table" id="regions">
							<thead>
								<th> Alliance Name</th>
								<th> Structures</th>
							</thead>

							<tbody>

								@if (isset($alliances))              
								@foreach($alliances as $alliance)

								@if (!$alliance->str_owner_alliance_name == "")
								<tr>
									<td style="vertical-align: middle"><a target="_blank" href="{{ route('alliance.view', $alliance->str_owner_alliance_id)}}">{!! $alliance->str_owner_alliance_name !!}</a></td>
									<td style="vertical-align: middle">{!! $structures->where('str_owner_alliance_name', $alliance->str_owner_alliance_name)->count() !!}</td>
								</tr>
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
	</div>

	<div class="col-md-5">
		<div class="panel panel-default">
			<div class="panel-heading">Keepstars - Sorted By Value. (Limited to 5)</div>
			<div class="panel-body">
				<div class="col-md-12">

					<div class="table-responsive top-border-table" id="location-table-wrapper">

						<table class="table" id="keepstars">
							<thead>
								<th> Structure Name</th>
								<th> Alliance</th>
								<th> System</th>
								<th> Fitting Value</th>
							</thead>

							<tbody>

								@if (isset($keepstars))              
								@foreach($keepstars as $keepstar)


								<tr>
									<td style="vertical-align: middle"><a target="_blank" href="{{ route('structures.view', $keepstar->str_structure_id_md5)}}">{!! $keepstar->str_name !!}</a></td>
									<td style="vertical-align: middle"><a href="{{ route('alliance.view', $keepstar->str_owner_alliance_id )}}"><img class="img-circle" src="https://images.evetech.net/alliances/{{ $keepstar->str_owner_alliance_id }}/logo?size=32">&nbsp;{{ $keepstar->str_owner_alliance_name }}</a></td>
									<td style="vertical-align: middle"><a target="_blank" href="{{ route('solar.system', $keepstar->str_system_id)}}">{!! $keepstar->str_system !!}</a></td>
									<td style="vertical-align: middle">{!! number_format($keepstar->str_value,2) !!}</td>
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

	<div class="col-md-5">
		<div class="panel panel-default">
			<div class="panel-heading">Sotiyos - Sorted By Value. (Limited to 5)</div>
			<div class="panel-body">
				<div class="col-md-12">

					<div class="table-responsive top-border-table" id="location-table-wrapper">

						<table class="table" id="sotiyos">
							<thead>
								<th> Structure Name</th>
								<th> Alliance</th>
								<th> System</th>
								<th> Fitting Value</th>
							</thead>

							<tbody>

								@if (isset($sotiyos))             
								@foreach($sotiyos as $sotiyo)


								<tr>
									<td style="vertical-align: middle"><a target="_blank" href="{{ route('structures.view', $sotiyo->str_structure_id_md5)}}">{!! $sotiyo->str_name !!}</a></td>
									<td style="vertical-align: middle"><a href="{{ route('alliance.view', $sotiyo->str_owner_alliance_id )}}"><img class="img-circle" src="https://images.evetech.net/alliances/{{ $sotiyo->str_owner_alliance_id }}/logo?size=32">&nbsp;{{ $sotiyo->str_owner_alliance_name }}</a></td>
									<td style="vertical-align: middle"><a target="_blank" href="{{ route('solar.system', $sotiyo->str_system_id)}}">{!! $sotiyo->str_system !!}</a></td>
									<td style="vertical-align: middle">{!! number_format($sotiyo->str_value,2) !!}</td>
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


	<div class="col-md-5">
		<div class="panel panel-default">
			<div class="panel-heading">Fortizars - Sorted By Value. (Limited to 5)</div>
			<div class="panel-body">
				<div class="col-md-12">

					<div class="table-responsive top-border-table" id="location-table-wrapper">

						<table class="table" id="sotiyos">
							<thead>
								<th> Structure Name</th>
								<th> Alliance</th>
								<th> System</th>
								<th> Fitting Value</th>
							</thead>

							<tbody>

								@if (isset($fortizars))             
								@foreach($fortizars as $fortizar)


								<tr>
									<td style="vertical-align: middle"><a target="_blank" href="{{ route('structures.view', $fortizar->str_structure_id_md5)}}">{!! $fortizar->str_name !!}</a></td>
									<td style="vertical-align: middle"><a href="{{ route('alliance.view', $fortizar->str_owner_alliance_id )}}"><img class="img-circle" src="https://images.evetech.net/alliances/{{ $fortizar->str_owner_alliance_id }}/logo?size=32">&nbsp;{{ $fortizar->str_owner_alliance_name }}</a></td>
									<td style="vertical-align: middle"><a target="_blank" href="{{ route('solar.system', $fortizar->str_system_id)}}">{!! $fortizar->str_system !!}</a></td>
									<td style="vertical-align: middle">{!! number_format($fortizar->str_value,2) !!}</td>
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


	<div class="col-md-5">
		<div class="panel panel-default">
			<div class="panel-heading">Azbels - Sorted By Value. (Limited to 5)</div>
			<div class="panel-body">
				<div class="col-md-12">

					<div class="table-responsive top-border-table" id="location-table-wrapper">

						<table class="table" id="sotiyos">
							<thead>
								<th> Structure Name</th>
								<th> Alliance</th>
								<th> System</th>
								<th> Fitting Value</th>
							</thead>

							<tbody>

								@if (isset($azbels))             
								@foreach($azbels as $azbel)


								<tr>
									<td style="vertical-align: middle"><a target="_blank" href="{{ route('structures.view', $azbel->str_structure_id_md5)}}">{!! $azbel->str_name !!}</a></td>
									<td style="vertical-align: middle"><a href="{{ route('alliance.view', $azbel->str_owner_alliance_id )}}"><img class="img-circle" src="https://images.evetech.net/alliances/{{ $azbel->str_owner_alliance_id }}/logo?size=32">&nbsp;{{ $azbel->str_owner_alliance_name }}</a></td>									<td style="vertical-align: middle"><a target="_blank" href="{{ route('solar.system', $azbel->str_system_id)}}">{!! $azbel->str_system !!}</a></td>
									<td style="vertical-align: middle">{!! number_format($azbel->str_value,2) !!}</td>
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

	<div class="col-md-5">
		<div class="panel panel-default">
			<div class="panel-heading">Jump Bridge Network (Limited to 5)</div>
			<div class="panel-body">
				<div class="col-md-12">

					<div class="table-responsive top-border-table" id="location-table-wrapper">

						<table class="table" id="jump_bridges">
							<thead>
								<th> Structure Name</th>
								<th> Alliance</th>
								<th> System</th>
								<th> Age</th>
							</thead>

							<tbody>

								@if (isset($jump_bridges))             
								@foreach($jump_bridges as $jump_bridge)


								<tr>
									<td style="vertical-align: middle"><a target="_blank" href="{{ route('structures.view', $jump_bridge->str_structure_id_md5)}}">{!! $jump_bridge->str_name !!}</a></td>
									<td style="vertical-align: middle"><a href="{{ route('alliance.view', $jump_bridge->str_owner_alliance_id )}}"><img class="img-circle" src="https://images.evetech.net/alliances/{{ $jump_bridge->str_owner_alliance_id }}/logo?size=32">&nbsp;{{ $jump_bridge->str_owner_alliance_name }}</a></td>									
									<td style="vertical-align: middle"><a target="_blank" href="{{ route('solar.system', $jump_bridge->str_system_id)}}">{!! $jump_bridge->str_system !!}</a></td>
									<td style="vertical-align: middle">{!! $jump_bridge->updated_at->diffForHumans() !!}</td>
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
	@permission('moon.view.moons')
	<div class="col-md-5">
		<div class="panel panel-default">
			<div class="panel-heading">Most Valuable Moons (Limited to 5)</div>
			<div class="panel-body">
				<div class="col-md-12">

					<div class="table-responsive top-border-table" id="location-table-wrapper">

						<table class="table" id="moons">
							<thead>
								<th> Moon Name</th>
								<th> Rarity</th>
								<th> Full Frack Value (56 Days)</th>
							</thead>

							<tbody>

								@if (isset($moons))             
								@foreach($moons as $moon)


								<tr>
									<td style="vertical-align: middle"><a href="{{ route('moons.view_moon', $moon->moon_id)}}">{{ $moon->moon_name }}</a></td>
									<td style="vertical-align: middle">R{!! $moon->moon_r_rating !!}</a></td>
									<td style="vertical-align: middle">{!! number_format($moon->moon_value_56_day,2) !!}</a></td>
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
	@endpermission


	
</div>




@stop

