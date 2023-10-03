<thead>
	<tr>

		<th>Character</th>
		<th>Alliance</th>
		<th>Location</th>
		<th>Ship</th>
		<th>Registered</th>
		<th>Remove</th>
	</tr>
</thead>
<tbody>

	@if (isset($registered_hackers))              
	@foreach($registered_hackers as $hacker)

	<tr>
		<td style="vertical-align: middle"><img class="img-circle" src="https://imageserver.eveonline.com/Character/{{ $hacker->eh_character_id }}_32.jpg">{{ $hacker->eh_character_name }}</td>
		<td style="vertical-align: middle"><img class="img-circle" src="https://imageserver.eveonline.com/Alliance/{{ $hacker->eh_character_alliance_id }}_32.png">{{ $hacker->eh_character_alliance_name }}</td>
		<td style="vertical-align: middle">{{ $hacker->eh_location_system_name }}</td>
		<td style="vertical-align: middle"><a href="#" title="{{ $hacker->eh_ship_type_name }}"
			data-toggle="tooltip" data-placement="right"><img class="img-circle" src="https://imageserver.eveonline.com/Type/{{ $hacker->eh_ship_type_id }}_32.png"></a></td>
			<td style="vertical-align: middle">{{ \Carbon\Carbon::parse($hacker->eh_registered_at)->diffForHumans() }}</td>
			<td class="text-center">
				<a href="#" class="btn btn-danger btn-circle edit" title="Remove Character"
				data-toggle="tooltip" data-placement="top">
				<i class="glyphicon glyphicon-remove"></i>
			</a>
		</td>
	</tr>
	@endforeach
	@else

	<tr>
		<td colspan="6"><em>No Records Found</em></td>
	</tr>

	@endif

</tbody>