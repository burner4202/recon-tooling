<?php

namespace Vanguard\Providers;

use Carbon\Carbon;
use Vanguard\Repositories\Activity\ActivityRepository;
use Vanguard\Repositories\Activity\EloquentActivity;
use Vanguard\Repositories\Country\CountryRepository;
use Vanguard\Repositories\Country\EloquentCountry;
use Vanguard\Repositories\Permission\EloquentPermission;
use Vanguard\Repositories\Permission\PermissionRepository;
use Vanguard\Repositories\Role\EloquentRole;
use Vanguard\Repositories\Role\RoleRepository;
use Vanguard\Repositories\Session\DbSession;
use Vanguard\Repositories\Session\SessionRepository;
use Vanguard\Repositories\User\EloquentUser;
use Vanguard\Repositories\User\UserRepository;
use Illuminate\Support\ServiceProvider;
use Vanguard\TaskManager;
use Auth;
use Vanguard\ESITokens;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
    	Carbon::setLocale(config('app.locale'));
    	config(['app.name' => settings('app_name')]);
    	\Illuminate\Database\Schema\Builder::defaultStringLength(191);

    	//$outstanding_tasks = TaskManager::where('tm_state', 1)
    	//->count();

    	$this->bootGICESocialite();

    	

    	view()->composer('*', function($view)
    	{

    		if(Auth::check()) {

    			$active_characters = ESITokens::where('esi_user_id', Auth::user()->id)
    			->where('esi_active', 1)
    			->get();

    			$trackme_characters = array();
    			$trackme_characters = 		['' => 'Select Character'];

    			foreach($active_characters as $each_character) { 
    				$trackme_characters[$each_character['esi_name']] = $each_character['esi_name']; 
    			}

    			$view->with('trackme_characters', $trackme_characters);   

    		} else {

    			$trackme_characters = 		['' => 'Select Character'];
    			$view->with('trackme_characters', $trackme_characters);   

    		} 

    	});

    	//view()->share('outstanding_tasks', $outstanding_tasks);

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    	$this->app->singleton(UserRepository::class, EloquentUser::class);
    	$this->app->singleton(ActivityRepository::class, EloquentActivity::class);
    	$this->app->singleton(RoleRepository::class, EloquentRole::class);
    	$this->app->singleton(PermissionRepository::class, EloquentPermission::class);
    	$this->app->singleton(SessionRepository::class, DbSession::class);
    	$this->app->singleton(CountryRepository::class, EloquentCountry::class);

    	if ($this->app->environment('local')) {
    		$this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
    		$this->app->register(\Barryvdh\Debugbar\ServiceProvider::class);
    	}
    }

    private function bootGICESocialite()
    {
    	$socialite = $this->app->make('Laravel\Socialite\Contracts\Factory');
    	$socialite->extend(
    		'gice',
    		function ($app) use ($socialite) {
    			$config = $app['config']['services.gice'];
    			return $socialite->buildProvider(GICEProvider::class, $config);
    		}
    	);
    }
}
