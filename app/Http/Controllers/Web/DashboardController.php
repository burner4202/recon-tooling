<?php

/*
 * Goonswarm Federation Recon Tools
 *
 * Developed by scopehone <scopeh@gmail.com>
 * In conjuction with Natalya Spaghet & Mindstar Technology 
 *
 */

namespace Vanguard\Http\Controllers\Web;

use Vanguard\Http\Controllers\Controller;
use Vanguard\Repositories\Activity\ActivityRepository;
use Vanguard\Repositories\User\UserRepository;
use Vanguard\Support\Enum\UserStatus;
use Vanguard\KnownStructures;
use Vanguard\SolarSystems;
use Vanguard\TaskManager;
use Auth;
use Input;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * @var UserRepository
     */
    private $users;
    /**
     * @var ActivityRepository
     */
    private $activities;

    /**
     * DashboardController constructor.
     * @param UserRepository $users
     * @param ActivityRepository $activities
     */
    public function __construct(UserRepository $users, ActivityRepository $activities)
    {
    	$this->middleware('auth');
    	$this->users = $users;
    	$this->activities = $activities;
    }

    /**
     * Displays dashboard based on user's role.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
    	if (Auth::user()->hasRole('Admin')) {
    		return $this->adminDashboard();
    	}

    	return $this->defaultDashboard();
    }

    /**
     * Displays dashboard for admin users.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    private function adminDashboard()
    {
    	$usersPerMonth = $this->users->countOfNewUsersPerMonth(
    		Carbon::now()->subYear(),
    		Carbon::now()
    	);

    	$stats = [
    		'total' => $this->users->count(),
    		'new' => $this->users->newUsersCount(),
    		'banned' => $this->users->countByStatus(UserStatus::BANNED),
    		'unconfirmed' => $this->users->countByStatus(UserStatus::UNCONFIRMED)
    	];

    	$latestRegistrations = $this->users->latest(7);

    	$search = Input::input('search');
    	$query = KnownStructures::query();

    	if ($search) {
    		$query->where(function ($q) use ($search) {
    			$q->sortable();
    			$q->where('name', "like", "%{$search}%");
    			$q->orWhere('type', 'like', "%{$search}%");
    			$q->orWhere('system', 'like', "%{$search}%");
    			$q->orWhere('owner_corporation_name', 'like', "%{$search}%");
    		});
    	}

    	$structures = $query->sortable()->paginate(50);

    	if ($search) {
    		$structures->appends(['search' => $search]);
    		return view('structures.index',  compact('structures'));
    	}

        $tasks = TaskManager::where('tm_state', 1)
        ->orderBy('tm_created_datetime_at', 'ASC')
        ->get();

        return view('dashboard.admin', compact('stats', 'latestRegistrations', 'usersPerMonth', 'tasks'));
    }

    /**
     * Displays default dashboard for non-admin users.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    private function defaultDashboard()
    {
    	$activities = $this->activities->userActivityForPeriod(
    		Auth::user()->id,
    		Carbon::now()->subWeeks(2),
    		Carbon::now()
    	)->toArray();

        $tasks = TaskManager::where('tm_state', 1)
        ->orderBy('tm_created_datetime_at', 'ASC')
        ->get();
        
        return view('dashboard.default', compact('activities', 'tasks'));
    }


}
