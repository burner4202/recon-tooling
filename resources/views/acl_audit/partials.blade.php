<tr>
	@if($member->aclc_member_type == 'character')
	<td style="vertical-align: middle"><a href="#"><img class="img-circle" src="https://images.evetech.net/characters/{{ $member->aclc_character_id }}/portrait?size=32">&nbsp;{!! $member->aclc_character_name !!}</a></td>
	<td style="vertical-align: middle"><a href="#"><img class="img-circle" src="https://images.evetech.net/corporations/{{ $member->aclc_corporation_id }}/logo?size=32">&nbsp;{!! $member->aclc_corporation_name !!}</a></td>
	<td style="vertical-align: middle"><a href="#"><img class="img-circle" src="https://images.evetech.net/alliances/{{ $member->aclc_alliance_id }}/logo?size=32">&nbsp;{!! $member->aclc_alliance_name !!}</a></td>
	@endif
	@if($member->aclc_member_type == 'corporation')
	<td style="vertical-align: middle"><a href="#"><img class="img-circle" src="https://images.evetech.net/corporations/{{ $member->aclc_character_id }}/logo?size=32">&nbsp;{!! $member->aclc_character_name !!}</a></td>
	<td style="vertical-align: middle"></td>
	<td style="vertical-align: middle"><a href="#"><img class="img-circle" src="https://images.evetech.net/alliances/{{ $member->aclc_alliance_id }}/logo?size=32">&nbsp;{!! $member->aclc_alliance_name !!}</a></td>
	@endif
	@if($member->aclc_member_type == 'alliance')
	<td style="vertical-align: middle"><a href="#"><img class="img-circle" src="https://images.evetech.net/alliances/{{ $member->aclc_character_id }}/logo?size=32">&nbsp;{!! $member->aclc_character_name !!}</a></td>
	<td style="vertical-align: middle"></td>
	<td style="vertical-align: middle"></td>
	@endif

</tr>