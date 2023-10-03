<?php

/**
 * Authentication
 */

Route::get('login', 'Auth\AuthController@getLogin');
Route::post('login', 'Auth\AuthController@postLogin');

Route::get('logout', [
	'as' => 'auth.logout',
	'uses' => 'Auth\AuthController@getLogout'
]);

// Allow registration routes only if registration is enabled.
if (settings('reg_enabled')) {
	Route::get('register', 'Auth\AuthController@getRegister');
	Route::post('register', 'Auth\AuthController@postRegister');
	Route::get('register/confirmation/{token}', [
		'as' => 'register.confirm-email',
		'uses' => 'Auth\AuthController@confirmEmail'
	]);
}

// Register password reset routes only if it is enabled inside website settings.
if (settings('forgot_password')) {
	Route::get('password/remind', 'Auth\PasswordController@forgotPassword');
	Route::post('password/remind', 'Auth\PasswordController@sendPasswordReminder');
	Route::get('password/reset/{token}', 'Auth\PasswordController@getReset');
	Route::post('password/reset', 'Auth\PasswordController@postReset');
}

/**
 * Two-Factor Authentication
 */
if (settings('2fa.enabled')) {
	Route::get('auth/two-factor-authentication', [
		'as' => 'auth.token',
		'uses' => 'Auth\AuthController@getToken'
	]);

	Route::post('auth/two-factor-authentication', [
		'as' => 'auth.token.validate',
		'uses' => 'Auth\AuthController@postToken'
	]);
}

/**
 * Social Login
 * Route::get('auth/{provider}/login', [
 *	'as' => 'social.login',
 *	'uses' => 'Auth\SocialAuthController@redirectToProvider',
 *	'middleware' => 'social.login'
 * ]);
 */

 // Route::get('auth/{provider}/callback', 'Auth\SocialAuthController@handleProviderCallback');

// Route::get('auth/gice/email', 'Auth\SocialAuthController@getGICEEmail');
// Route::post('auth/gice/email', 'Auth\SocialAuthController@postGICEEmail');

Route::get('citadel/vulnerability/parse', [
	'as' => 'citadel.parse',
	'uses' => 'CitadelTimerParseController@parse',
]);

Route::get('intel', [
	'as' => 'intel.index',
	'uses' => 'IntelController@index',
]);

Route::post('intel/post', 'IntelController@post');

/* API For Bot */

Route::get('api/structure/{structure_id}', 'StructureAPIController@singleStructure');
Route::get('api/destroyed/structures', 'StructureAPIController@destroyedStructures');
Route::get('api/adm/top10', 'ADMWatchAPIController@top10');

Route::get('api/structures/hostile/region/{region_id}', 'StructureAPIController@hostileStructuresInRegion');
Route::get('api/structures/hitlist', 'StructureAPIController@hitlist');

Route::get('api/log', [
	'uses' => 'StructureAPIController@apiLog',
	'middleware' => 'permission:api.log.view'
]);

Route::get('api/moon/{moon_name}', 'MoonAPIController@singleMoon');

Route::get('api/administration/augswarms', 'AugswarmTrackingController@augswarms');

# Create Task

Route::post('api/task/add', 'TaskManagerAPIController@postTask');


