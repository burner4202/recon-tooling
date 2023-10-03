<div class="panel-body">

	<table class="table" id="scouted-nodes">
		<thead>
			<tr>
				<th>Node ID</th>
				<th>System</th>
				<th>Reported By</th>
				<th>Allocate</th>
			</tr>
		</thead>
		<tbody>

			<tr>
				<td style="vertical-align: middle">-id-</td>
				<td style="vertical-align: middle">-system-</td>
				<td style="vertical-align: middle">-character-</td>
				<td style="vertical-align: middle">
					<form method="post" action="#" enctype="multipart/form-data">
						{{ csrf_field() }}
						
						{!! Form::select('character', ['Character 1', 'Character 2'], Input::get('character'), ['id' => 'character', 'class' => 'form-control']) !!}

					</form>
				</td>
			</tr>

		</tbody>
	</table>
</div>		