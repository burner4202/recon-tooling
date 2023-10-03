@extends('layouts.guest')

@section('page-title', 'Intel')

@section('content')

<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
			Intel
			<small> paste anything from ingame.</small>
		</h1>
	</div>
</div>

@include('partials.messages')


	<div class="row col-md-8">
		<div class="panel panel-default">
			<div class="panel-heading">Paste Something
				<div class="pull-right" style="vertical-align:middle;">
					<span class="glyphicon glyphicon-info-sign fa-1x" data-toggle="tooltip" title="Paste something." data-placement="left"></span>
				</div>

			</div>
			<div class="panel-body">


				<form method="post" action="/intel/post" enctype="multipart/form-data">
					{{ csrf_field() }}
					<div class="form-group row">
						<div class="col-sm-12">
							<textarea name="title" type="text" class="form-control" id="dscan" placeholder="Go on... throw it on in.." rows="20"></textarea>
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