Route::group(['middleware' => 'auth'], function () {

	# Adminstration Page

	Route::get('administration', [
		'as' => 'administration.index',
		'uses' => 'AdminstrationController@index',
		'middleware' => 'permission:administration.view'
	]);

	# Augswarm Tracking 09/08/21

	Route::get('administration/augswarm/tracking', [
		'as' => 'augswarm.index',
		'uses' => 'AugswarmTrackingController@index',
		'middleware' => 'permission:augswarm.tracking'
	]);

	Route::get('administration/augswarm/tracking/update', [
		'as' => 'augswarm.update',
		'uses' => 'AugswarmTrackingController@update',
		'middleware' => 'permission:augswarm.tracking'
	]);

		Route::get('administration/augswarm/tracking/{character_id}/remove', [
		'as' => 'augswarm.remove',
		'uses' => 'AugswarmTrackingController@remove',
		'middleware' => 'permission:augswarm.tracking'
	]);

	Route::post('administration/augswarm/create', [
		'as' => 'augswarm.create',
		'uses' => 'AugswarmTrackingController@create',
		'middleware' => 'permission:augswarm.tracking'
	]);

	# Route Logging

	Route::get('administration/route/logging', [
		'as' => 'route_logging.index',
		'uses' => 'RouteLoggingController@index',
		'middleware' => 'permission:users.activity'
	]);


	Route::get('intelligence/alliance/health', [
		'as' => 'alliance_health.index',
		'uses' => 'AllianceHealthIndexController@index',
		'middleware' => 'permission:alliances.view'
	]);

	Route::get('intelligence/alliance/{alliance_id}/health', [
		'as' => 'alliance_health.view',
		'uses' => 'AllianceHealthIndexController@view',
		'middleware' => 'permission:alliances.view'
	]);


	/*
	* Character Dashboard
	*/

	Route::get('intelligence/character/', [
		'as' => 'character.index',
		'uses' => 'CharactersController@index',
		'middleware' => 'permission:character.view'
	]);

	Route::get('intelligence/character/{character_name}/create', [
		'as' => 'character.create',
		'uses' => 'CharactersController@create',
		'middleware' => 'permission:character.view'
	]);

	Route::get('intelligence/character/{character_name}/view', [
		'as' => 'character.view',
		'uses' => 'CharactersController@view',
		'middleware' => 'permission:character.view'
	]);

	Route::post('intelligence/character/store_report', [
		'as' => 'character.store_report',
		'uses' => 'CharactersController@store_report',
		'middleware' => 'permission:character.view'
	]);

	Route::post('intelligence/character/store_relationship', [
		'as' => 'character.store_relationship',
		'uses' => 'CharactersController@store_relationship',
		'middleware' => 'permission:character.view'
	]);


	Route::get('intelligence/character/reporting', [
		'as' => 'character_reporting.index',
		'uses' => 'CharacterReportsController@index',
		'middleware' => 'permission:character.view'
	]);

	Route::get('intelligence/character/import', [
		'as' => 'character_reporting.import',
		'uses' => 'CharacterScoutingController@index',
		'middleware' => 'permission:character.import'
	]);


	Route::post('/intelligence/character/metadump', 'CharacterScoutingController@metadump');

	/*
	* NPC Kills
	*/

	Route::get('intelligence/npckills/regions/', [
		'as' => 'npc_kills.regions',
		'uses' => 'NPCKillsController@regions',
		'middleware' => 'permission:npc_kills.view'
	]);

	Route::get('intelligence/npckills/region/{region_id}/', [
		'as' => 'npc_kills.region',
		'uses' => 'NPCKillsController@region',
		'middleware' => 'permission:npc_kills.view'
	]);

	Route::get('intelligence/npckills/system/{system_id}', [
		'as' => 'npc_kills.system',
		'uses' => 'NPCKillsController@system',
		'middleware' => 'permission:npc_kills.view'
	]);


	/*
	 *  ADM Watch
	 */

	Route::get('adm/watch', [
		'as' => 'adm_watch.index',
		'uses' => 'ADMWatchController@index',
		'middleware' => 'permission:adm_watch.view'
	]);


	Route::get('adm/watch/manage', [
		'as' => 'adm_watch.manage',
		'uses' => 'ADMWatchController@manage',
		'middleware' => 'permission:adm_watch.manage'
	]);

	Route::post('adm/watch/add/pending', [
		'as' => 'adm_watch.add_to_pending',
		'uses' => 'ADMWatchController@add_to_pending',
		'middleware' => 'permission:adm_watch.manage'
	]);

	Route::get('adm/watch/{id}/dispatch', [
		'as' => 'adm_watch.dispatch',
		'uses' => 'ADMWatchController@dispatch',
		'middleware' => 'permission:adm_watch.manage'
	]);
	Route::get('adm/watch/dispatch/all', [
		'as' => 'adm_watch.dispatch_all',
		'uses' => 'ADMWatchController@dispatch_all',
		'middleware' => 'permission:adm_watch.manage'
	]);
	Route::get('adm/watch/{system_id}/remove', [
		'as' => 'adm_watch.remove_from_dispatch',
		'uses' => 'ADMWatchController@remove',
		'middleware' => 'permission:adm_watch.manage'
	]);

	Route::get('adm/watch/remove/all', [
		'as' => 'adm_watch.remove_all',
		'uses' => 'ADMWatchController@remove_all',
		'middleware' => 'permission:adm_watch.manage'
	]);




	/**
	* EVE SSO ESI Token
	*
	**/

	Route::get('dossier', [
		'as' => 'dossier.index',
		'uses' => 'GroupDossierController@index',
		'middleware' => 'permission:dossier.manage'
	]);

	Route::get('dossier/{id}/view', [
		'as' => 'dossier.view',
		'uses' => 'GroupDossierController@view',
		'middleware' => 'permission:dossier.view'
	]);

	Route::post('dossier/create', [
		'as' => 'dossier.create',
		'uses' => 'GroupDossierController@create',
		'middleware' => 'permission:dossier.manage'
	]);

	Route::post('dossier/store', [
		'as' => 'dossier.store',
		'uses' => 'GroupDossierController@store',
		'middleware' => 'permission:dossier.manage'
	]);

	Route::get('dossier/{id}/approved', [
		'as' => 'dossier.approved',
		'uses' => 'GroupDossierController@approved',
		'middleware' => 'permission:dossier.manage'
	]);

	Route::get('dossier/{id}/delete', [
		'as' => 'dossier.delete',
		'uses' => 'GroupDossierController@delete',
		'middleware' => 'permission:dossier.manage'
	]);

	Route::get('intelligence/dashboard', [
		'as' => 'intelligence.index',
		'uses' => 'IntelligenceDashboardController@index',
	]);

	Route::get('intelligence/contracts', [
		'as' => 'public_contracts.index',
		'uses' => 'PublicContractsController@index',
		'middleware' => 'permission:public_contracts.view'
	]);

	Route::get('intelligence/fleet_commander/hunter', [
		'as' => 'fc.hunter.index',
		'uses' => 'FleetCommanderHunterController@index',
		'middleware' => 'permission:fc_hunter.view'
	]);

	Route::get('intelligence/jump_freighter/hunter', [
		'as' => 'jump_freighter.index',
		'uses' => 'JumpFreighterHuntController@index',
		'middleware' => 'permission:jump_freighter.view'
	]);
	/*
	Route::get('/admin', [
		'as' => 'admin.index',
		'uses' => 'AdministratorDashboardController@index',
		'middleware' => 'permission:admin_dash.view'
	]);
	*/

	Route::get('/intelligence/keepstar/map', [
		'as' => 'regional_tree.index',
		'uses' => 'RegionalTreeViewController@index',
		'middleware' => 'permission:keepstar_tree.view'
	]);

	Route::get('intelligence/structure/statistics', [
		'as' => 'structure_statistics.index',
		'uses' => 'StructureStatisticsController@index',
		'middleware' => 'permission:statistics.view'
	]);

	Route::get('/coordination', [
		'as' => 'coord.index',
		'uses' => 'CoordinationDashController@index',
		'middleware' => 'permission:coordination.use'
	]);

	Route::post('/coordination/add_system', [
		'as' => 'coord.add_system',
		'uses' => 'CoordinationDashController@add_system',
		'middleware' => 'permission:coordination.use'
	]);

	Route::get('/coordination/system/{system_id}/remove', [
		'as' => 'coord.remove_system',
		'uses' => 'CoordinationDashController@remove_system',
		'middleware' => 'permission:coordination.use'
	]);


	/* API for Dashboard (Coord)  */


	Route::get('api/coordination/active_fleets', 'CoordinationDashAPIController@active_fleets');
	Route::get('api/coordination/watched_systems', 'CoordinationDashAPIController@watched_systems');
	Route::get('api/coordination/watched_systems_dscan', 'CoordinationDashAPIController@watched_systems_dscan');


	Route::get('/coalitions', [
		'as' => 'coalitions.list',
		'uses' => 'CoalitionsController@list',
		'middleware' => 'permission:coalitions.view'
	]);

	Route::get('/coalitions/{id}/view', [
		'as' => 'coalitions.view_coalition',
		'uses' => 'CoalitionsController@view_coalition',
		'middleware' => 'permission:coalitions.view'
	]);


	Route::get('/coalitions/manage', [
		'as' => 'coalitions.manage.index',
		'uses' => 'CoalitionsController@manage_index',
		'middleware' => 'permission:coalitions.manage'
	]);

	Route::get('/coalitions/manage/{id}/view', [
		'as' => 'coalitions.view',
		'uses' => 'CoalitionsController@view',
		'middleware' => 'permission:coalitions.manage'
	]);

	Route::delete('/coalitions/manage/{id}/delete', [
		'as' => 'coalitions.delete',
		'uses' => 'CoalitionsController@delete',
		'middleware' => 'permission:coalitions.manage'
	]);


	Route::post('/coalitions/manage/create', [
		'as' => 'coalitions.create',
		'uses' => 'CoalitionsController@create',
		'middleware' => 'permission:coalitions.manage'
	]);

	Route::post('/coalitions/manage/alliance/{id}/add', [
		'as' => 'coalitions.add_alliance',
		'uses' => 'CoalitionsController@add_alliance',
		'middleware' => 'permission:coalitions.manage'
	]);

	Route::get('/coalitions/manage/alliance/{alliance}/remove', [
		'as' => 'coalitions.remove_alliance',
		'uses' => 'CoalitionsController@remove_alliance',
		'middleware' => 'permission:coalitions.manage'
	]);


	# Sovereignty


	Route::get('intelligence/sovereignty', [
		'as' => 'sovereignty.index',
		'uses' => 'SovController@index',
		'middleware' => 'permission:sovereignty.view',
	]);



	# Moons!

	Route::get('moons/2020/import/dscan', [
		'as' => 'moons.index',
		'uses' => 'MoonScanController@index',
		'middleware' => 'permission:moon_dscan.use',
	]);

	Route::get('moons/compare', [
		'as' => 'moons.moons_compare',
		'uses' => 'MoonScanController@moons_compare',
		'middleware' => 'permission:moon.view.moons',
	]);

	Route::get('moons/2020', [
		'as' => 'moons.moons',
		'uses' => 'MoonScanController@new_moons',
		'middleware' => 'permission:moon.view.moons',
	]);

	Route::get('moons/2017', [
		'as' => 'moons.old_moons',
		'uses' => 'MoonScanController@old_moons',
		'middleware' => 'permission:moon.view.moons',
	]);

	Route::get('intelligence/moons/2017/regional_report', [
		'as' => 'moons.regional_old_report',
		'uses' => 'MoonScanController@regional_old_report',
		'middleware' => 'permission:moon.regional.report.view',
	]);

	Route::get('intelligence/moons/2020/regional_report', [
		'as' => 'moons.regional_report',
		'uses' => 'MoonScanController@regional_report',
		'middleware' => 'permission:moon.regional.report.view',
	]);

	Route::get('intelligence/moons/2017/regional_view/{region_id}', [
		'as' => 'moons.regional_old_view',
		'uses' => 'MoonScanController@regional_old_view',
		'middleware' => 'permission:moon.regional.report.view',
	]);

	Route::get('intelligence/moons/2020/regional_view/{region_id}', [
		'as' => 'moons.regional_view',
		'uses' => 'MoonScanController@regional_view',
		'middleware' => 'permission:moon.regional.report.view',
	]);

	Route::get('moons/2020/{moon_id}/view', [
		'as' => 'moons.view_moon',
		'uses' => 'MoonScanController@view_new_moon',
		'middleware' => 'permission:moon.view.moons',
	]);

	Route::get('moons/2017/{moon_id}/view', [
		'as' => 'moons.view_old_moon',
		'uses' => 'MoonScanController@view_old_moon',
		'middleware' => 'permission:moon.view.moons',
	]);

	Route::get('moons/2020/regions', [
		'as' => 'moons.regions',
		'uses' => 'MoonScanController@regions',
		'middleware' => 'permission:moon_dscan.use',
	]);

	Route::get('moons/2020/region/{region_id}', [
		'as' => 'moons.systems',
		'uses' => 'MoonScanController@systems',
		'middleware' => 'permission:moon_dscan.use',
	]);

	Route::get('moons/2020/system/{system_id}', [
		'as' => 'moons.system',
		'uses' => 'MoonScanController@system',
		'middleware' => 'permission:moon.view.moons',

	]);

	Route::get('moons/2020/constellation/{constellation_id}', [
		'as' => 'moons.constellation',
		'uses' => 'MoonScanController@constellation',
		'middleware' => 'permission:moon_dscan.use',
	]);

	Route::post('/moons/2020/import/dscan/post', 'MoonScanController@dscan');
	Route::post('/moons/2020/import/adash_import/post', 'MoonScanController@adash_import');


	Route::get('market/minerals', [
		'as' => 'refined.minerals',
		'uses' => 'RefinedMaterialsController@minerals',
	]);

	Route::get('market/minerals/{type_id}/history', [
		'as' => 'refined.minerals_history',
		'uses' => 'RefinedMaterialsController@mineralsHistory',
	]);

	Route::get('market/ice', [
		'as' => 'refined.ice',
		'uses' => 'RefinedMaterialsController@ice',
	]);

	Route::get('market/ice/{type_id}/history', [
		'as' => 'refined.ice_history',
		'uses' => 'RefinedMaterialsController@iceHistory',
	]);

	Route::get('market/moon_goo', [
		'as' => 'refined.moons',
		'uses' => 'RefinedMaterialsController@moons',
	]);

	Route::get('market/moon_goo/{type_id}/history', [
		'as' => 'refined.moons_history',
		'uses' => 'RefinedMaterialsController@moonsHistory',
	]);

	Route::get('market/minerals/{mineral_id}/view', [
		'as' => 'refined.mineral.view',
		'uses' => 'RefinedMaterialsController@viewminerals',
	]);

	Route::get('market/ice/{ice_id}/view', [
		'as' => 'refined.ice.view',
		'uses' => 'RefinedMaterialsController@viewice',
	]);

	Route::get('market/moon_goo/{moon_id}/view', [
		'as' => 'refined.moons.view',
		'uses' => 'RefinedMaterialsController@viewmoons',
	]);


	Route::get('intelligence/stagers/', [
		'as' => 'stager.index',
		'uses' => 	'StagerController@index',
		'middleware' => 'permission:stager.view'
	]);

	Route::post('intelligence/stagers/add_system', [
		'as' => 'stager.add_system',
		'uses' => 	'StagerController@add_system',
		'middleware' => 'permission:stager.add'
	]);

	Route::delete('intelligence/stagers/{id}/remove', [
		'as' => 'stager.remove',
		'uses' => 	'StagerController@remove',
		'middleware' => 'permission:stager.remove'
	]);

	Route::get('intelligence/stagers/update/standings', [
		'as' => 'stager.update_standings',
		'uses' => 	'StagerController@update_standings',
		'middleware' => 'permission:stager.add'
	]);

	Route::get('metadata/', [
		'as' => 'metadata.index',
		'uses' => 	'MetaDataDumpController@index',
		'middleware' => 'permission:structure.import.meta.data'
	]);

	Route::post('metadata/dump', 'MetaDataDumpController@metaDataDump');

	Route::get('sig/management', [
		'as' => 'sig.management.index',
		'uses' => 	'SigManagementController@index',
		'middleware' => 'permission:sig.management'
	]);

	Route::post('sig/management/import/scouts', [
		'as' => 'sig.management.import_scouts',
		'uses' => 	'SigManagementController@import_scouts',
		'middleware' => 'permission:sig.management'
	]);

	Route::get('intelligence/military/region', [
		'as' => 'regional.report.index',
		'uses' => 	'RegionalReportController@index',
		'middleware' => 'permission:regional.report.view'
	]);

	Route::get('intelligence/military/region/{region_name}/view', [
		'as' => 'regional.report.view',
		'uses' => 	'RegionalReportController@view',
		'middleware' => 'permission:regional.report.view'
	]);


	Route::get('report/package/manager/monthly', [
		'as' => 'package_manager.monthly_index',
		'uses' => 	'PackageManagerController@monthly_index',
		'middleware' => 'permission:package.monthly.report'
	]);

	Route::get('report/package/manager/monthly/{month_year}', [
		'as' => 'package_manager.month_year_view',
		'uses' => 	'PackageManagerController@month_year_view',
		'middleware' => 'permission:package.monthly.report'
	]);

	Route::get('report/package/manager/monthly/export/{month_year}', [
		'as' => 'package_manager.export_monthly_stats',
		'uses' => 	'PackageManagerController@export_monthly_stats',
		'middleware' => 'permission:package.monthly.report.manage'
	]);

	Route::get('report/package/manager/monthly/pay/{month_year}', [
		'as' => 'package_manager.mark_month_as_paid',
		'uses' => 	'PackageManagerController@mark_month_as_paid',
		'middleware' => 'permission:package.monthly.report.manage'
	]);

	Route::get('report/package/manager/monthly/{month_year}/{at_username}', [
		'as' => 'package_manager.month_year_user_view',
		'uses' => 	'PackageManagerController@month_year_user_view',
		'middleware' => 'permission:manage.package'
	]);

	Route::post('acl/audit/post', [
		'as' => 'acl_audit.post',
		'uses' => 	'ACLCheckerController@auditACLCharacters',
		'middleware' => 'permission:acl_checker.view'
	]);

	Route::get('acl/audit/', [
		'as' => 'acl_audit.index',
		'uses' => 	'ACLCheckerController@index',
		'middleware' => 'permission:acl_checker.view'
	]);

	Route::get('acl/audit/{id}/view', [
		'as' => 'acl_audit.view',
		'uses' => 	'ACLCheckerController@view',
		'middleware' => 'permission:acl_checker.view'
	]);

	Route::post('acl/audit/{id}/set/name', [
		'as' => 'acl_audit.add_name',
		'uses' => 	'ACLCheckerController@addName',
		'middleware' => 'permission:acl_checker.view'
	]);

	Route::post('fittings/post', [
		'as' => 'fittings.post',
		'uses' => 	'FittingsController@storeFitting',
		'middleware' => 'permission:fittings.view'
	]);

	Route::get('fittings/', [
		'as' => 'fittings.index',
		'uses' => 	'FittingsController@index',
		'middleware' => 'permission:fittings.view'
	]);

	Route::get('fittings/view/{id}', [
		'as' => 'fittings.view',
		'uses' => 	'FittingsController@view',
		'middleware' => 'permission:fittings.view'
	]);


	Route::post('intelligence/capital/killmail/post', [
		'as' => 'killmail.post',
		'uses' => 	'killmailController@storePost',
		'middleware' => 'permission:capital.tracking'
	]);

	Route::get('intelligence/capital/tracking', [
		'as' => 'killmail.index',
		'uses' => 'killmailController@index',
		'middleware' => 'permission:capital.tracking'
	]);

	Route::get('intelligence/capital/tracking/alliance/{alliance_id}/view', [
		'as' => 'killmail.view_alliance',
		'uses' => 'killmailController@view_alliance',
		'middleware' => 'permission:capital.tracking'
	]);


	Route::get('/report/standings', [
		'as' => 'standings.index',
		'uses' => 'AllianceStandingsController@index',
		//'middleware' => 'permission:system.indices.view'
	]);

	Route::get('/intelligence/standings/enemy', [
		'as' => 'enemy_standings.index',
		'uses' => 'AllianceEnemyStandingsController@index',
		'middleware' => 'permission:view.enemy.standings'
	]);

	Route::get('/intelligence/standings/enemy/{alliance_id}/view', [
		'as' => 'enemy_standings.view',
		'uses' => 'AllianceEnemyStandingsController@view',
		'middleware' => 'permission:view.enemy.standings'
	]);

	Route::get('/intelligence/indices', [
		'as' => 'indices.index',
		'uses' => 'SystemIndicesController@index',
		'middleware' => 'permission:system.indices.view'
	]);

	Route::get('/upwell/rigs', [
		'as' => 'upwell.rigs',
		'uses' => 'UpwellRigsController@rigs',
		//'middleware' => 'permission:statistics.view'
	]);

	Route::get('/upwell/rig/{type_id}', [
		'as' => 'upwell.rig',
		'uses' => 'UpwellRigsController@rig',
		//'middleware' => 'permission:statistics.view'
	]);

	Route::get('/market/modules', [
		'as' => 'upwell.modules',
		'uses' => 'UpwellRigsController@modules',
		//'middleware' => 'permission:statistics.view'
	]);

	Route::get('/market/modules/{id}/view', [
		'as' => 'upwell.view_modules',
		'uses' => 'UpwellRigsController@view_modules',
		//'middleware' => 'permission:statistics.view'
	]);

	Route::get('/market/salvage', [
		'as' => 'upwell.salvage',
		'uses' => 'UpwellRigsController@salvage',
		//'middleware' => 'permission:statistics.view'
	]);

	Route::get('/market/salvage/{id}/view', [
		'as' => 'upwell.view_salvage',
		'uses' => 'UpwellRigsController@view_salvage',
		//'middleware' => 'permission:statistics.view'
	]);

	Route::get('/entosis/campaigns', [
		'as' => 'entosis.campaigns',
		'uses' => 'EntosisController@campaigns',
	]);

	Route::get('/entosis/campaigns/active', [
		'as' => 'entosis.active_campaigns',
		'uses' => 'EntosisController@active_campaigns',
	]);

	Route::post('/entosis/campaigns/add_to_pending', [
		'as' => 'entosis.add_pending',
		'uses' => 'EntosisController@add_campaign_to_pending',
		//'middleware' => 'permission:taskmanager.manage'
	]);

	Route::get('/entosis/campaigns/{id}/dispatch', [
		'as' => 'entosis.dispatch',
		'uses' => 'EntosisController@dispatch',
		//'middleware' => 'permission:taskmanager.manage'
	]);

	Route::get('/entosis/campaigns/{id}/remove', [
		'as' => 'entosis.remove_from_dispatch',
		'uses' => 'EntosisController@remove_from_dispatch',
		//'middleware' => 'permission:taskmanager.manage'
	]);

	Route::get('/entosis/campaigns/{id}/complete', [
		'as' => 'entosis.complete',
		'uses' => 'EntosisController@complete',
		//'middleware' => 'permission:taskmanager.manage'
	]);

	Route::get('/entosis/campaigns/{id}/view', [
		'as' => 'entosis.view_campaign',
		'uses' => 'EntosisController@view_campaign',
		//'middleware' => 'permission:taskmanager.manage'
	]);

	Route::get('/entosis/campaigns/{id}/view/registered/hackers', [
		'as' => 'entosis.view_campaign_registered_hackers',
		'uses' => 'EntosisController@view_campaign_registered_hackers',
		//'middleware' => 'permission:taskmanager.manage'
	]);

	Route::get('/entosis/campaigns/{id}/view/registered/scouts', [
		'as' => 'entosis.view_campaign_registered_scouts',
		'uses' => 'EntosisController@view_campaign_registered_scouts',
		//'middleware' => 'permission:taskmanager.manage'
	]);

	Route::get('/entosis/campaigns/{id}/view/registered/nodes', [
		'as' => 'entosis.view_campaign_registered_nodes',
		'uses' => 'EntosisController@view_campaign_registered_nodes',
		//'middleware' => 'permission:taskmanager.manage'
	]);

	Route::post('/entosis/campaigns/{id}/view/registered/nodes/allocate', [
		'as' => 'entosis.view_campaign_registered_nodes_allocate',
		'uses' => 'EntosisController@view_campaign_registered_nodes_allocate',
		//'middleware' => 'permission:taskmanager.manage'
	]);


	Route::post('/entosis/campaigns/{id}/register/scout', [
		'as' => 'entosis.register_scout_to_campaign',
		'uses' => 'EntosisController@register_scout_to_campaign',
		//'middleware' => 'permission:taskmanager.manage'
	]);

	Route::post('/entosis/campaigns/{id}/register/hacker', [
		'as' => 'entosis.register_hacker_to_campaign',
		'uses' => 'EntosisController@register_hacker_to_campaign',
		//'middleware' => 'permission:taskmanager.manage'
	]);

	Route::post('/entosis/campaigns/{id}/node/add', [
		'as' => 'entosis.add_node_to_campaign',
		'uses' => 'EntosisController@add_node_to_campaign',
		//'middleware' => 'permission:taskmanager.manage'
	]);

	Route::post('/entosis/campaigns/{id}/node/update/{node_id}/status', [
		'as' => 'entosis.update_node_status_for_campaign',
		'uses' => 'EntosisController@update_node_status_for_campaign',
		//'middleware' => 'permission:taskmanager.manage'
	]);

	Route::post('/entosis/campaigns/{id}/node/update/{node_id}/time', [
		'as' => 'entosis.update_node_time_for_campaign',
		'uses' => 'EntosisController@update_node_time_for_campaign',
		//'middleware' => 'permission:taskmanager.manage'
	]);

	Route::post('/entosis/campaigns/{id}/node/allocate/{node_id}/{character_id}', [
		'as' => 'entosis.allocate_node_to_character_for_campaign',
		'uses' => 'EntosisController@allocate_node_to_character_for_campaign',
		//'middleware' => 'permission:taskmanager.manage'
	]);

	Route::get('intelligence/structure/activity/tracker/log', [
		'as' => 'activitytracker.index',
		'uses' => 'ActivityTrackerController@index',
		'middleware' => 'permission:activitytracker.view'
	]);

	Route::get('intelligence/structure/tracker/metrics/', [
		'as' => 'activitytracker.metrics_index',
		'uses' => 'ActivityTrackerController@activity_metrics_index',
		'middleware' => 'permission:activitytracker.view'
	]);

	Route::get('intelligence/structure/metrics/{month_year}', [
		'as' => 'activitytracker.metrics_monthly_index',
		'uses' => 	'ActivityTrackerController@month_year_view',
		'middleware' => 'permission:activitytracker.view'
	]);
	Route::get('intelligence/structure/metrics/{month_year}/{at_username}', [
		'as' => 'activitytracker.month_year_user_view',
		'uses' => 	'ActivityTrackerController@month_year_user_view',
		'middleware' => 'permission:activitytracker.view'
	]);

	Route::get('/tasks/outstanding', [
		'as' => 'taskmanager.outstanding',
		'uses' => 'TaskManagerController@outstanding',
		'middleware' => 'permission:tasks.outstanding.view'
	]);

	Route::get('/tasks/inprogress', [
		'as' => 'taskmanager.inprogress',
		'uses' => 'TaskManagerController@inprogress',
		'middleware' => 'permission:tasks.outstanding.view'
	]);

	Route::get('/tasks/overview', [
		'as' => 'taskmanager.overview',
		'uses' => 'TaskManagerController@overview',
		'middleware' => 'permission:taskmanager.manage'

	]);

	Route::post('/tasks/add/pending', [
		'as' => 'taskmanager.add_pending',
		'uses' => 'TaskManagerController@add_task_to_pending',
		'middleware' => 'permission:taskmanager.manage'
	]);

	Route::get('/tasks/{id}/dispatch', [
		'as' => 'taskmanager.dispatch',
		'uses' => 'TaskManagerController@dispatch',
		'middleware' => 'permission:taskmanager.manage'
	]);

	Route::get('/tasks/{id}/dispatch_from_system', [
		'as' => 'taskmanager.dispatch_from_system',
		'uses' => 'TaskManagerController@dispatch_from_system',
		'middleware' => 'permission:taskmanager.manage'
	]);

	Route::get('/tasks/{id}/claim', [
		'as' => 'taskmanager.claim',
		'uses' => 'TaskManagerController@claim',
		'middleware' => 'permission:tasks.outstanding.view'
	]);

	Route::get('/tasks/{id}/unclaim', [
		'as' => 'taskmanager.unclaim',
		'uses' => 'TaskManagerController@unclaim',
		'middleware' => 'permission:tasks.outstanding.view'
	]);

	Route::get('/tasks/{id}/complete', [
		'as' => 'taskmanager.complete',
		'uses' => 'TaskManagerController@complete',
		'middleware' => 'permission:tasks.outstanding.view'
	]);

	Route::get('/tasks/dispatch/all', [
		'as' => 'taskmanager.dispatch_all',
		'uses' => 'TaskManagerController@dispatch_all',
		'middleware' => 'permission:taskmanager.manage'
	]);

	Route::get('/tasks/remove/all', [
		'as' => 'taskmanager.remove_all',
		'uses' => 'TaskManagerController@remove_all',
		'middleware' => 'permission:taskmanager.manage'
	]);

	Route::get('/tasks/{id}/remove', [
		'as' => 'taskmanager.remove_from_dispatch',
		'uses' => 'TaskManagerController@remove_from_dispatch',
		'middleware' => 'permission:taskmanager.manage'
	]);

	Route::get('/tasks/{id}/reallocate', [
		'as' => 'taskmanager.return_to_outstanding',
		'uses' => 'TaskManagerController@return_to_outstanding',
		'middleware' => 'permission:taskmanager.manage'
	]);

	Route::get('/observation', [
		'as' => 'observation.index',
		'uses' => 'ObservationController@index',
		'middleware' => 'permission:observation.create'
	]);

	Route::post('/observation/create', [
		'as' => 'observation.create',
		'uses' => 'ObservationController@create',
		'middleware' => 'permission:observation.create'
	]);

	Route::get('/observation/list', [
		'as' => 'observation.list',
		'uses' => 'ObservationController@list',
		'middleware' => 'permission:observation.manage'
	]);

	Route::get('/observation/removed', [
		'as' => 'observation.removed',
		'uses' => 'ObservationController@removed',
		'middleware' => 'permission:observation.manage'
	]);

	Route::get('/observation/{id}/view', [
		'as' => 'observation.view',
		'uses' => 'ObservationController@view',
		'middleware' => 'permission:observation.manage'
	]);

	Route::post('/observation/{id}/reviewed', [
		'as' => 'observation.reviewed',
		'uses' => 'ObservationController@reviewed',
		'middleware' => 'permission:observation.manage'
	]);

	Route::get('/observation/{id}/remove', [
		'as' => 'observation.remove',
		'uses' => 'ObservationController@remove',
		'middleware' => 'permission:observation.manage'
	]);

	Route::get('/trackMe', [
		'as' => 'esi.trackMe',
		'uses' => 'TrackerController@trackMe',
		'middleware' => 'permission:trackme.usage'
	]);

	Route::get('/structure/setwaypoint/{structure_id}', [
		'as' => 'structures.setwaypoint',
		'uses' => 'SetWayPointController@setWayPoint',
		'middleware' => 'permission:set.waypoint'
	]);

	Route::get('/structure/setwaypoint_system/{system_id}', [
		'as' => 'structures.setwaypoint_system',
		'uses' => 'SetWayPointController@setWayPointSystem',
		'middleware' => 'permission:set.waypoint'
	]);

	Route::get('/structure/information/{structure_id}', [
		'as' => 'structures.information',
		'uses' => 'SetWayPointController@openInformation',
		'middleware' => 'permission:system.view'
	]);

	Route::get('/route/planning', [
		'as' => 'route.planning',
		'uses' => 'ScoutController@routePlanning',
	]);

	Route::get('/route/planning/update/mine', [
		'as' => 'route.update_mine',
		'uses' => 'ScoutController@updateMyCharactersLocation',
	]);

	Route::get('/report/recon/tracking', [
		'as' => 'route.tracking',
		'uses' => 'ScoutController@scoutTracking',
		'middleware' => 'permission:scout.tracking.view'
	]);

	Route::get('/report/tracking/recon/tracking/update/scouts', [
		'as' => 'route.update_scouts',
		'uses' => 'ScoutController@updateAllCharactersLocations',
		'middleware' => 'permission:scout.tracking.view'
	]);

	Route::get('/route/planning/route', [
		'as' => 'route.find_shortest_route_mine',
		'uses' => 'ScoutController@findShortestRouteMine',
	]);

	Route::get('autocomplete/universe', [
		'as' => 'autocomplete.universe',
		'uses' => 'AutoCompleteController@universe',
		'middleware' => 'permission:system.view'
	]);

	Route::get('autocomplete/systems', [
		'as' => 'autocomplete.systems',
		'uses' => 'AutoCompleteController@systems',
		'middleware' => 'permission:system.view'
	]);
	Route::get('autocomplete/constellations', [
		'as' => 'autocomplete.constellations',
		'uses' => 'AutoCompleteController@constellations',
		'middleware' => 'permission:system.view'
	]);

	Route::get('autocomplete/regions', [
		'as' => 'autocomplete.regions',
		'uses' => 'AutoCompleteController@regions',
		'middleware' => 'permission:system.view'
	]);


	Route::get('autocomplete/corporations', [
		'as' => 'autocomplete.corporations',
		'uses' => 'AutoCompleteController@corporations',
		'middleware' => 'permission:system.view'
	]);

	Route::get('autocomplete/alliances', [
		'as' => 'autocomplete.alliances',
		'uses' => 'AutoCompleteController@alliances',
		'middleware' => 'permission:system.view'
	]);

	Route::get('autocomplete/alliance_tickers', [
		'as' => 'autocomplete.alliance_tickers',
		'uses' => 'AutoCompleteController@alliance_tickers',
		'middleware' => 'permission:system.view'
	]);

	Route::get('route/planning/autocomplete', [
		'as' => 'route.planning_mine_autocomplete',
		'uses' => 'ScoutController@mineAutocomplete',
		'middleware' => 'permission:system.view'
	]);

	Route::get('esi', [
		'as' => 'esi.index',
		'uses' => 'ESIController@index',
	]);

	Route::get('esi/all_tokens', [
		'as' => 'esi.all',
		'uses' => 'ESIController@all',
		'middleware' => 'permission:esi.view.tokens'
	]);

	Route::get('esi/token/add', [
		'as' => 'esi.token_add',
		'uses' => 'ESIController@redirectToProvider',
	]);

	Route::get('esi/token/add/corporation', [
		'as' => 'esi.token_add_corporation',
		'uses' => 'ESIController@redirectToProviderCorporation',
	]);

	Route::get('esi/callback', 'ESIController@handleProviderCallback');

	Route::get('regions/', [
		'as' => 'solar.regions',
		'uses' => 'SolarSystemController@regions',
		'middleware' => 'permission:regions.view'
	]);


	Route::get('universe/', [
		'as' => 'solar.universe',
		'uses' => 'SolarSystemController@universe',
		'middleware' => 'permission:universe.view'
	]);

	Route::get('region/{region_id}/', [
		'as' => 'solar.region',
		'uses' => 'SolarSystemController@region',
		'middleware' => 'permission:regions.view'
	]);

	Route::get('constellation/{constellation_id}/', [
		'as' => 'solar.constellation',
		'uses' => 'SolarSystemController@constellation',
		'middleware' => 'permission:constellation.view'
	]);

	Route::get('system/{system_id}', [
		'as' => 'solar.system',
		'uses' => 'SolarSystemController@system',
		'middleware' => 'permission:system.view'
	]);

	Route::get('system/{system_id}/empty', [
		'as' => 'solar.system_empty',
		'uses' => 'SolarSystemController@system_empty',
		'middleware' => 'permission:system.view'
	]);

	Route::post('dscan/system/{system_id}', 'SolarSystemController@storeDscan');

	Route::get('intelligence/alliances/', [
		'as' => 'alliances.index',
		'uses' => 'AllianceController@index',
		'middleware' => 'permission:alliances.view'
	]);

	Route::get('intelligence/alliances/{alliance_id}/view', [
		'as' => 'alliance.view',
		'uses' => 'AllianceController@view',
		'middleware' => 'permission:alliances.view'
	]);

	Route::get('corporations/', [
		'as' => 'corporations.index',
		'uses' => 'CorporationsController@index',
		'middleware' => 'permission:corporations.view'
	]);

	Route::get('corporations/{corporation_id}/view', [
		'as' => 'corporation.view',
		'uses' => 'CorporationsController@view',
		'middleware' => 'permission:corporations.view'
	]);


	Route::get('structures/', [
		'as' => 'structures.index',
		'uses' => 'StructuresController@index',
		'middleware' => 'permission:structures.view'
	]);

	Route::get('search/structures/', [
		'as' => 'structures.search',
		'uses' => 'StructureSearchController@index',
		'middleware' => 'permission:structure.single.search'
	]);


	Route::get('structures/orphans/', [
		'as' => 'structures.orphans',
		'uses' => 'StructuresController@orphans',
		'middleware' => 'permission:structures.view'
	]);

	Route::get('structures/packageless/', [
		'as' => 'structures.packageless',
		'uses' => 'StructuresController@packageless',
		'middleware' => 'permission:deliver.package'
	]);

	Route::get('package/structure/review', [
		'as' => 'structures.package_review',
		'uses' => 'StructuresController@package_review',
		'middleware' => 'permission:manage.package'
	]);

	Route::get('structures/destroyed/', [
		'as' => 'structures.destroyed',
		'uses' => 'StructuresController@destroyed',
		'middleware' => 'permission:structures.view'
	]);

	Route::get('structures/moon_drill/', [
		'as' => 'structures.moon_drill',
		'uses' => 'StructuresController@moon_drill',
		'middleware' => 'permission:structures.view'
	]);


	Route::get('structures/vulnerable/', [
		'as' => 'structures.vulnerable',
		'uses' => 'StructuresController@vulnerable',
		'middleware' => 'permission:structures.view'
	]);

	Route::get('structures/abandoned', [
		'as' => 'structures.abandoned',
		'uses' => 'StructuresController@abandoned',
		'middleware' => 'permission:structures.view'
	]);

	Route::get('structures/export/', [
		'as' => 'structures.export_to_excel',
		'uses' => 'StructuresController@exportToExcel',
		'middleware' => 'permission:export.structures.to.excel'
	]);


	Route::get('structures/{hash}/view', [
		'as' => 'structures.view',
		'uses' => 'StructuresController@view',
		'middleware' => 'permission:view.structure.single'
	]);

	Route::post('dscan/structure/{structure_id}', 'StructuresController@storeStructureDscan');
	Route::post('dscan/structure/fitting/{structure_id}', 'StructuresController@storeStructureFittingDscan');
	Route::post('structure/vulnerability/{structure_id}', 'StructuresController@setVulnerabilityWindow');
	Route::post('structure/moon_anchored/{structure_id}', 'StructuresController@setAnchoredMoon');

	Route::get('structures/autocomplete', [
		'as' => 'structures.autocomplete',
		'uses' => 'StructuresController@autocomplete',
		'middleware' => 'permission:structure.metadata.update'
	]);

	Route::get('structures/{structure_id}/hitlist/add', [
		'as' => 'structures.hitlist_add',
		'uses' => 'StructuresController@addToHitlist',
		'middleware' => 'permission:structure.hitlist'
	]);

	Route::get('structures/{structure_id}/hitlist/remove', [
		'as' => 'structures.hitlist_remove',
		'uses' => 'StructuresController@removeFromHitlist',
		'middleware' => 'permission:structure.hitlist'
	]);

	Route::get('structures/hitlist/clear', [
		'as' => 'structures.hitlist_clear',
		'uses' => 'StructuresController@clearHitlist',
		'middleware' => 'permission:structure.hitlist'
	]);

	Route::get('structures/hitlist/export', [
		'as' => 'structures.hitlist_export',
		'uses' => 'StructuresController@exportHitlist',
		'middleware' => 'permission:structure.hitlist'
	]);

	Route::get('structures/hitlist/export/destroyed', [
		'as' => 'structures.hitlist_export_destroyed',
		'uses' => 'StructuresController@exportHitlistDestroyed',
		'middleware' => 'permission:structure.hitlist'
	]);


	Route::get('structures/{structure_id}/state/high_power', [
		'as' => 'structures.state_high_power',
		'uses' => 'StructuresController@stateHighPower',
		'middleware' => 'permission:structure.metadata.update'
	]);

	Route::get('structures/{structure_id}/state/low_power', [
		'as' => 'structures.state_low_power',
		'uses' => 'StructuresController@stateLowPower',
		'middleware' => 'permission:structure.metadata.update'
	]);

	Route::get('structures/{structure_id}/state/abandoned', [
		'as' => 'structures.state_abandoned',
		'uses' => 'StructuresController@stateAbandoned',
		'middleware' => 'permission:structure.metadata.update'
	]);

	Route::get('structures/{structure_id}/package/delivered', [
		'as' => 'structures.package_delivered',
		'uses' => 'StructuresController@packageDelivered',
		'middleware' => 'permission:deliver.package'
	]);



	Route::get('structures/{structure_id}/state/anchoring', [
		'as' => 'structures.state_anchoring',
		'uses' => 'StructuresController@stateAnchoring',
		'middleware' => 'permission:structure.metadata.update'
	]);

	Route::get('structures/{structure_id}/status/unanchoring', [
		'as' => 'structures.status_unanchoring',
		'uses' => 'StructuresController@statusUnanchoring',
		'middleware' => 'permission:structure.metadata.update'
	]);

	Route::get('structures/{structure_id}/status/reinforced', [
		'as' => 'structures.status_reinforced',
		'uses' => 'StructuresController@statusReinforced',
		'middleware' => 'permission:structure.metadata.update'
	]);

	Route::get('structures/{structure_id}/status/reinforced/clear', [
		'as' => 'structures.status_reinforced_clear',
		'uses' => 'StructuresController@statusReinforcedClear',
		'middleware' => 'permission:structure.metadata.update'
	]);

	Route::get('structures/{structure_id}/fitting', [
		'as' => 'structures.fitting',
		'uses' => 'StructuresController@fitting',
		'middleware' => 'permission:structure.metadata.update'
	]);

	Route::get('structures/{structure_id}/cored', [
		'as' => 'structures.cored',
		'uses' => 'StructuresController@cored',
		'middleware' => 'permission:structure.metadata.update'
	]);

	Route::get('structures/{structure_id}/destroy', [
		'as' => 'structures.destroy',
		'uses' => 'StructuresController@destroy',
		'middleware' => 'permission:structure.destroy'

	]);

	Route::get('structure/merge/{structure_id}/view', [
		'as' => 'merge.view',
		'uses' => 'MergeStructureController@view_structure',
		'middleware' => 'permission:structure.merge'
	]);

	Route::get('structure/merge/{old_structure_id_md5}/{new_structure_id_md5}/merge', [
		'as' => 'merge.structure',
		'uses' => 'MergeStructureController@merge_structure',
		'middleware' => 'permission:structure.merge'
	]);

		Route::get('structure/merge/{old_structure_id_md5}/{new_structure_id_md5}/merge/with_fit', [
		'as' => 'merge.structure_with_fit',
		'uses' => 'MergeStructureController@merge_structure_with_fit',
		'middleware' => 'permission:structure.merge'
	]);

	Route::get('structures/duplicates', [
		'as' => 'merge.duplicates',
		'uses' => 'MergeStructureController@duplicate_structures',
		'middleware' => 'permission:structure.merge'
	]);








    /**
     * Dashboard
     */

    Route::get('/', [
    	'as' => 'dashboard',
    	'uses' => 'DashboardController@index'
    ]);

    Route::get('/help', [
    	'as' => 'help.index',
    	'uses' => 'HelpController@index',
    ]);



    /**
     * User Profile
     */

    Route::get('profile', [
    	'as' => 'profile',
    	'uses' => 'ProfileController@index'
    ]);

    Route::get('profile/activity', [
    	'as' => 'profile.activity',
    	'uses' => 'ProfileController@activity'
    ]);

    Route::put('profile/details/update', [
    	'as' => 'profile.update.details',
    	'uses' => 'ProfileController@updateDetails'
    ]);

    Route::post('profile/avatar/update', [
    	'as' => 'profile.update.avatar',
    	'uses' => 'ProfileController@updateAvatar'
    ]);

    Route::post('profile/avatar/update/external', [
    	'as' => 'profile.update.avatar-external',
    	'uses' => 'ProfileController@updateAvatarExternal'
    ]);

    Route::put('profile/login-details/update', [
    	'as' => 'profile.update.login-details',
    	'uses' => 'ProfileController@updateLoginDetails'
    ]);

    Route::post('profile/two-factor/enable', [
    	'as' => 'profile.two-factor.enable',
    	'uses' => 'ProfileController@enableTwoFactorAuth'
    ]);

    Route::post('profile/two-factor/disable', [
    	'as' => 'profile.two-factor.disable',
    	'uses' => 'ProfileController@disableTwoFactorAuth'
    ]);

    Route::get('profile/sessions', [
    	'as' => 'profile.sessions',
    	'uses' => 'ProfileController@sessions'
    ]);

    Route::delete('profile/sessions/{session}/invalidate', [
    	'as' => 'profile.sessions.invalidate',
    	'uses' => 'ProfileController@invalidateSession'
    ]);

    /**
     * User Management
     */

    Route::get('active-users', 'ActiveUsersController@index')->name('active-users');

    Route::get('user', [
    	'as' => 'user.list',
    	'uses' => 'UsersController@index'
    ]);

    Route::get('user/create', [
    	'as' => 'user.create',
    	'uses' => 'UsersController@create'
    ]);

    Route::post('user/create', [
    	'as' => 'user.store',
    	'uses' => 'UsersController@store'
    ]);

    Route::get('user/{user}/show', [
    	'as' => 'user.show',
    	'uses' => 'UsersController@view'
    ]);

    Route::get('user/{user}/edit', [
    	'as' => 'user.edit',
    	'uses' => 'UsersController@edit'
    ]);

    Route::put('user/{user}/update/details', [
    	'as' => 'user.update.details',
    	'uses' => 'UsersController@updateDetails'
    ]);

    Route::put('user/{user}/update/login-details', [
    	'as' => 'user.update.login-details',
    	'uses' => 'UsersController@updateLoginDetails'
    ]);

    Route::delete('user/{user}/delete', [
    	'as' => 'user.delete',
    	'uses' => 'UsersController@delete'
    ]);

    Route::post('user/{user}/update/avatar', [
    	'as' => 'user.update.avatar',
    	'uses' => 'UsersController@updateAvatar'
    ]);

    Route::post('user/{user}/update/avatar/external', [
    	'as' => 'user.update.avatar.external',
    	'uses' => 'UsersController@updateAvatarExternal'
    ]);

    Route::get('user/{user}/sessions', [
    	'as' => 'user.sessions',
    	'uses' => 'UsersController@sessions'
    ]);

    Route::delete('user/{user}/sessions/{session}/invalidate', [
    	'as' => 'user.sessions.invalidate',
    	'uses' => 'UsersController@invalidateSession'
    ]);

    Route::post('user/{user}/two-factor/enable', [
    	'as' => 'user.two-factor.enable',
    	'uses' => 'UsersController@enableTwoFactorAuth'
    ]);

    Route::post('user/{user}/two-factor/disable', [
    	'as' => 'user.two-factor.disable',
    	'uses' => 'UsersController@disableTwoFactorAuth'
    ]);

    /**
     * Roles & Permissions
     */

    Route::get('role', [
    	'as' => 'role.index',
    	'uses' => 'RolesController@index'
    ]);

    Route::get('role/create', [
    	'as' => 'role.create',
    	'uses' => 'RolesController@create'
    ]);

    Route::post('role/store', [
    	'as' => 'role.store',
    	'uses' => 'RolesController@store'
    ]);

    Route::get('role/{role}/edit', [
    	'as' => 'role.edit',
    	'uses' => 'RolesController@edit'
    ]);

    Route::put('role/{role}/update', [
    	'as' => 'role.update',
    	'uses' => 'RolesController@update'
    ]);

    Route::delete('role/{role}/delete', [
    	'as' => 'role.delete',
    	'uses' => 'RolesController@delete'
    ]);


    Route::post('permission/save', [
    	'as' => 'permission.save',
    	'uses' => 'PermissionsController@saveRolePermissions'
    ]);

    Route::resource('permission', 'PermissionsController');

    /**
     * Settings
     */

    Route::get('settings', [
    	'as' => 'settings.general',
    	'uses' => 'SettingsController@general',
    	'middleware' => 'permission:settings.general'
    ]);

    Route::post('settings/general', [
    	'as' => 'settings.general.update',
    	'uses' => 'SettingsController@update',
    	'middleware' => 'permission:settings.general'
    ]);

    Route::get('settings/auth', [
    	'as' => 'settings.auth',
    	'uses' => 'SettingsController@auth',
    	'middleware' => 'permission:settings.auth'
    ]);

    Route::post('settings/auth', [
    	'as' => 'settings.auth.update',
    	'uses' => 'SettingsController@update',
    	'middleware' => 'permission:settings.auth'
    ]);

	// Only allow managing 2FA if AUTHY_KEY is defined inside .env file
    if (env('AUTHY_KEY')) {
    	Route::post('settings/auth/2fa/enable', [
    		'as' => 'settings.auth.2fa.enable',
    		'uses' => 'SettingsController@enableTwoFactor',
    		'middleware' => 'permission:settings.auth'
    	]);

    	Route::post('settings/auth/2fa/disable', [
    		'as' => 'settings.auth.2fa.disable',
    		'uses' => 'SettingsController@disableTwoFactor',
    		'middleware' => 'permission:settings.auth'
    	]);
    }

    Route::post('settings/auth/registration/captcha/enable', [
    	'as' => 'settings.registration.captcha.enable',
    	'uses' => 'SettingsController@enableCaptcha',
    	'middleware' => 'permission:settings.auth'
    ]);

    Route::post('settings/auth/registration/captcha/disable', [
    	'as' => 'settings.registration.captcha.disable',
    	'uses' => 'SettingsController@disableCaptcha',
    	'middleware' => 'permission:settings.auth'
    ]);

    Route::get('settings/notifications', [
    	'as' => 'settings.notifications',
    	'uses' => 'SettingsController@notifications',
    	'middleware' => 'permission:settings.notifications'
    ]);

    Route::post('settings/notifications', [
    	'as' => 'settings.notifications.update',
    	'uses' => 'SettingsController@update',
    	'middleware' => 'permission:settings.notifications'
    ]);

    /**
     * Activity Log
     */

    Route::get('activity', [
    	'as' => 'activity.index',
    	'uses' => 'ActivityController@index'
    ]);

    Route::get('activity/user/{user}/log', [
    	'as' => 'activity.user',
    	'uses' => 'ActivityController@userActivity'
    ]);

});


/**
 * Installation
 */

$router->get('install', [
	'as' => 'install.start',
	'uses' => 'InstallController@index'
]);

$router->get('install/requirements', [
	'as' => 'install.requirements',
	'uses' => 'InstallController@requirements'
]);

$router->get('install/permissions', [
	'as' => 'install.permissions',
	'uses' => 'InstallController@permissions'
]);

$router->get('install/database', [
	'as' => 'install.database',
	'uses' => 'InstallController@databaseInfo'
]);

$router->get('install/start-installation', [
	'as' => 'install.installation',
	'uses' => 'InstallController@installation'
]);

$router->post('install/start-installation', [
	'as' => 'install.installation',
	'uses' => 'InstallController@installation'
]);

$router->post('install/install-app', [
	'as' => 'install.install',
	'uses' => 'InstallController@install'
]);

$router->get('install/complete', [
	'as' => 'install.complete',
	'uses' => 'InstallController@complete'
]);

$router->get('install/error', [
	'as' => 'install.error',
	'uses' => 'InstallController@error'
]);
