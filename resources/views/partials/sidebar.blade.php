<div class="navbar-default sidebar" role="navigation">
	<div class="sidebar-nav navbar-collapse">
		<ul class="nav" id="side-menu">
			<li class="sidebar-avatar">
				<div class="dropdown">
					<div>
						<img alt="image" class="img-circle avatar" width="100" src="{{ Auth::user()->present()->avatar }}">
					</div>
					@permission('trackme.usage')
					<div class="name"><strong>{{ Auth::user()->present()->nameOrEmail }}</strong></div><br>
					<form method="GET" action="{{ route('esi.trackMe') }}" accept-charset="UTF-8" id="trackme-form">
						{!! Form::select('character_name', $trackme_characters, Input::get('character_name'), ['id' => 'character_name', 'class' => 'form-control']) !!}
					</div>
				</form>
				@endpermission
			</li>
			<li class="{{ Request::is('/') ? 'active open' : ''  }}">
				<a href="{{ route('dashboard') }}" class="{{ Request::is('/') ? 'active' : ''  }}">
					<i class="fa fa-dashboard fa-fw"></i> @lang('app.dashboard')
				</a>
			</li>
			@permission('administration.view')
						<li class="{{ Request::is('/administration*') ? 'active open' : ''  }}">
				<a href="{{ route('administration.index') }}" class="{{ Request::is('/administration') ? 'active' : ''  }}">
					<i class="fa fa-user-secret fa-fw"></i> Administration
				</a>
			</li>
			@endpermission

			<li class="{{ Request::is('/intelligence/dashboard') ? 'active open' : ''  }}">
				<a href="{{ route('intelligence.index') }}" class="{{ Request::is('/intelligence/dashboard') ? 'active' : ''  }}">
					<i class="fa fa-connectdevelop fa-fw"></i> Intelligence
				</a>
			</li>


			@permission('help.me.usage')
			<li class="{{ Request::is('/help') ? 'active open' : ''  }}">
				<a href="{{ route('help.index') }}" class="{{ Request::is('/help') ? 'active' : ''  }}">
					<i class="fa fa-ambulance fa-fw"></i> Help Me
				</a>
			</li>
			@endpermission


			@permission('observation.create')
			<li class="{{ Request::is('/observation') ? 'active open' : ''  }}">
				<a href="{{ route('observation.index') }}" class="{{ Request::is('/observation') ? 'active' : ''  }}">
					<i class="fa fa-eye fa-fw"></i> Observation
				</a>
			</li>
			@endpermission

			<li class="{{ Request::is('esi*') || Request::is('route/planning*')  ? 'active open' : ''  }}">
				<a href="#">
					<i class="fa fa-key fa-fw"></i>
					Characters
					<span class="fa arrow"></span>
				</a>
				<ul class="nav nav-second-level collapse">


					<li class="{{ Request::is('esi') ? 'active open' : ''  }}">
						<a href="{{ route('esi.index') }}" class="{{ Request::is('esi') ? 'active' : ''  }}">
							Registered Tokens
						</a>
					</li>

					<li class="{{ Request::is('route/planning') ? 'active open' : ''  }}">
						<a href="{{ route('route.planning') }}" class="{{ Request::is('/route/planning/*') ? 'active' : ''  }}">
							Route Planning
						</a>
					</li>


				</ul>
			</li>

			@permission('structure.single.search')
			<li class="{{ Request::is('search/structures*') ? 'active open' : ''  }}">
				<a href="{{ route('structures.search') }}" class="{{ Request::is('search/structures*') ? 'active' : ''  }}">
					<i class="fa fa-search fa-fw"></i> Structure Search
				</a>
			</li>
			@endpermission


			@permission('universe.view')
			<li class="{{ Request::is('universe*') ? 'active open' : ''  }}">
				<a href="{{ route('solar.universe') }}" class="{{ Request::is('universe/*') ? 'active' : ''  }}">
					<i class="fa fa-globe fa-fw"></i> Universe
				</a>
			</li>
			@endpermission

			<li class="{{ Request::is('report/standings*') ? 'active open' : ''  }}">
				<a href="{{ route('standings.index') }}" class="{{ Request::is('report/standings/*') ? 'active' : ''  }}" data-toggle="tooltip" title="Alliance Standings, Updated Daily." data-placement="right">
					<i class="fa fa-bank fa-fw"></i> Alliance Standings
				</a>
			</li>

			@permission('structures.view')
			<li class="{{ Request::is('structures*') ? 'active open' : ''  }}">
				<a href="#">
					<i class="fa fa-building fa-fw"></i>
					Upwell Structures
					<span class="fa arrow"></span>
				</a>
				<ul class="nav nav-second-level collapse">


					<li>
						<a href="{{ route('structures.index') }}" data-toggle="tooltip" title="All Known Structures" data-placement="right"class="{{ Request::is('structures/*') ? 'active' : ''  }}">
							All Structures
						</a>
					</li>

					@permission('structure.hitlist')

					<li>
						<a href="{{ route('structures.index')}}?sort=str_value&direction=desc&on_hitlist=on" data-toggle="tooltip" title="Kill me now, not now, right now!" data-placement="right" class="{{ Request::is('structures/hitlist*') ? 'active' : ''  }}">
							Hitlist
						</a>
					</li>
					@endpermission

					<li>
						<a href="{{ route('structures.abandoned')}}" data-toggle="tooltip" title="Structures that have no owner, needs meta data." data-placement="right" class="{{ Request::is('structures/abandoned*') ? 'active' : ''  }}">
							Abandoned
						</a>
					</li>

					<li>
						<a href="{{ route('structures.destroyed')}}" data-toggle="tooltip" title="They built it, we killed it. Hon Hon Hon" data-placement="right" class="{{ Request::is('structures/destroyed*') ? 'active' : ''  }}">
							Destroyed
						</a>
					</li>

					<li>
						<a href="{{ route('structures.orphans')}}" data-toggle="tooltip" title="Structures that have no owner, needs meta data." data-placement="right" class="{{ Request::is('structures/orphans*') ? 'active' : ''  }}">
							Orphans
						</a>
					</li>
					@permission('deliver.package')
					<li>
						<a href="{{ route('structures.packageless')}}" data-toggle="tooltip" title="Structures that have no package." data-placement="right" class="{{ Request::is('structures/packageless*') ? 'active' : ''  }}">
							Packageless
						</a>
					</li>
					@endpermission
					@permission('structure.merge')
					<li>
						<a href="{{ route('merge.duplicates')}}" data-toggle="tooltip" title="Duplicate structures, name changes/ownership changes etc, must be merged." data-placement="right" class="{{ Request::is('structures/duplicates*') ? 'active' : ''  }}">
							Duplicates
						</a>
					</li>
					@endpermission
				</ul>
			</li>
			@endpermission

			@permission('package.monthly.report')
			<li class="{{ Request::is('report/package/manager/monthly*') ? 'active open' : ''  }}">
				<a href="{{ route('package_manager.monthly_index') }}" data-toggle="tooltip" title="Delivery Reports" data-placement="right">
					<i class="fa fa-cube fa-fw"></i> Package Delivery
				</a>
			</li>
			@endpermission

			@permission('moon.view.moons')
			<li>
				<a href="{{ route('moons.moons') }}" class="{{ Request::is('moons') ? 'active' : ''  }}">
					<i class="fa fa-moon-o fa-fw"></i> Moon Database
				</a>
			</li>
			@endpermission


			@permission('tasks.view')
			<li class="{{ Request::is('tasks*') ? 'active open' : ''  }}">
				<a href="#">
					<i class="fa fa-tasks fa-fw"></i>
					Tasks Manager
					<span class="fa arrow"></span>
				</a>
				<ul class="nav nav-second-level collapse">

					<li class="{{ Request::is('tasks/outstanding*') ? 'active open' : ''  }}">
						<a href="{{ route('taskmanager.outstanding') }}" class="{{ Request::is('tasks/outstanding*') ? 'active' : ''  }}" data-toggle="tooltip" title="Current Outstanding Tasks" data-placement="right">
							Outstanding
						</a>
					</li>


					<li class="{{ Request::is('tasks/inprogress*') ? 'active open' : ''  }}">
						<a href="{{ route('taskmanager.inprogress') }}" class="{{ Request::is('tasks/inprogress*') ? 'active' : ''  }}">
							In Progress
						</a>
					</li>

					@permission('taskmanager.manage')
					<li class="{{ Request::is('tasks/overview*') ? 'active open' : ''  }}">
						<a href="{{ route('taskmanager.overview') }}" class="{{ Request::is('tasks/overview*') ? 'active' : ''  }}">
							Overview
						</a>
					</li>
					@endpermission

				</ul>
			</li>
			@endpermission


			<li class="{{ Request::is('upwell*') || Request::is('market*') ? 'active open' : ''  }}">
				<a href="#">
					<i class="fa fa-usd fa-fw"></i>
					Market Prices
					<span class="fa arrow"></span>
				</a>
				<ul class="nav nav-second-level collapse">

					<li class="{{ Request::is('upwell/rigs*') ? 'active open' : ''  }}">
						<a href="{{ route('upwell.rigs') }}" class="{{ Request::is('upwell/rigs') ? 'active' : ''  }}" data-toggle="tooltip" title="Upwell Rig Costings (Calculated & Updated Daily with Market Salvage Prices)" data-placement="right">
							Rig Manufacturing Costs
						</a>
					</li>


					<li class="{{ Request::is('market/salvage*') ? 'active open' : ''  }}">
						<a href="{{ route('upwell.salvage') }}" class="{{ Request::is('market/salvage') ? 'active' : ''  }}" data-toggle="tooltip" title="Updated Daily at 13:00 UTC from The Forge Market" data-placement="right">
							Salvage
						</a>
					</li>

					<li class="{{ Request::is('market/modules*') ? 'active open' : ''  }}">
						<a href="{{ route('upwell.modules') }}" class="{{ Request::is('market/modules') ? 'active' : ''  }}" data-toggle="tooltip" title="Updated Daily at 13:00 UTC from The Forge Market" data-placement="right">
							Upwell Modules
						</a>
					</li>

					<li class="{{ Request::is('market/minerals*') ? 'active open' : ''  }}">
						<a href="{{ route('refined.minerals') }}" class="{{ Request::is('market/minerals*') ? 'active' : ''  }}">
							Minerals
						</a>
					</li>

					<li class="{{ Request::is('market/moon_goo*') ? 'active open' : ''  }}">
						<a href="{{ route('refined.moons') }}" class="{{ Request::is('market/moon_goo*') ? 'active' : ''  }}">
							Raw Moon Materials
						</a>
					</li>

				</ul>
			</li>

			@permission('entosis.view')
			<li class="{{ Request::is('entosis/*') ? 'active open' : ''  }}">
				<a href="{{ route('entosis.campaigns') }}" class="{{ Request::is('entosis/campaigns*') ? 'active' : ''  }}">
					<i class="fa fa-exchange fa-fw"></i> Entosis Manager
				</a>
			</li>
			@endpermission

			@permission(['users.manage', 'users.activity', 'roles.manage', 'permissions.manage'])
			<li class="{{ Request::is('user*') || Request::is('active-users') || Request::is('activity') || Request::is('role*')  || Request::is('permission*') ? 'active open' : ''  }}">
				<a href="#">
					<i class="fa fa-user fa-fw"></i>
					User Management
					<span class="fa arrow"></span>
				</a>
				<ul class="nav nav-second-level collapse">


					@permission('users.activity')
					<li class="{{ Request::is('activity') ? 'active open' : ''  }}">
						<a href="{{ route('activity.index') }}" class="{{ Request::is('activity') ? 'active' : ''  }}">
							@lang('app.activity_log')
						</a>
					</li>
					@endpermission

					@permission('users.manage')
					<li class="{{ Request::is('active-users') ? 'active open' : ''  }}">
						<a href="{{ route('active-users') }}" class="{{ Request::is('active-users') ? 'active' : ''  }}">
							Active Users
						</a>
					</li>

					<li class="{{ Request::is('user*') ? 'active open' : ''  }}">
						<a href="{{ route('user.list') }}" class="{{ Request::is('user*') ? 'active' : ''  }}">
							@lang('app.users')
						</a>
					</li>
					@endpermission

					@permission('roles.manage')
					<li>
						<a href="{{ route('role.index') }}" class="{{ Request::is('role*') ? 'active' : ''  }}">
							@lang('app.roles')
						</a>
					</li>
					@endpermission
					@permission('permissions.manage')
					<li>
						<a href="{{ route('permission.index') }}"
						class="{{ Request::is('permission*') ? 'active' : ''  }}">@lang('app.permissions')</a>
					</li>
					@endpermission


				</ul>
			</li>
			@endpermission


			@permission(['settings.general', 'settings.auth', 'settings.notifications'], false)
			<li class="{{ Request::is('settings*') ? 'active open' : ''  }}">
				<a href="#">
					<i class="fa fa-gear fa-fw"></i> @lang('app.settings')
					<span class="fa arrow"></span>
				</a>
				<ul class="nav nav-second-level collapse">
					@permission('settings.general')
					<li>
						<a href="{{ route('settings.general') }}"
						class="{{ Request::is('settings') ? 'active' : ''  }}">
						@lang('app.general')
					</a>
				</li>
				@endpermission
				@permission('settings.auth')
				<li>
					<a href="{{ route('settings.auth') }}"
					class="{{ Request::is('settings/auth*') ? 'active' : ''  }}">
					@lang('app.auth_and_registration')
				</a>
			</li>
			@endpermission
			@permission('settings.notifications')
			<li>
				<a href="{{ route('settings.notifications') }}"
				class="{{ Request::is('settings/notifications*') ? 'active' : ''  }}">
				@lang('app.notifications')
			</a>
		</li>
		@endpermission
	</ul>
</li>
@endpermission
</ul>
</div>
<!-- /.sidebar-collapse -->
</div>
