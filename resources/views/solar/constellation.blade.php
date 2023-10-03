@extends('layouts.app')

@section('page-title', $constellation_name->ss_constellation_name)

@section('content')

<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
			{{ $constellation_name->ss_constellation_name }}
			<small> - </small>
			<div class="pull-right">
				<ol class="breadcrumb">
					<li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
					<li><a href="{{ route('solar.universe') }}">Universe</a></li>
					<li><a href="{{ route('solar.region', $constellation_name->ss_region_id )}}">{{ $constellation_name->ss_region_name }}</a></li>
					<li class="active">{{ $constellation_name->ss_constellation_name }}</li>
				</ol>
			</div>

		</h1>
	</div>
</div>

@include('partials.messages')


<div class="row tab-search">
	<div class="col-md-12">
		<form method="GET" action="" accept-charset="UTF-8" id="universe-form" autocomplete="off">
			<div class="col-md-3">
				<div class="input-group custom-search-form">
					<input type="text" class="form-control" name="search" value="{{ Input::get('search') }}" placeholder="Search System" meta name="csrf-token" content="{{csrf_token() }}">
					<span class="input-group-btn">
						<button class="btn btn-default" type="submit" id="search-universe-btn">
							<span class="glyphicon glyphicon-search"></span>
						</button>
						@if (Input::has('search') && Input::get('search') != '')
						<a href="{{ route('solar.universe') }}" class="btn btn-danger" type="button" >
							<span class="glyphicon glyphicon-remove"></span>
						</a>
						@endif
					</span>
				</div>
			</div>
		</form>
	</div>
</div>

<div class="col-md-6">
	{!! $systems->appends(\Request::except('systems'))->render() !!}
</div>

<div class="col-md-12">
	<div class="panel panel-default">
		<div class="panel-heading">Constellation</div>
		<div class="panel-body">

			<div class="table-responsive top-border-table" id="srp-table-wrapper">

				<table class="table" id="constellation">
					<thead>
						<th>@sortablelink('ss_system_name', 'System Name')</th>
						<th>@sortablelink('ss_constellation_name', 'Constellation Name')</th>
						<th>@sortablelink('ss_security_status', 'Security Status')</th>
						<th>No of Structures</th>
					</thead>
					<tbody>

						@if (isset($systems))              
						@foreach($systems as $system)

						<tr>
							<td><a href="{{ route('solar.system', $system->ss_system_id) }}">{{ $system->ss_system_name }}&nbsp;</a>

								@foreach($keepstars as $keepstar) 
								@if($system->ss_system_id == $keepstar->str_system_id)
								<a href="#" data-toggle="tooltip" title="System has a Keepstar, Click system to see when last updated." data-placement="top"><img class="img-circle" src="https://image.eveonline.com/Type/35834_32.png"></img>&nbsp;</a>
								@endif
								@endforeach

								@foreach($sotiyos as $sotiyo) 
								@if($system->ss_system_id == $sotiyo->str_system_id)
								<a href="#" data-toggle="tooltip" title="System has a Sotiyo, Click system to see when last updated." data-placement="top"><img class="img-circle" src="https://image.eveonline.com/Type/35827_32.png"></img>&nbsp;</a>
								@endif
								@endforeach

								@foreach($tataras as $tatara) 
								@if($system->ss_system_id == $tatara->str_system_id)
								<a href="#" data-toggle="tooltip" title="System has a Tatara, Click system to see when last updated." data-placement="top"><img class="img-circle" src="https://image.eveonline.com/Type/35836_32.png"></img>&nbsp;</a>
								@endif
								@endforeach


								@foreach($cyno_jammer as $cyno) 
								@if($system->ss_system_id == $cyno->str_system_id)
								<a href="#" data-toggle="tooltip" title="System has a Cyno Jammer, Click system to see when last updated." data-placement="top"><img class="img-circle" src="https://image.eveonline.com/Type/37534_32.png"></img>&nbsp;</a>
								@endif
								@endforeach

								@foreach($jump_gate as $gate) 
								@if($system->ss_system_id == $gate->str_system_id)
								<a href="#" data-toggle="tooltip" title="System has a Jump Gate, Click system to see when last updated." data-placement="top"><img class="img-circle" src="https://image.eveonline.com/Type/35841_32.png"></img>&nbsp;</a>
								@endif
								@endforeach

							</td>
							<td>{{ $system->ss_constellation_name }}</td>

							<td style="vertical-align: middle">{{ $system->ss_security_status }}</td>		

							@php($total = 0)
							@foreach ($structures as $structure)
							@if ($system->ss_system_id == $structure->str_system_id)
							@php($total += 1)
							@endif
							@endforeach



							@if($total == 0)
							<td style="vertical-align: middle"></td>
							@else
							<td style="vertical-align: middle">{!! $total !!}</td>
							@endif
							</td>						
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
@stop


@section('scripts')


<script>

	$("#types").change(function () {
		$("#universe-form").submit();
	});


</script>

@stop