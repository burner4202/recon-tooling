<table class="table" id="structures">
	<thead>
		<th> @sortablelink('str_name', 'Structure Name')</th>
		<th> @sortablelink('str_type', 'Type')</th>
		<th> Fitting Summary </th>
		<th> @sortablelink('str_vul_hour', 'Vulnerability Time')</th>
		<th> @sortablelink('str_state', 'State')</th>
		@permission('deliver.package')
		<th>@sortablelink('str_package_delivered', 'Package Status')</th>
		@endpermission
		<th> @sortablelink('str_status', 'Status')</th>

		<th> @sortablelink('str_system', 'System')</th>
		<th> @sortablelink('str_constellation_name', 'Constellation')</th>
		<th> @sortablelink('str_region_name', 'Region')</th>
		<th> @sortablelink('str_owner_corporation_name', 'Corporation Owner')</th>
		<th> @sortablelink('str_owner_alliance_name', 'Alliance')</th>
		<th> @sortablelink('str_value', 'Fitting Value',)</th>
		<th> @sortablelink('updated_at', 'Last Updated')</th>
		@permission('structure.hitlist')
		<th> Hitlist </th>
			@endpermission
			@permission('set.waypoint')
			<th> Set Waypoint </th>
			@endpermission


		</thead>
		<tbody>

			@if (isset($structures))              
			@foreach($structures as $structure)

			<tr>

				<td style="vertical-align: middle">
					@permission('structure.hitlist')
					@if($structure->str_hitlist == 1)

					<i class="glyphicon glyphicon-flag"></i>

					@endif
					@endpermission

					<a href="{{  route('structures.view', $structure->str_structure_id_md5 )}}">{{ $structure->str_name }}
					</a>


				</td>
				<td style="vertical-align: middle"><img class="img-circle" src="https://images.evetech.net/types/{{ $structure->str_type_id }}/render?size=32">&nbsp;{{ $structure->str_type }}</td>

				<td style="vertical-align: middle; max-width:180px;">

					@if($structure->str_dooms_day)
					<span class="label label-danger">Dooms Day</span>
					@endif
					@if($structure->str_point_defense)
					<span class="label label-danger">Point Defense</span>
					@endif
					@if($structure->str_anti_cap)
					<span class="label label-danger">Anti Cap Fit</span>
					@endif
					@if($structure->str_anti_subcap)
					<span class="label label-danger">Anti Subcap Fit</span>
					@endif
					@if($structure->str_guide_bombs)
					<span class="label label-warning">Guided Bombs</span>
					@endif


					@if($structure->str_market)
					<span class="label label-primary">Market Hub</span>
					@endif
					@if($structure->str_cloning)
					<span class="label label-primary">Clone Bay</span>
					@endif
					@if($structure->str_capital_shipyard)
					<span class="label label-warning">Capital Production</span>
					@endif
					@if($structure->str_supercapital_shipyard)
					<span class="label label-danger">Titan Production</span>
					@endif
					@if($structure->str_hyasyoda)
					<span class="label label-primary">Hyasyoda</span>
					@endif
					@if($structure->str_invention)
					<span class="label label-primary">Invention</span>
					@endif
					@if($structure->str_manufacturing)
					<span class="label label-primary">Manufacturing</span>
					@endif
					@if($structure->str_research)
					<span class="label label-primary">Researching</span>
					@endif
					@if($structure->str_biochemical)
					<span class="label label-primary">Booster Production</span>
					@endif
					@if($structure->str_composite)
					<span class="label label-primary">Moon Reactions</span>
					@endif
					@if($structure->str_hybrid)
					<span class="label label-primary">Tech 3 Production</span>
					@endif
					@if($structure->str_moon_drilling)
					<span class="label label-primary">Moon Drilling</span>
					@endif
					@if($structure->str_reprocessing)
					<span class="label label-primary">Reprocessing</span>
					@endif
					@if($structure->str_t2_rigged)
					<span class="label label-success">T2 Rigged</span>
					@endif

				</td>
				<td style="vertical-align: middle">
					{{ $structure->str_vul_hour }}
				</td>
				@if ($structure->str_state === "High Power")
				<td style="vertical-align: middle"><span class="label label-success">High Power</span></td>
				@elseif ($structure->str_state === "Low Power")
				<td style="vertical-align: middle"><span class="label label-danger">Low Power</span></td>
				@elseif ($structure->str_state === "Abandoned")
				<td style="vertical-align: middle"><span class="label label-warning">Abandoned</span></td>
				@elseif ($structure->str_state === "Anchoring")
				<td style="vertical-align: middle"><span class="label label-warning">Anchoring</span></td>
				@elseif ($structure->str_state === "Unanchoring")
				<td style="vertical-align: middle"><span class="label label-primary">Unanchoring</span></td>
				@elseif ($structure->str_state === "Reinforced")
				<td style="vertical-align: middle"><span class="label label-info">Reinforced</span></td>
				@else
				<td style="vertical-align: middle">-</td>
				@endif

				@permission('deliver.package')
				<td style="vertical-align: middle">

					@if ($structure->str_package_delivered === "Package Delivered")
					<span class="label label-success">{{ $structure->str_package_delivered }}</span>
					@elseif ($structure->str_package_delivered === "")
					<span class="label label-danger">No Package</span>
					@elseif($structure->str_package_delivered === "Package Vertified")
					<span class="label label-primary">Package Vertified</span>
					@else
					<span class="label label-danger">{{ $structure->str_package_delivered }}</span>
					@endif
				</td>
				@endpermission

				@if ($structure->str_status === "Unanchoring")
				<td style="vertical-align: middle"><span class="label label-primary">Unanchoring</span></td>
				@elseif ($structure->str_status === "Armor")
				<td style="vertical-align: middle"><span class="label label-warning">Reinforced Armor</span></td>
				@elseif ($structure->str_status === "Hull")
				<td style="vertical-align: middle"><span class="label label-danger">Reinforced Hull</span></td>
				@else
				<td style="vertical-align: middle">-</td>
				@endif

				<td style="vertical-align: middle"><a href="{{  route('solar.system', $structure->str_system_id) }}">{{ $structure->str_system }}</a></td>
				<td style="vertical-align: middle"><a href="{{  route('solar.constellation', $structure->str_constellation_id) }}">{{ $structure->str_constellation_name }}</a></td>
				<td style="vertical-align: middle"><a href="{{  route('solar.region', $structure->str_region_id) }}">{{ $structure->str_region_name }}</a></td>
				@if($structure->str_owner_corporation_id > 1)
				<td style="vertical-align: middle"><a href="{{ route('corporation.view', $structure->str_owner_corporation_id )}}"><img class="img-circle" src="https://images.evetech.net/corporations/{{ $structure->str_owner_corporation_id }}/logo?size=32">&nbsp;{{ $structure->str_owner_corporation_name }}</a></td>
				@else
				<td></td>
				@endif
				@if($structure->str_owner_alliance_id > 1)
				<td style="vertical-align: middle"><a href="{{ route('alliance.view', $structure->str_owner_alliance_id )}}"><img class="img-circle" src="https://images.evetech.net/alliances/{{ $structure->str_owner_alliance_id }}/logo?size=32">&nbsp;{{ $structure->str_owner_alliance_name }} ({{ $structure->str_owner_alliance_ticker }})</a></td>
				@else
				<td></td>
				@endif

				<td style="vertical-align: middle">{{ number_format($structure->str_value,2) }}</td>

				<td style="vertical-align: middle">{{ $structure->updated_at->diffForHumans() }}</td>

				@permission('structure.hitlist')
				<td class="text-center" style="vertical-align: middle">
				@if($structure->str_hitlist == 1)

					<a href="{{ route('structures.hitlist_remove', $structure->str_structure_id_md5) }}" class="btn btn-danger" id="hitlist_remove" data-toggle="tooltip" data-placement="right" title="Remove from the Hitlist">
						<i class="glyphicon glyphicon-minus"></i>
					</a>

				@else

					<a href="{{ route('structures.hitlist_add', $structure->str_structure_id_md5) }}" class="btn btn-danger" id="hitlist_add" data-toggle="tooltip" data-placement="right" title="Add to Hitlist">
						<i class="glyphicon glyphicon-flag"></i>
					</a>

				@endif
				</td>
				@endpermission


				@permission('set.waypoint')
				<td class="text-center" style="vertical-align: middle">
					<a href="{{ route('structures.setwaypoint', $structure->str_structure_id)}}" class="btn btn-success btn-circle edit" title="Set Waypoint" data-toggle="tooltip" data-placement="right">
						<i class="glyphicon glyphicon-play"></a></i>
					</a>
				</td>
				@endpermission

			</tr>

			@endforeach
			@else

			<tr>
				<td colspan="6"><em>No Records Found</em></td>
			</tr>

			@endif

		</tbody>
	</table>