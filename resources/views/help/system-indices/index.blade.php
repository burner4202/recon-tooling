<ul class="nav nav-tabs">
	<li class="active"><a data-toggle="tab" href="#summary">Summary</a></li>
	<li><a data-toggle="tab" href="#reactions">Reactions</a></li>
</ul>

<div class="tab-content">
	<div id="summary" class="tab-pane fade in active">

		@include('help.system-indices.summary')
		
	</div>

	<div id="reactions" class="tab-pane fade">

		@include('help.system-indices.reactions')

	</div>

</div>
